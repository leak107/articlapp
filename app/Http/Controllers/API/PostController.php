<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use Exception;

class PostController extends Controller
{
    public function __construct(
        protected PostService $service
    ) {
        //
    }

    /**
    * @return JsonResponse
    */
    public function index(): JsonResponse
    {
        $posts = $this->service->indexQuery()->paginate(10);

        return response()->json([
            'payload' => $posts
        ]);
    }

    /**
    * @param StoreRequest $request
    *
    * @return JsonResponse
    */
    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $post = $this->service->create($data);

        return $this->json([
            'message' => $post instanceof Post ? 'Berhasil membuat post.' : 'Terjadi kesalahan!',
        ]);
    }

    /**
    * @param UpdateRequest $request
    * @param Post $post
    *
    * @return JsonResponse
    */
    public function update(UpdateRequest $request, Post $post): JsonResponse
    {
        $data = $request->validated();

        $post = $this->service->update(
            post: $post,
            data: $data,
        );

        return $this->json([
            'message' => 'Berhasil update post.'
        ]);
    }

    /**
    * @param Post $post
    *
    * @return JsonResponse
    */
    public function destroy(Post $post): JsonResponse
    {
        throw_if($post->author->id != auth()->id(), new Exception('You are not allowed', 403));

        $post->delete();

        return $this->json([
            'message' => 'Berhasil hapus post.'
        ]);
    }
}
