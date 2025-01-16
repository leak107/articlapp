<?php

use Tests\TestCase;

describe('post controller test', function () {
    it('can hit posts index api', function () {
        /** @var TestCase $this **/

        $response = $this->get(route('api.posts.index'));

        expect($response->getStatusCode())->toBe(200);
    });

    it('it can create post through posts store api', function () {
        /** @var TestCase $this **/

        $response = $this->postJson(route('api.posts.store'));

        dd($response);
    });
});
