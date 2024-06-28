<?php

namespace App\Services;

use App\Constant\ImageSize;
use App\Models\Category;
use App\Repository\ImageRepository;
use App\Services\Aws\S3;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;

class ImageUpload
{
    public function __construct(
        private ImageRepository $imageRepository,
        private S3 $s3,
    ){
    }

    public function upload(Category $category, UploadedFile $file): \App\Models\Image
    {
        Image::read($file->getRealPath())->resize(ImageSize::HEIGHT, ImageSize::WIDTH)->save($file->getRealPath());

        $filename = sprintf('%s/%s.%s', $category->name, random_int(1, 100000), $file->extension());

        $this->s3->put($filename, $file->getRealPath());

        return $this->imageRepository->create($filename, $category);
    }
}
