<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $service
    ) {
        //
    }

    public function store(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . $product->quantity],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $authenticatedUser = $this->getAuthenticatedUser();

        $this->service->create(
            product: $product,
            customer: $authenticatedUser,
            data: $data,
        );

        return $this->json(message: 'Transaksi berhasil dibuat, silahkan cek email anda');
    }
}
