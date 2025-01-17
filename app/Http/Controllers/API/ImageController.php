<?php

namespace App\Http\Controllers\API;

use App\Models\Image;
use App\Models\Post;
use App\Models\Product;
use App\Enum\Imageable;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function __construct(
        protected ImageService $service
    ) {

    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['required', 'string'],
            'type' => ['required', Rule::in(Imageable::cases())],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png'],
        ]);

        $image = $request->file('image');

        $imageable = match($data['type']) {
            Imageable::POST->value => Post::query()->find($data['id']),
            Imageable::PRODUCT->value => Product::query()->find($data['id']),
            default => null
        };

        $authenticatedUser = $this->getAuthenticatedUser();

        $this->service->add($imageable, $image, $authenticatedUser);

        return $this->json();
    }

    public function destroy(Image $image): JsonResponse
    {
        $this->service->delete($image);

        return $this->json();
    }
}
