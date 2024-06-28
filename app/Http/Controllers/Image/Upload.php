<?php

namespace App\Http\Controllers\Image;

use App\Dto\UploadResponseDto;
use App\Http\Requests\UploadRequest;
use App\Repository\CategoryRepository;
use App\Services\ImageUpload;
use Illuminate\Http\JsonResponse;

class Upload
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ImageUpload $imageUpload,
    ){
    }

    public function __invoke(UploadRequest $request): JsonResponse
    {
        $file = $request->file('image');
        $categoryName = $request->query('category');

        $category = $this->categoryRepository->create($categoryName);
        $image = $this->imageUpload->upload($category, $file);

        return new JsonResponse(UploadResponseDto::crate($category, $image));
    }
}
