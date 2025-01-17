<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $service
    ) {
        //
    }

    /**
    * @return JsonResponse
    */
    public function index(): JsonResponse
    {
        $products = $this->service->indexQuery()->paginate(10);

        return $this->json($products);
    }

    /**
    * @param StoreRequest $request
    *
    * @return JsonResponse
    */
    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $product = $this->service->create($data);

        return $this->json([
            'message' => $product instanceof Product ? 'Berhasil membuat produk.' : 'Terjadi kesalahan!',
        ]);
    }

    /**
    * @param UpdateRequest $request
    * @param Product $product
    *
    * @return JsonResponse
    */
    public function update(UpdateRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();

        $product = $this->service->update(
            product: $product,
            data: $data,
        );

        return $this->json([
            'message' => 'Berhasil update produk.'
        ]);
    }

    /**
    * @param Product $product
    *
    * @return JsonResponse
    */
    public function destroy(Product $product): JsonResponse
    {
        throw_if($product->author->id != auth()->id(), new Exception('You are not allowed', 403));

        $product->delete();

        return $this->json([
            'message' => 'Berhasil hapus produk.'
        ]);
    }
}
