<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
  use HasFactory;

    protected $fillable = [
        'user_id',
        'channel_id',
    ];

    // الاشتراك مرتبط بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الاشتراك مرتبط بالقناة
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}