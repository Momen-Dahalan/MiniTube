<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'channel_id',  // إذا الدفع للاشتراك في قناة معينة
        'amount',
        'payment_method',
        'status',
        
    ];

    // العلاقة مع المستخدم اللي عمل الدفع
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع القناة (لو الدفع مرتبط بالاشتراك في قناة)
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
