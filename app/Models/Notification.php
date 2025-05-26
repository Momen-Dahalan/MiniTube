<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
protected $fillable = ['user_id', 'video_id', 'video_userId', 'type' , 'channel_id' ,'is_read'];
public function user()
{
    return $this->belongsTo(User::class);
}

public function video()
{
    return $this->belongsTo(Video::class);
}

public function channel()
{
    return $this->belongsTo(Channel::class);
}

}
