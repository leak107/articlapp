<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostService
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
        $query = Post::query();

        $this->request->whenFilled('title', fn ($value) => $query->where('title', 'like', '%' . $value . '%'));

        return $query;
    }

    /**
    * @param array $data
    *
    * @return Post
    */
    public function create(array $data): Post
    {
        $slug = Post::generateSlug($data['title']);

        $data['slug'] = $slug;

        return $this->createOrUpdatePost($data);
    }

    /**
    * @param Post $post
    * @param array $data
    *
    *
    * @return Post
    */
    public function update(Post $post, array $data): Post
    {
        $data['author_id'] = $post->created_by_id;
        $data['slug'] = $post->title == $data['title'] ? $post->slug : Post::generateSlug($data['title']);

        return $this->createOrUpdatePost(
            data: $data,
            post: $post,
        );
    }

    /**
    * @param array $data
    * @param Post|null $post
    *
    * @return Post
    */
    protected function createOrUpdatePost(array $data, ?Post $post = null): Post
    {
        $post ??= new Post();

        $post->slug = $data['slug'];
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->created_by_id = $data['author_id'];

        $post->save();

        if (array_key_exists('tags', $data)) {
            $post->tags()->sync($data['tags']);
        }

        return $post;
    }
}
