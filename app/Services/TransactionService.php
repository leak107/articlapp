<?php

namespace App\Services;

use App\Enum\Transaction\Type;
use App\Events\TransactionCreated;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class TransactionService
{
    public function create(Product $product, User $customer, array $data): void
    {
        $totalAmount = $product->price * $data['quantity'];

        try {
            DB::transaction(function () use ($product, $customer, $data, $totalAmount){
                $transaction = new Transaction();

                $transaction->type = Type::IN;
                $transaction->customer_email = $customer->email;
                $transaction->customer_name = $customer->name;
                $transaction->total_amount = $totalAmount;
                $transaction->supplier_name = $product->author->name;
                $transaction->notes = $data['notes'] ?? '';
                $transaction->created_by_id = $customer->id;

                $transaction->save();

                $product->quantity = $product->quantity - $data['quantity'];
                $product->save();

                TransactionCreated::dispatch($customer, $product, $transaction, $data['quantity']);
            });
        } catch (Exception $e) {
            logger('ERROR', [
                'message' => $e->getMessage(),
                'product_id' => $product->id,
                'request' => $data
            ]);
        }
    }
}
