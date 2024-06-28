<?php

namespace App\Models;

use App\Events\ImageLoaded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $image
 */
class Image extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'category_id'
    ];

    protected $dispatchesEvents = [
        'created' => ImageLoaded::class,
        'retrieved' => ImageLoaded::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
