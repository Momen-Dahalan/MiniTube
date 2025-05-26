<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Video extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'channel_id',
        'category_id',
        'title',
        'description',
        'video_path',
        'video_paths',
        'thumbnail',
        'views',
        'is_processed',
    ];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'channel_name' => $this->channel->name ?? '',
            'category' => $this->category->name ?? '',
        ];
    }




    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likesCount()
    {
        return $this->likes()->where('is_like', true)->count();
    }

    public function dislikesCount()
    {
        return $this->likes()->where('is_like', false)->count();
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->where('is_like', true)->exists();
    }
    public function isDislikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->where('is_like', false)->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
