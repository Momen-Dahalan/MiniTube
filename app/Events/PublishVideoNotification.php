<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublishVideoNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $channel_name;
    public $date;
    public $video_id;
    public function __construct($userId , $channel_name , $date , $video_id)
    {
        $this->userId =$userId;
        $this->channel_name=$channel_name;
        $this->date=$date;
        $this->video_id=$video_id;
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
        new PrivateChannel('user.notifications.' . $this->userId) // تغيير اسم القناة
        ];
    }


    public function broadcastAs(): string
    {
        return 'video.published';
    }


}
