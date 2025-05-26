<?php

namespace App\Livewire;

use App\Events\LikeNotification;
use App\Models\Notification;
use App\Models\Video;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VideoLikes extends Component
{
    public Video $video;
    public ?bool $userLikeType = null;

    public function mount()
    {
        $this->refreshLikeStatus();
    }

    public function like()
    {
        $user = Auth::user();
        if (!$user) return;

        $existingLike = $this->video->likes()->where('user_id', $user->id)->first();

        if ($existingLike && $existingLike->is_like) {
            $existingLike->delete();
        } else {
            $this->video->likes()->updateOrCreate(
                ['user_id' => $user->id],
                ['is_like' => true]
            );

            $this->sendNotification($user);
        }

        $this->refreshLikeStatus();
        $this->dispatch('$refresh');
    }

    protected function sendNotification($user)
    {
        $videoOwnerId = $this->video->channel->user->id;

        if ($user->id != $videoOwnerId) {
            event(new LikeNotification(
                $videoOwnerId,
                $user->name,
                $this->video->title,
                Carbon::now()->diffForHumans()
            ));

            Notification::create([
                'user_id' => $videoOwnerId,
                'video_id' => $this->video->id,
                'video_userId' => auth()->user()->id,
                'type' => 'like'
            ]);
        }
    }

    public function dislike()
    {
        $user = Auth::user();
        if (!$user) return;

        $existingLike = $this->video->likes()->where('user_id', $user->id)->first();

        if ($existingLike && !$existingLike->is_like) {
            $existingLike->delete();
        } else {
            $this->video->likes()->updateOrCreate(
                ['user_id' => $user->id],
                ['is_like' => false]
            );
        }

        $this->refreshLikeStatus();
        $this->dispatch('$refresh');
    }

    protected function refreshLikeStatus()
    {
        $user = Auth::user();
        $this->userLikeType = $user 
            ? $this->video->likes()->where('user_id', $user->id)->first()?->is_like 
            : null;
    }

    public function render()
    {
        return view('livewire.video-likes');
    }
}