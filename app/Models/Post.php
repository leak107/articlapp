<?php

namespace App\Models;

use App\Contracts\ImageabeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

/**
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string $created_by_id
 *
 */
class Post extends Model implements ImageabeInterface
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
    * @return MorphMany
    */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
    * @param string $title
    *
    * @return string
    */
    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);

        if ($counter = static::query()->where('slug', 'like', '%' . $slug . '%')->count()) {
            $slug = $slug . '-' . $counter;
        }

        return $slug;
    }
}
