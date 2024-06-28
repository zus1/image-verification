<?php

namespace App\Events;

use App\Models\Image;
use Illuminate\Foundation\Events\Dispatchable;

class ImageLoaded
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private Image $image,
    ){
    }

    public function getImage(): Image
    {
        return $this->image;
    }
}
