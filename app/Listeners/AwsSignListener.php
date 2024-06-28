<?php

namespace App\Listeners;

use App\Events\ImageLoaded;
use App\Services\Aws\S3;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwsSignListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private S3 $s3,
    ){
    }

    /**
     * Handle the event.
     */
    public function handle(ImageLoaded $event): void
    {
        $image = $event->getImage();

        $image->image = $this->s3->singUrl($image->image);
    }
}
