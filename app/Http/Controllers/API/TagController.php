<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    /**
    * @return JsonResponse
    */
    public function index(): JsonResponse
    {
        $tags = Tag::query()->get()->toArray();

        return $this->json($tags);
    }
}
