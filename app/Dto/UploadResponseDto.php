<?php

namespace App\Dto;

use App\Models\Category;
use App\Models\Image;

class UploadResponseDto implements \JsonSerializable
{
    private string $category;
    private string $image;

    public static function crate(Category $category, Image $image): self
    {
        $static = new self();
        $static->image = $image->image;
        $static->category = $category->name;

        return $static;
    }


    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
