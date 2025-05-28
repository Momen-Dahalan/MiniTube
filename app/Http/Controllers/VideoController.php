<?php

namespace App\Http\Controllers;

use App\Events\PublishVideoNotification;
use App\Jobs\ConvertVideo;
use App\Jobs\ProcessVideoJob;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Video;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use FFMpeg;
use Illuminate\Support\Str;

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
        return view('videos.create', compact(['channel', 'categories'])); // تمرير القنوات والفئات إلى العرض
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable|string',
            'thumbnail' => 'image|required',
            'video_path' => 'required',
        ]);


        $randomPath = Str::random(16); // إنشاء مسار عشوائي
        $videoPath = $randomPath . '.' . $request->video_path->getClientOriginalExtension();
        $imagePath = $randomPath . '.' . $request->thumbnail->getClientOriginalExtension();

        storage_path('app/public/' . $videoPath);
        $request->video_path->storeAs('/', $videoPath, 'public');
        $request->thumbnail->storeAs('/thumnail', $imagePath, 'public');

        $video = Video::create([
            'video_path'  => $videoPath,
            'thumbnail'  => $imagePath,
            'title'       => $request->title,
            'description' => $request->description,
            'channel_id'  => auth()->user()->channel->id,
            'category_id' => $request->category_id
        ]);


        ConvertVideo::dispatch($video);

        return redirect()->back()->with('success', 'تم رفع الفيديو بنجاح وسيتم معالجته قريباً.');
    }



    public function show(Video $video)
    {
        $sessionKey = 'viewed_video_' . $video->id;

        if (!session()->has($sessionKey)) {
            $video->increment('views');
            session()->put($sessionKey, true);
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


    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image',
            'video_path' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ];

        $randomPath = Str::random(16);

        // ✅ تحديث الصورة إن وُجدت
        if ($request->hasFile('thumbnail')) {
            // حذف الصورة القديمة
            if ($video->thumbnail && Storage::disk('public')->exists('thumnail/' . $video->thumbnail)) {
                Storage::disk('public')->delete('thumnail/' . $video->thumbnail);
            }

            $imagePath = $randomPath . '.' . $request->thumbnail->getClientOriginalExtension();
            $request->thumbnail->storeAs('thumnail', $imagePath, 'public');
            $data['thumbnail'] = $imagePath;
        }

        // ✅ تحديث قاعدة البيانات
        $video->update($data);

        return redirect()->back()->with('success', 'تم تعديل الفيديو بنجاح.');
    }


    public function destroy(Video $video)
    {
        $this->authorize('delete', $video);

        // استخراج اسم الفيديو بدون المسار والصيغة
        $videoFilename = pathinfo($video->video_path, PATHINFO_FILENAME); // يعطيك PBdhmJ3RK3G0juPN

        // حذف جميع الملفات التي تحتوي على هذا الاسم (دقات MP4 و WEBM)
        $allFiles = Storage::disk('s3')->allFiles(); // جلب كل الملفات في البوكت

        $relatedFiles = collect($allFiles)->filter(function ($file) use ($videoFilename) {
            return str_contains($file, $videoFilename);
        });

        Storage::disk('s3')->delete($relatedFiles->all());

        // حذف الفيديو من قاعدة البيانات
        $video->delete();

        return redirect()->route('home.index')->with('success', 'تم حذف الفيديو وجميع نسخه بنجاح.');
    }




    public function search(Request $request)
    {
        $query = $request->input('query');
        $videos = Video::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhereHas('channel', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->paginate(9);
        return view('home', compact('videos'));
    }
}
