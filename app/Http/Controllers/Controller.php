<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
    * @param mixed $payload
    * @param string $message
    *
    * @return JsonResponse
    */
    public function json(mixed $payload = [], $message = ''): JsonResponse
    {
        return response()->json([
            'payload' => $payload,
            'message' => $message,
        ]);
    }
}
