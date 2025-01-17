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

        $productName = 'Product-9';

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

    it('can attach tags into product', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $amount = Product::query()->count();

        $productName = fake()->sentence();

        $tags = $this->getJson(route('api.tags.index'), ['Authorization' => 'Bearer ' . $authorizationToken])->json()['payload'];

        $selectedTags = collect($tags)->random(4)->pluck('id')->toArray();

        $this->postJson(route('api.products.store'), headers: ['Authorization' => 'Bearer ' . $authorizationToken], data: [
            'name' => $productName,
            'price' => 15000.00,
            'unit' => Unit::KILOGRAM->value,
            'quantity' => 20,
            'tags' => $selectedTags
        ])->assertOk();

        expect(Product::query()->count())->toBe($amount + 1);

        $product = Product::query()->where('name', $productName)->first();

        expect($product->tags->pluck('id')->toArray())->toBe($selectedTags);

        $product->delete();
    });

    it('can update tags into product', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $amount = Product::query()->count();

        $productName = fake()->sentence();

        $tags = $this->getJson(route('api.tags.index'), ['Authorization' => 'Bearer ' . $authorizationToken])->json()['payload'];

        $selectedTags = collect($tags)->random(4)->pluck('id')->toArray();

        $this->postJson(route('api.products.store'), headers: ['Authorization' => 'Bearer ' . $authorizationToken], data: [
            'name' => $productName,
            'price' => 15000.00,
            'unit' => Unit::KILOGRAM->value,
            'quantity' => 20,
            'tags' => $selectedTags
        ])->assertOk();

        expect(Product::query()->count())->toBe($amount + 1);

        $product = Product::query()->where('name', $productName)->first();

        expect($product->tags->pluck('id')->toArray())->toBe($selectedTags);

        $this->putJson(route('api.products.update', $product->id), headers: ['Authorization' => 'Bearer ' . $authorizationToken], data: [
            'name' => 'New Product 101',
            'price' => 15000.00,
            'unit' => Unit::KILOGRAM->value,
            'quantity' => 20,
            'tags' => [1, 2],
        ])->assertOk();

        $product->refresh();

        foreach ($product->tags as $tag) {
            expect(in_array($tag->id, [1, 2]))->toBeTrue();
        }

        $product->delete();
    });
});
