<?php

namespace App\Http\Controllers;

use App\Events\CommentNotification;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
          // التحقق من صحة البيانات
        $data = $request->validate([
            'video_id' => 'required|exists:videos,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);
        // إضافة user_id تلقائيًا من المستخدم الحالي
        $data['user_id'] = auth()->id();

        // إنشاء التعليق
        $comment = Comment::create($data);

        $video = Video::find($data['video_id']);
        $video_title = $video->title;
        $video_owner_id = $video->channel->user->id;   
        $date = Carbon::now()->diffForHumans();     

        if(auth()->user()->id != $video_owner_id){
            event(new CommentNotification(
            $video_owner_id, // معرف صاحب الفيديو الذي تريد إعلامه
            auth()->user()->name,
            $video_title,
            $date
        ));

        }


        $notification = new Notification();
        if(auth()->user()->id != $video_owner_id){
            $notification->user_id= $video_owner_id;
            $notification->video_id = $video->id;
            $notification->video_userId = auth()->user()->id;
            $notification->type = 'comment'; // 👈️ النوع
            $notification->save();
        }

        // بعد الإضافة، نعيد التوجيه مع رسالة نجاح ونربط له بـ id التعليق الجديد
        return redirect()->back()->with('success', 'تم إضافة التعليق بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
