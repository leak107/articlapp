<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
    * @param array $payload
    * @param string $message
    *
    * @return JsonResponse
    */
    public function json(array $payload = [], $message = ''): JsonResponse
    {
        return response()->json([
            'payload' => $payload,
            'message' => $message,
        ]);
    }
}
