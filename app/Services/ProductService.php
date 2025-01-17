<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductService
{
    public function __construct(
        protected ?Request $request = null
    ) {

    }

    /**
    * @return Builder
    */
    public function indexQuery(): Builder
    {
        $query = Product::query();

        $this->request->whenFilled('title', fn ($value) => $query->where('title', 'like', '%' . $value . '%'));

        return $query;
    }

    /**
    * @param array $data
    *
    * @return Product
    */
    public function create(array $data): Product
    {
        return $this->createOrUpdateProduct($data);
    }

    /**
    * @param Product $product
    * @param array $data
    *
    *
    * @return Product
    */
    public function update(Product $product, array $data): Product
    {
        $data['author_id'] = $product->created_by_id;

        return $this->createOrUpdateProduct(
            data: $data,
            product: $product,
        );
    }

    /**
    * @param array $data
    * @param Product|null $product
    *
    * @return Product
    */
    protected function createOrUpdateProduct(array $data, ?Product $product = null): Product
    {
        $product ??= new Product();

        $product->name = $data['name'];
        $product->price = $data['price'];
        $product->unit = $data['unit'];
        $product->quantity = $data['quantity'];
        $product->created_by_id = $data['author_id'];

        $product->save();

        return $product;
    }

}
