<?php

namespace App\Http\Controllers;

use App\Events\PublishVideoNotification;
use App\Jobs\ProcessVideoJob;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use FFMpeg;
class VideoController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $videos = Video::latest()->paginate(6); // عدّل العدد حسب الحاجة
        return view('videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $channel = auth()->user()->channel; // الحصول على القنوات الخاصة بالمستخدم
        $this->authorize('create', $channel);
        $categories = Category::all(); // الحصول على جميع الفئات
        return view('videos.create' , compact(['channel' , 'categories'])); // تمرير القنوات والفئات إلى العرض
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'nullable|exists:categories,id',
        'video_path' => 'required|file|mimes:mp4,avi,mov,mkv',
        'thumbnail' => 'nullable|image',
    ]);

    // 1. رفع الفيديو الخام إلى S3
    $videoFile = $request->file('video_path');
    $videoName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
    $videoPath = $videoFile->storeAs('videos/original', $videoName, 's3');

    // 2. حفظ بيانات الفيديو مبدئياً في قاعدة البيانات مع حالة عدم المعالجة
    $video = Video::create([
        'channel_id' => auth()->user()->channel->id,
        'category_id' => $request->category_id,
        'title' => $request->title,
        'description' => $request->description,
        'video_path' => $videoPath,
        'video_paths'  => null, // <-- أضف هذا السطر هنا
        'is_processed' => false,
    ]);

    // 3. إطلاق الـ Job لمعالجة الفيديو (تحميل الفيديو مؤقتًا وعمل المعالجة داخل الجوب)
    ProcessVideoJob::dispatch($video->id, $videoName);

    $channel_name = $video->channel->name;
    $subscribers = $video->channel->subscribers()
        ->where('user_id', '!=', $video->user_id) // تجنب إرسال إشعار للمالك
        ->pluck('user_id');
    foreach ($subscribers as $subscriberId) {
        event(new PublishVideoNotification(
                $subscriberId,
                $channel_name,
                Carbon::now()->diffForHumans(),
                $video->id,
            ));

        Notification::create([
            'user_id' => $subscriberId,
            'video_id' => $video->id,
            'channel_id'=>$video->channel->id,
            'video_userId' => $video->channel->user->id,
            'type' => 'publish_video',
            'is_read'=>0
        ]);
    }



    // 4. إعادة التوجيه إلى صفحة انتظار المعالجة أو أي صفحة تناسب
    return redirect()->route('videos.processing', $video->id);
}
    public function processing(Video $video)
    {
        return view('videos.processing', compact('video'));
    }

    public function status(Video $video)
    {
        return response()->json(['is_processed' => $video->is_processed]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
    $sessionKey = 'viewed_video_' . $video->id;

    if (!session()->has($sessionKey)) {
        $video->increment('views'); // تزود المشاهدة مرة وحدة
        session()->put($sessionKey, true); // تحفظ أنه شاف الفيديو في الجلسة
    }
        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        $this->authorize('update', $video);
        $categories = Category::all();
        return view('videos.edit', compact('video', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'video_path' => 'nullable|file|mimes:mp4,avi,mov,mkv',
            'thumbnail' => 'nullable|image',
        ]);

        // تحديث بيانات الفيديو (بدون تغيير الفيديو الفعلي)
        $video->title = $request->title;
        $video->description = $request->description;
        $video->category_id = $request->category_id;

        if ($request->hasFile('video_path')) {
            // حذف الفيديو القديم (الأصلي وجميع الجودات)
            if ($video->video_path) {
                Storage::disk('s3')->delete($video->video_path);
            }

            if ($video->video_paths) {
                $oldPaths = json_decode($video->video_paths, true);
                if (is_array($oldPaths)) {
                    foreach ($oldPaths as $oldPath) {
                        Storage::disk('s3')->delete($oldPath);
                    }
                }
            }

            // رفع الفيديو الجديد
            $videoFile = $request->file('video_path');
            $videoName = uniqid() . '.' . $videoFile->getClientOriginalExtension();
            $videoPath = $videoFile->storeAs('videos/original', $videoName, 's3');

            // تحديث مسار الفيديو في الداتا بيس وحالة المعالجة
            $video->video_path = $videoPath;
            $video->video_paths = null;
            $video->is_processed = false;

            // حفظ التحديثات الأولية
            $video->save();

            // إطلاق الجوب لإعادة معالجة الفيديو
            ProcessVideoJob::dispatch($video->id, $videoName);

            // إعادة التوجيه لصفحة معالجة الفيديو
            return redirect()->route('videos.processing', $video->id)
                ->with('success', 'تم تحديث الفيديو وبدأت معالجته.');
        } else {
            // حفظ التحديثات فقط إذا لم يتم رفع فيديو جديد
            $video->save();

            return redirect()->route('videos.show', $video->id)
                ->with('success', 'تم تحديث بيانات الفيديو بنجاح.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $this->authorize('delete', $video);
                // حذف الفيديو من التخزين إذا كان موجود
        if ($video->video_path && Storage::disk('s3')->exists($video->video_path)) {
            Storage::disk('s3')->delete($video->video_path);
        }

        // حذف الفيديو من قاعدة البيانات
        $video->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('home.index')->with('success', 'تم حذف الفيديو بنجاح.');
    }



    public function search(Request $request)
    {
        $query = $request->input('query');
        $videos = Video::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhereHas('channel', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('category', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->paginate(9);
            return view('home', compact('videos'));
    }
}
