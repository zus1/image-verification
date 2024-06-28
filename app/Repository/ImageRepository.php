<?php

namespace App\Repository;

use App\Models\Category;
use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ImageRepository extends BaseRepository
{
    protected const MODEL = Image::class;

    public function __construct(
        private CategoryRepository $categoryRepository,
    ){
    }

    public function findByCategoryName(string $categoryName): Collection
    {
        $builder = $this->getBuilder();
        /** @var Category $category */
        $category = $this->categoryRepository->findOnByOr404(['name' => $categoryName]);


        return $builder->whereRelation('category', 'id', $category->id)
            ->get();
    }

    public function create(string $filename, Category $category): Image
    {
        $image = new Image();
        $image->image = $filename;

        $image->category()->associate($category);

        $image->save();

        return $image;
    }

    public function findForVerification(array $imageIds, string $categoryName): Collection
    {
        $builder = $this->getBuilder();

        return $builder->whereRelation('category', 'name', $categoryName)
            ->whereIn('id', $imageIds)
            ->get();
    }

    public function retrieveCollection(Collection $categories, string $categoryName): Collection
    {
        [$numOfCorrectImages, $numOfIncorrectImages] = $this->getImagesQuantities();

        $images = new Collection();
        /** @var Category $category */
        foreach ($categories as $category) {
            if($category->name === $categoryName) {
                $images = $images->merge($this->getImagesForCategory($category, $numOfCorrectImages));

                continue;
            }

            $images = $images->merge($this->getImagesForCategory($category, ceil($numOfIncorrectImages/($categories->count()-1))));
        }

        return $images->shuffle()->take(config('app.custom.image_num'));
    }

    private function getImagesQuantities(): array
    {
        $numOfImages = (int) config('app.custom.image_num');
        $numOfCorrectImages = (int) config('app.custom.correct_image_num');

        if($numOfCorrectImages >= $numOfImages) {
            throw new HttpException(422,
                sprintf(
                    'Number of correct images %d can not be grater or equal then total number of images %d',
                    $numOfCorrectImages,
                    $numOfImages
                )
            );
        }

        $numOfIncorrectImages = $numOfImages - $numOfCorrectImages;

        return [$numOfCorrectImages, $numOfIncorrectImages];
    }

    private function getImagesForCategory(Category $category, int $imgNum): Collection
    {
        return $this->getBuilder()
            ->whereRelation('category', 'name', $category->name)
            ->orderByRaw('RAND()')
            ->limit($imgNum)->get();
    }
}
