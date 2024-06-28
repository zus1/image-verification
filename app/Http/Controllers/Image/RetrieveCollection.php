<?php

namespace App\Http\Controllers\Image;

use App\Dto\RetrieveCollectionResponse;
use App\Http\Requests\ImageRequest;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use Illuminate\Http\JsonResponse;

class RetrieveCollection
{
    public function __construct(
        private ImageRepository $imageRepository,
        private CategoryRepository $categoryRepository,
    ){
    }

    public function __invoke(ImageRequest $request): JsonResponse
    {
        $categoryName = $request->query('category');

        $categories = $this->categoryRepository->retrieveCollection($categoryName);
        $images = $this->imageRepository->retrieveCollection($categories, $categoryName);

        return new JsonResponse(RetrieveCollectionResponse::create($categoryName, $images));
    }
}
