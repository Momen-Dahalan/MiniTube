<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'is_like'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
// العلاقة البوليمورفية مع أي موديل يمكن عمل لايك له (فيديو، تعليق، ...).
    public function likeable()
    {
        return $this->morphTo();
    }
}
