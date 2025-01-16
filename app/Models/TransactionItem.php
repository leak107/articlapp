<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'quantity',
        'quantity_before',
        'quantity_after',
        'total_amount',
        'product_id',
        'transaction_id',
        'created_by_id',
    ];

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
