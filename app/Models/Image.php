<?php

namespace App\Models;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $filename
 * @property string $full_path
 * @property string $mime_type
 * @property string $imageable_type
 * @property string $imageable_id
 * @property int $size
 * @property string $created_by_id
 */
class Image extends Model
{
    use HasUuids;

    protected $appends = [
        'url'
    ];

    /**
    * @return BelongsTo
    */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
    * @return MorphTo
    */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function url(): Attribute
    {
        return new Attribute(function () {
            $filesystem = Storage::disk('images');

            return $filesystem->temporaryUrl($this->full_path, now()->addMinutes(5));
        });
    }

    public static function booted(): void
    {
        self::deleted(function (Image $image) {
            Storage::disk('images')->delete($image->full_path);
        });
    }
}

