<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
            'meta' => (object) $meta,
        ], $status);
    }

    public static function error(
        string $message,
        int $status,
        array $errors = [],
        ?string $code = null,
        array $meta = []
    ): JsonResponse {
        if ($code !== null) {
            $meta['code'] = $code;
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'meta' => (object) $meta,
        ], $status);
    }
}
