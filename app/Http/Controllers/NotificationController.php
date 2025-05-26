<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
public function index()
{
    $someNotifications = Notification::with(['video.channel', 'user'])
        ->where('user_id', auth()->id())
        ->where('is_read', false)
        ->orderBy('created_at', 'desc')
        ->limit(4)
        ->get();

    $data = $someNotifications->map(function ($item) {
        return [
            'item' => $item,
            'comment_user_name' => $item->user->name ?? 'مستخدم محذوف',
            'video_title' => $item->video->title ?? 'فيديو محذوف',
            'channel_name' => $item->video->channel->name ?? 'قناة محذوفة',
            'video_id' => $item->video->id ?? null, // تأكد من وجودها
            'channel_id' => $item->video->channel->id ?? null, // أضف هذا
            'comment_id' => $item->comment_id ?? null, // أضف هذا
            'date' => $item->created_at->diffForHumans(),
            'type' => $item->type,
        ];
    });

    return response()->json([
        'someNotifications' => $data
    ]);
}

    public function markAllRead()
    {
        // الصحيح: تحديث إشعارات المستخدم الحالي (user_id = auth()->id())
        $updated = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => $updated ? 'success' : 'failed',
            'updated' => $updated
        ]);
    }
}