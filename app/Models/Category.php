<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name_ar', 'name_en'];
    // علاقة التصنيفات بالفيديوهات
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale(); // الحصول على اللغة الحالية

        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

}
