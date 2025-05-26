<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment_user_name;
    public $video_title;
    public $date;

    protected $userId;  // لتحديد قناة البث حسب المستخدم

    public function __construct($userId, $comment_user_name, $video_title, $date)
    {
        $this->userId = $userId;
        $this->comment_user_name = $comment_user_name;
        $this->video_title = $video_title;
        $this->date = $date;
    }

    // تحديد القناة التي سيتم بث الحدث عليها
    public function broadcastOn()
    {
        return new PrivateChannel('video-owner.' . $this->userId);
    }

    // اسم الحدث الذي سيرسل إلى العميل
    public function broadcastAs()
    {
        return 'CommentNotification';
    }
}
