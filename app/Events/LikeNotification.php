<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikeNotification implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $comment_user_name;
    public $video_title;
    public $date;

    public function __construct(
        public $userId,
        $comment_user_name,
        $video_title,
        $date
    ) {
        $this->comment_user_name = $comment_user_name;
        $this->video_title = $video_title;
        $this->date = $date;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('video-like.'.$this->userId)
        ];
    }

    public function broadcastAs(): string
    {
        return 'LikeNotification';
    }
}