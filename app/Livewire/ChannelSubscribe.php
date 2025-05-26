<?php

// app/Livewire/ChannelSubscribe.php

namespace App\Livewire;

use App\Events\SubscribeNotification;
use App\Models\Channel;
use App\Models\Notification;
use App\Models\Subscribe;
use App\Notifications\ChannelSubscribed;
use Livewire\Component;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChannelSubscribe extends Component
{
    public Channel  $channel;
    public bool $subscribed = false;

    public function mount(Channel $channel)
    {
        $this->channel = $channel;
        $this->subscribed = Subscribe::where('user_id', Auth::id())
                                        ->where('channel_id', $this->channel->id)
                                        ->exists();
    }

    public function toggleSubscribe()
    {
        if ($this->subscribed) {
            Subscribe::where('user_id', Auth::id())
                        ->where('channel_id', $this->channel->id)
                        ->delete();
            $this->subscribed = false;
        } else {
            Subscribe::create([
                'user_id' => Auth::id(),
                'channel_id' => $this->channel->id,
            ]);
            $this->subscribed = true;

            $user_name = auth()->user()->name;
            $date = Carbon::now()->diffForHumans();
            $userId=$this->channel->user->id;

            event(new SubscribeNotification(
            $userId,
         $user_name,
              $date
            ));


            $notification = new Notification();
            $notification->user_id= $this->channel->user->id;
            $notification->video_id = null;
            $notification->channel_id = $this->channel->id;
            $notification->video_userId = auth()->user()->id;
            $notification->type='subscribe';
            $notification->save();

            // إعادة تحميل بيانات القناة من قاعدة البيانات لتحديث العداد
            $this->channel->refresh();
        }
    }

    public function render()
    {
        return view('livewire.channel-subscribe', [
            'subscriberCount' => $this->channel->subscribers()->count(),
        ]);
    }
}

