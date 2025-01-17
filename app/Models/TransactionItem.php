<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $quantity
 * @property int $quantity_before
 * @property int $quantity_after
 * @property float $total_amount
 * @property string $product_id
 * @property string $transaction_id
 * @property string $created_by_id
 */
class TransactionItem extends Model
{
    use HasUuids;

    /**
    * @return BelongsTo
    */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
    * @return BelongsTo
    */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
    /**
    * @return BelongsTo
    */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
