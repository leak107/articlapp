<?php

namespace App\Models;

use App\Contracts\ImageableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

/**
 * @property-read string $id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string $created_by_id
 *
 */
class Post extends Model implements ImageableInterface
{
    use HasUuids;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'created_by_id',
    ];

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
    * @return MorphToMany
    */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
