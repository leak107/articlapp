<?php

use App\Models\Post;
use Tests\TestCase;

describe('post api test', function () {
    it('can hit posts index api', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $response = $this->getJson(route('api.posts.index'), headers: ['Authorization' => 'Bearer ' . $authorizationToken]);

        $response->assertJsonStructure([
            'payload',
        ])->assertOk();
    });

    it('it can create post through posts store api', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $title = fake()->sentence();
        $content = fake()->sentence();

        $slug = Post::generateSlug($title);

        $response = $this->postJson(
            uri: route('api.posts.store'),
            headers: [
                'Authorization' => 'Bearer ' . $authorizationToken,
            ],
            data: [
                'title' => $title,
                'content' => $content,
            ]
        );

        $post = Post::query()->where('slug', $slug)->first();

        expect($post->title)->toBe($title)->and($post->content)->toBe($content);

        $post->delete();
    });

    it('it can update a post through update api', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $title = fake()->sentence();
        $content = fake()->sentence();

        $slug = Post::generateSlug($title);

        $response = $this->postJson(
            uri: route('api.posts.store'),
            headers: [
                'Authorization' => 'Bearer ' . $authorizationToken,
            ],
            data: [
                'title' => $title,
                'content' => $content,
            ]
        );

        /** @var Post $post **/
        $post = Post::query()->where('slug', $slug)->first();

        $title = 'when duck fly bird drop';
        $newSlug = Post::generateSlug($title);

        $response = $this->putJson(route('api.posts.update', $slug), [
            'title' => $title,
            'content' => $post->content
        ])->assertOk();

        $post->refresh();

        expect($post->title)->toBe($title)->and($post->slug)->toBe($newSlug);
    });

    it('can delete newly created post', function () {
        /** @var TestCase $this **/
        $authorizationToken = $this->postJson(route('api.login'), [
            'email' => 'admin@article.app',
            'device_name' => 'testing'
        ])['token'];

        $amount = Post::query()->count();

        $title = fake()->sentence();
        $content = fake()->sentence();

        $slug = Post::generateSlug($title);

        $response = $this->postJson(
            uri: route('api.posts.store'),
            headers: [
                'Authorization' => 'Bearer ' . $authorizationToken,
            ],
            data: [
                'title' => $title,
                'content' => $content,
            ]
        )->assertOk();

        expect(Post::query()->count())->toBe($amount + 1);

        $response = $this->deleteJson(route('api.posts.destroy', $slug), headers: ['Authorization' => 'Bearer ' . $authorizationToken])->assertOk();

        expect(Post::query()->count())->toBe($amount);
    });
});
