<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProductController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'email' => ['required', 'email', 'string', 'max:255'],
        'device_name' => ['required', 'string'],
    ]);

    if ($user = User::query()->where('email', $data['email'])->first()) {
        return $user->createToken($data['device_name'])->plainTextToken;
    }

    return false;
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('products', ProductController::class, ['names' => 'api.products'])->except(['create', 'edit', 'show']);
    Route::resource('posts', PostController::class, ['names' => 'api.posts'])->except(['create', 'edit', 'show'])->scoped([
        'post' => 'slug'
    ]);
});
