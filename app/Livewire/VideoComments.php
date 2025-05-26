<?php

namespace App\Livewire;

use App\Events\CommentNotification;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VideoComments extends Component
{
    public $videoId;
    public $content;
    public $parentId = null;

    public $editingCommentId = null;
    public $editingContent = ''; // نفس الاسم في الـ Blade

    protected $rules = [
        'content' => 'required|string|min:3|max:1000',
    ];

    protected $updateRules = [
        'editingContent' => 'required|string|min:3|max:1000',
    ];

    public function mount($videoId)
    {
        $this->videoId = $videoId;
    }

    public function addComment()
    {
        $this->validate();

        Comment::create([
            'user_id' => Auth::id(),
            'video_id' => $this->videoId,
            'parent_id' => $this->parentId,
            'content' => $this->content,
        ]);

        $this->content = '';
        $this->parentId = null;


        $video = Video::find($this->videoId);
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
            $notification->user_id= auth()->user()->id;
            $notification->video_id = $video->id;
            $notification->video_userId = $video_owner_id;
            $notification->save();
        }
 
    }

    public function setReply($commentId)
    {
        $this->parentId = $commentId;
        $this->content = '';
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id === auth()->id()) {
            $this->editingCommentId = $id;
            $this->editingContent = $comment->content;
        }
    }

    public function update()
    {
        $this->validate($this->updateRules);

        $comment = Comment::findOrFail($this->editingCommentId);
        if ($comment->user_id === auth()->id()) {
            $comment->content = $this->editingContent;
            $comment->save();

            $this->editingCommentId = null;
            $this->editingContent = '';
        }
    }

    public function cancel()
    {
        $this->editingCommentId = null;
        $this->editingContent = '';
    }



    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // تأكد أن المستخدم هو صاحب التعليق أو لديك صلاحيات للحذف
        if ($comment->user_id === auth()->id()) {
            // حذف الردود أولاً إذا كانت موجودة (لتجنب مشاكل القيد)
            $comment->replies()->delete();
            // حذف التعليق نفسه
            $comment->delete();

            // يمكن تضيف رسالة نجاح أو تحديث التعليقات
        } else {
            // يمكن ترسل رسالة خطأ أو من دون تنفيذ شيء
            session()->flash('error', 'ليس لديك صلاحية لحذف هذا التعليق.');
        }
    }



    public function render()
    {
        $comments = Comment::where('video_id', $this->videoId)
                           ->whereNull('parent_id')
                           ->with('user', 'replies.user')
                           ->latest()
                           ->get();

        return view('livewire.video-comments', ['comments' => $comments]);
    }
}
