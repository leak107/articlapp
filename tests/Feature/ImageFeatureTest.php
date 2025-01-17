<?php

use App\Enum\Product\Unit;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('image post test', function () {
    it('can upload image into post', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        /** @var Post $post **/
        $post = Post::query()->updateOrCreate([
            'title' => 'One title',
            'content' => 'one little post',
            'slug' => 'one-title',
            'created_by_id' => User::query()->firstWhere('email', 'admin@article.app')->id,
        ]);

        if ($post->images()->count() > 0) {
            $post->images->each->delete();
        }

        // Image path on storage/app/private
        $imageString = Storage::get('image.jpeg');

        $image = UploadedFile::fake()->createWithContent('image.jpeg', $imageString);

        $this->post(
            uri: route('api.images.store'),
            headers: [
                'Authorization' => 'Bearer ' . $authorizationToken
            ],
            data: [
                'type' => 'post',
                'id' => $post->id,
                'image' => $image
            ],
        );

        $postImages = $post->images;

        expect($postImages->count())->toBe(1)
            ->and($postImages->first()->filename)->toBe('image.jpeg')
            ->and($postImages->first()->size)->toBe($image->getSize());
    });

    it('can delete image from post', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        /** @var Post $post **/
        $post = Post::query()->updateOrCreate([
            'title' => 'Two title',
            'content' => 'two little post',
            'slug' => 'two-title',
            'created_by_id' => User::query()->firstWhere('email', 'admin@article.app')->id,
        ]);

        // Image path on storage/app/private
        $imageString = Storage::get('image2.jpeg');

        $image = UploadedFile::fake()->createWithContent('image.jpeg', $imageString);

        $this->post(
            uri: route('api.images.store'),
            headers: [
                'Authorization' => 'Bearer ' . $authorizationToken
            ],
            data: [
                'type' => 'post',
                'id' => $post->id,
                'image' => $image
            ],
        );

        expect($post->images()->count())->toBe(1);

        $postImage = $post->images->first();

        $this->deleteJson(route('api.images.destroy', $postImage->id));

        $post->refresh();

        expect($post->images()->count())->toBe(0);
    });
});

describe('image product test', function () {
    it('can upload image into product', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        /** @var Product $product **/
        $product = Product::query()->updateOrCreate([
            'name' => 'one apple',
            'price' => 20000,
            'unit' => Unit::KILOGRAM,
            'quantity' => 50,
            'created_by_id' => User::query()->firstWhere('email', 'admin@article.app')->id,
        ]);

        if ($product->images()->count() > 0) {
            $product->images->each->delete();
        }

        // Image path on storage/app/private
        $imageString = Storage::get('apple.jpeg');

        $image = UploadedFile::fake()->createWithContent('apple.jpeg', $imageString);

        $this->post(
            uri: route('api.images.store'),
            headers: [
                'Authorization' => 'Bearer ' . $authorizationToken
            ],
            data: [
                'type' => 'product',
                'id' => $product->id,
                'image' => $image
            ],
        );

        $productImages = $product->images;

        expect($productImages->count())->toBe(1)
            ->and($productImages->first()->filename)->toBe('apple.jpeg')
            ->and($productImages->first()->size)->toBe($image->getSize());
    });

    it('can delete image from product', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        /** @var Product $product **/
        $product = Product::query()->updateOrCreate([
            'name' => 'two apple',
            'price' => 20000,
            'unit' => Unit::KILOGRAM,
            'quantity' => 50,
            'created_by_id' => User::query()->firstWhere('email', 'admin@article.app')->id,
        ]);

        if ($product->images()->count() > 0) {
            $product->images->each->delete();
        }

        // Image path on storage/app/private
        $imageString = Storage::get('apple.jpeg');

        $image = UploadedFile::fake()->createWithContent('apple.jpeg', $imageString);

        $this->post(
            uri: route('api.images.store'),
            headers: [
                'Authorization' => 'Bearer ' . $authorizationToken
            ],
            data: [
                'type' => 'product',
                'id' => $product->id,
                'image' => $image
            ],
        );

        expect($product->images()->count())->toBe(1);

        $productImage = $product->images->first();

        $this->deleteJson(route('api.images.destroy', $productImage->id));

        $product->refresh();

        expect($product->images()->count())->toBe(0);
    });

    it('multiple images on product', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        /** @var Product $product **/
        $product = Product::query()->updateOrCreate([
            'name' => 'three apple',
            'price' => 30000,
            'unit' => Unit::KILOGRAM,
            'quantity' => 50,
            'created_by_id' => User::query()->firstWhere('email', 'admin@article.app')->id,
        ]);

        if ($product->images()->count() > 0) {
            $product->images->each->delete();
        }

        foreach (range(0, 2) as $i) {
            // Image path on storage/app/private
            $imageString = Storage::get('apple.jpeg');

            $image = UploadedFile::fake()->createWithContent('apple.jpeg', $imageString);

            $this->post(
                uri: route('api.images.store'),
                headers: [
                    'Authorization' => 'Bearer ' . $authorizationToken
                ],
                data: [
                    'type' => 'product',
                    'id' => $product->id,
                    'image' => $image
                ],
            );
        }

        $product->refresh();

        expect($product->images()->count())->toBe(3);

        $product->images->each->delete();
    });
});
