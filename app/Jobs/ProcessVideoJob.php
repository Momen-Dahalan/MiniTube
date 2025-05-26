<?php

namespace App\Jobs;

use App\Events\PublishVideoNotification;
use App\Models\Notification;
use App\Models\Video;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoId;
    protected $videoName;

    public function __construct($videoId, $videoName)
    {
        $this->videoId = $videoId;
        $this->videoName = $videoName;
    }

    public function handle()
    {
        $video = Video::findOrFail($this->videoId);
        $s3Path = $video->video_path;
        $tempLocalPath = storage_path("app/temp/{$this->videoName}");

        // تحميل الفيديو الأصلي من S3 إلى التخزين المحلي المؤقت
        $s3Client = Storage::disk('s3')->getClient();
        $s3Client->getObject([
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $s3Path,
            'SaveAs' => $tempLocalPath,
        ]);

        $ffmpeg = FFMpeg::create();
        $videoFF = $ffmpeg->open($tempLocalPath);

        // الجودات المطلوبة
        $qualities = [
            '720p' => new Dimension(1280, 720),
            '480p' => new Dimension(854, 480),
            '360p' => new Dimension(640, 360),
            '240p' => new Dimension(426, 240),
        ];

        $videoPaths = [];

        foreach ($qualities as $quality => $dimension) {
            $convertedName = $quality . '_' . $this->videoName;
            $convertedLocalPath = storage_path("app/temp/$convertedName");

            // تغيير الأبعاد وتصدير النسخة
            $videoFF->filters()->resize($dimension)->synchronize();
            $videoFF->save(new X264(), $convertedLocalPath);

            // رفع النسخة إلى S3
            $finalS3Path = "videos/processed/$convertedName";
            Storage::disk('s3')->put($finalS3Path, file_get_contents($convertedLocalPath));

            // حفظ الرابط داخل مصفوفة الجودات
            $videoPaths[$quality] = $finalS3Path;

            // حذف الملف المؤقت
            @unlink($convertedLocalPath);
        }

        // إنشاء صورة مصغرة من الثانية 1
        $thumbnailName = 'thumb_' . uniqid() . '.jpg';
        $thumbnailLocalPath = storage_path("app/temp/$thumbnailName");
        $videoFF->frame(TimeCode::fromSeconds(1))->save($thumbnailLocalPath);

        // رفع الصورة المصغرة إلى التخزين العام (public)
        Storage::disk('public')->put("thumbnails/$thumbnailName", file_get_contents($thumbnailLocalPath));


        // تحديث السجل في قاعدة البيانات
        $video->update([
            'video_paths' => json_encode($videoPaths), // روابط الجودات المختلفة
            'thumbnail' => "thumbnails/$thumbnailName",
            'is_processed' => true,
        ]);



        // حذف الملفات المؤقتة
        @unlink($tempLocalPath);
        @unlink($thumbnailLocalPath);




    }
}
