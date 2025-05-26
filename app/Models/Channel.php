<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description', 'image'];

    // العلاقة مع المستخدم (صاحب القناة)
       public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الفيديوهات
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

        public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscribes');
    }
}
