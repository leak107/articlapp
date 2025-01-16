<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasUuids;

    protected $fillable = [
        'filename',
        'full_path',
        'mime_type',
        'size',
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
    * @return MorphTo
    */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}

