<?php

namespace App\Models;

use App\Enum\Product\Unit;
use App\Contracts\ImageabeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property string $name
 * @property float $price
 * @property Unit $unit
 * @property int $quantity
 * @property string $created_by_id
 *
 */
class Product extends Model implements ImageabeInterface
{
    use HasUuids;

    /**
    * @return BelongsTo
    */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'unit' => Unit::class,
        ];
    }

    /**
    * @return MorphMany
    */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
    * @return MorphToMany
    */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
