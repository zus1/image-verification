<?php

namespace App\Repository;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository
{
    protected const MODEL = Category::class;

    public function create(string $name): Category
    {
        /** @var Category $existing */
        $existing = $this->findOneBy(['name' => $name]);

        if($existing !== null) {
            return $existing;
        }

        $category = new Category();
        $category->name = $name;

        $category->save();

        return $category;
    }

    public function retrieveCollection(?string &$categoryName): Collection
    {
        $builder = $this->getBuilder();

        $categoriesDb = $builder->get();
        $categoriesDb = $categoriesDb->shuffle();

        if($categoryName !== null) {
            $categories = new Collection();
            $chosenCategoryKey = $categoriesDb->search(function (Category $category) use ($categoryName) {
                return $category->name === $categoryName;
            });

            $categories->add($categoriesDb->get($chosenCategoryKey));
            $categoriesDb->forget($chosenCategoryKey);

            return $categories->merge($categoriesDb->take((int) config('app.custom.category_num') - 1));
        }

        /** @var Category $category */
        $category = $categoriesDb->get(0);
        $categoryName = $category->name;

        return $categoriesDb->take((int) config('app.custom.category_num'));
    }
}
