<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DecrementProductQuantityAfterTransaction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCreated $event): void
    {
        /** @var Product $product **/
        $product = $event->product;

        /** @var Transaction $transaction **/
        $transaction = $event->transaction;

        /** @var User $customer **/
        $customer = $event->customer;

        $item = new TransactionItem;

        $item->quantity = $event->quantity;
        $item->quantity_before = $product->quantity;
        $item->quantity_after = $product->quantity - $event->quantity;
        $item->total_amount = $transaction->total_amount;
        $item->product_id = $product->id;
        $item->transaction_id = $transaction->id;
        $item->created_by_id = $transaction->created_by_id;
        $item->save();
    }
}
