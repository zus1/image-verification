<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 */
class Category extends Model
{
    use HasFactory;

    public function images():  HasMany
    {
        return $this->hasMany(Image::class);
    }
}
