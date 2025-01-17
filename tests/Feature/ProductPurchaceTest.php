<?php

use App\Enum\Product\Unit;
use App\Models\Product;

describe('customer buy a product', function () {
    it('customer can login and buy product', function () {
        /** @var TestCase $this **/
        $adminAuthorizationToken = $this->postJson(route('api.login'), data: [
            'email' => 'admin@article.app',
            'device_name' => 'testing',
        ])['token'];

        if (Product::query()->where('name', 'Buah Apel Impor')->doesntExist()) {
            $this->postJson(route('api.products.store'), headers: ['Authorization' => 'Bearer ' . $adminAuthorizationToken], data: [
                'name' => 'Buah Apel Impor',
                'price' => 60000.00,
                'unit' => Unit::KILOGRAM->value,
                'quantity' => 50
            ])->assertOk();
        }

        $product = Product::query()->where('name', 'Buah Apel Impor')->first();

        if ($product->quantity < 20) {
            $product->quantity = 50;
            $product->save();
        }

        $customerAuthorizationToken = $this->postJson(route('api.login'), data: [
            'email' => 'one@customer',
            'device_name' => 'testing',
        ])['token'];

        $response = $this->postJson(route('api.transactions.store', $product->id), headers: ['Authorization' => 'Bearer ' . $customerAuthorizationToken], data: [
            'quantity' => 20,
            'notes' => 'Tolong pilih apel yang bagus ya.'
        ]);

        $product->refresh();

        $response->assertOk();
    });
});
