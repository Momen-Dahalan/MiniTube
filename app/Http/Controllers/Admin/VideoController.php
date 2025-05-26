<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessVideoJob;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos =Video::with('channel', 'category')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.videos.index' , compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        $categories = Category::all();
        return view('admin.videos.edit', compact('video', 'categories'));
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

            return redirect()->route('admin.videos.index', $video->id)
                ->with('success', 'تم تحديث بيانات الفيديو بنجاح.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
                        // حذف الفيديو من التخزين إذا كان موجود
        if ($video->video_path && Storage::disk('s3')->exists($video->video_path)) {
            Storage::disk('s3')->delete($video->video_path);
        }

        // حذف الفيديو من قاعدة البيانات
        $video->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('admin.videos.index')->with('success', 'تم حذف الفيديو بنجاح.');
    }
}
