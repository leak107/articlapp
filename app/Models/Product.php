<?php

namespace App\Models;

use App\Enum\Product\Unit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasUuids;

    /**
    * @var
    *
    */
    protected $fillable = [
        'name',
        'price',
        'unit',
        'quantity',
        'created_by_id',
    ];

    /**
    * @return BelongsTo
    */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_by_id');
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
}
