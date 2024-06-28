<?php

namespace App\Dto;

use Illuminate\Database\Eloquent\Collection;

class RetrieveCollectionResponse implements \JsonSerializable
{
    private string $category;
    private array $images;

    public static function create(string $categoryName, Collection $images): self
    {
        $instance = new self();
        $instance->category = $categoryName;
        $instance->images = $images->all();

        return $instance;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);

    }
}
