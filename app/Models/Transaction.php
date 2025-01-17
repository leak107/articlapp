<?php

namespace App\Models;

use App\Enum\Transaction\Type;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Type $type
 * @property string $customer_email
 * @property string $customer_name
 * @property float $total_amount
 * @property string $supplier_name
 * @property string $notes
 * @property string $created_by_id
 */
class Transaction extends Model
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
            'type' => Type::class,
        ];
    }
}
