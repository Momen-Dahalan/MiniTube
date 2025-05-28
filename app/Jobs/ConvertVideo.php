<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use App\Models\ConvertedVideo;
use App\Models\Video;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\VideoFilters;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\X264;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFProbe;


class ConvertVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $video;
    public $format;
    public $videoWidth;
    public $videoHeight;
    public $i;
    public $names;
    public function __construct(Video $video)
    {
        $this->video = $video;
    }


    /**
     * Execute the job.
     */



    public function convertVideo($loopNumber){
        $this->format = [
            [
                (new X264('aac' , 'libx264'))
                    ->setKiloBitrate(4096),

                (new WebM('libvorbis', 'libvpx'))
                    ->setKiloBitrate(4096)
            ],

            [
                (new X264('aac' , 'libx264'))
                    ->setKiloBitrate(2048),
    
                (new WebM('libvorbis', 'libvpx'))
                    ->setKiloBitrate(2048)
            ],
    
            [
                (new X264('aac' , 'libx264'))
                    ->setKiloBitrate(1024),
    
                (new WebM('libvorbis', 'libvpx'))
                    ->setKiloBitrate(1024)
            ],
    
            [
                (new X264('aac' , 'libx264'))
                    ->setKiloBitrate(512),
    
                (new WebM('libvorbis', 'libvpx'))
                    ->setKiloBitrate(512)
            ],
                
            [
                (new X264('aac' , 'libx264'))
                    ->setKiloBitrate(256),
    
                (new WebM('libvorbis', 'libvpx'))
                    ->setKiloBitrate(256)
            ]

        ];


        $this->videoWidth = [
            1920,
            1280,
            854,
            640,
            426
        ];

        $this->videoHeight = [
            1080,
            720,
            480,
            360,
            240
        ];

        $this->names = array(
            array(
                '1080p-' . $this->getFileName($this->video->video_path, '.mp4'), '1080p-' . $this->getFileName($this->video->video_path, '.webm')
            ),
            array(
                '720p-' . $this->getFileName($this->video->video_path, '.mp4'), '720p-' . $this->getFileName($this->video->video_path, '.webm')
            ),
            array(
                '480p-' . $this->getFileName($this->video->video_path, '.mp4'), '480p-' . $this->getFileName($this->video->video_path, '.webm')
            ),
            array(
                '360p-' . $this->getFileName($this->video->video_path, '.mp4'), '360p-' . $this->getFileName($this->video->video_path, '.webm')
            ),
            array(
                '240p-' . $this->getFileName($this->video->video_path, '.mp4'), '240p-' . $this->getFileName($this->video->video_path, '.webm')
            ),
        );


        for ($this->i = $loopNumber; $this->i < 5; $this->i++) {
            for ($j = 0; $j < 2; $j++) {
                FFMpeg::fromDisk('public')
                    ->open($this->video->video_path)
                    ->export()
                    ->toDisk(env('FILESYSTEM_DISK'))
                    ->inFormat($this->format[$this->i][$j])
                    ->addFilter(function (VideoFilters $filters) {
                        $filters->resize(new Dimension($this->videoWidth[$this->i], $this->videoHeight[$this->i]));
                    })
                    ->save($this->names[$this->i][$j]);
            }
        }







    }



    public function handle(): void
    {
        $ffprobe = FFProbe::create();
        $video1 = $ffprobe->streams(public_path('/storage//' .$this->video->video_path))->videos()->first();
        $width = $video1->get('width');
        $height = $video1->get('height');

        $media= FFMpeg::fromDisk('public')
        ->open($this->video->video_path);
        
        $durationInSeconds = $media->getDurationInSeconds();

        $hours = floor($durationInSeconds / 3600);
        $minutes = floor(($durationInSeconds / 60) % 60);
        $seconds = $durationInSeconds % 60;

        $quality = 0;

           //المقطع عرضي
        if ($width > $height) {

            if(($width >= 1920) && ($height >=1080)) {
                $quality = 1080;
                $this->convertVideo(0);
            }
            elseif(($width >= 1280) && ($height >=720) && ($width < 1920 && $height < 1080)) {
                $quality = 720;
                $this->convertVideo(1);

            }
            elseif(($width >= 854) && ($height >=480) && ($width < 1280 && $height < 720)) {
                $quality = 480;
                $this->convertVideo(2);

            }
            elseif(($width >= 640) && ($height >=360) && ($width < 854 && $height < 480)) {
                $quality = 360;
                $this->convertVideo(3);

            }
            else {
                $quality = 240;
                $this->convertVideo(4);
            }
        }
        else if($height > $width) { //المقطع طولي  

            $this->video->update([
                'Longitudinal' => true
            ]);
            
            if(($height >= 1920) && ($width >=1080)) {
                $quality = 1080;
                $this->convertVideo(0);
            }
            elseif(($height >= 1280) && ($width >=720) && ($height < 1920 && $width < 1080)) {
                $quality = 720;
                $this->convertVideo(1);

            }
            elseif(($height >= 854) && ($width >=480) && ($height < 1280 && $width < 720)) {
                $quality = 480;
                $this->convertVideo(2);

            }
            elseif(($height >= 640) && ($width >=360) && ($height < 854 && $width < 480)) {
                $quality = 360;
                $this->convertVideo(3);
            }
            else {
                $quality = 240;
                $this->convertVideo(4);
            }
        }



        Storage::disk('public')->delete($this->video->video_path);
        $converted_video = new ConvertedVideo();

        for ($i = 0; $i < 5; $i++) {
            $converted_video->{ 'mp4_Format_'. $this->videoHeight[$i] } = $this->names[$i][0];
            $converted_video->{ 'webm_Format_'. $this->videoHeight[$i] } = $this->names[$i][1];
        }



        $converted_video->video_id = $this->video->id;
        $converted_video->save();

        $this->video->update([
            'processed' => true,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'quality' => $quality,
        ]);
    }



    private function getFileName($filename, $type) {
        return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) .$type;   
    }


}
