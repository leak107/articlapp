<?php

use App\Enum\Product\Unit;
use App\Models\Product;

describe('product api test', function () {
    it('can hit products index api', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $response = $this->getJson(route('api.products.index'), headers: ['Authorization' => 'Bearer ' . $authorizationToken]);

        $response->assertJsonStructure([
            'payload',
        ])->assertOk();
    });

    it('can create product through product store api', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $amount = Product::query()->count();

        $this->postJson(route('api.products.store'), headers: ['Authorization' => 'Bearer ' . $authorizationToken], data: [
            'name' => fake()->sentence(),
            'price' => 15000.00,
            'unit' => Unit::KILOGRAM->value,
            'quantity' => 20
        ])->assertOk();

        expect(Product::query()->count())->toBe($amount + 1);
    });

    it('can update product through update api', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $amount = Product::query()->count();

        $productName = 'Product-1';

        $this->postJson(route('api.products.store'), headers: ['Authorization' => 'Bearer ' . $authorizationToken], data: [
            'name' => $productName,
            'price' => 15000.00,
            'unit' => Unit::KILOGRAM->value,
            'quantity' => 20
        ])->assertOk();

        expect(Product::query()->count())->toBe($amount + 1);

        $product = Product::query()->where('name', $productName)->first();

        $this->putJson(route('api.products.update', $product->id), headers: ['Authorization' => 'Bearer ' . $authorizationToken], data: [
            'name' => 'New Product',
            'price' => 15000.00,
            'unit' => Unit::KILOGRAM->value,
            'quantity' => 20
        ])->assertOk();

        $product->refresh();

        expect($product->name)->toBe('New Product');

        $product->delete();
    });

    it('can delete product through delete api', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $amount = Product::query()->count();

        $currentUnixTime = now()->unix();
        $productName = 'Product-2';

        $this->postJson(route('api.products.store'), headers: ['Authorization' => 'Bearer ' . $authorizationToken], data: [
            'name' => $productName,
            'price' => 15000.00,
            'unit' => Unit::KILOGRAM->value,
            'quantity' => 20
        ])->assertOk();

        expect(Product::query()->count())->toBe($amount + 1);

        $product = Product::query()->where('name', $productName)->first();

        $this->deleteJson(route('api.products.destroy', $product->id), headers: ['Authorization' => 'Bearer ' . $authorizationToken])->assertOk();

        expect(Product::query()->count())->toBe($amount);
    });
});
