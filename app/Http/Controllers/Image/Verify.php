<?php

namespace App\Http\Controllers\Image;

use App\Http\Requests\ImageRequest;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class Verify
{
    public function __construct(
        private ImageRepository $repository
    ){
    }

    public function __invoke(ImageRequest $request): JsonResponse
    {
        $categoryName = $request->input('category');
        $imageIds = (array) $request->input('images');

        $images = $this->repository->findForVerification($imageIds, $categoryName);

        return new JsonResponse([
            'verified' => $images->count() === config('app.custom.correct_image_num')
        ]);
    }
}
