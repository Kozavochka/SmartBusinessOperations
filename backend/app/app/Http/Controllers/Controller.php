<?php

namespace App\Http\Controllers;

use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function respondSuccess(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return ApiResponse::success($data, $message, $status, $meta);
    }

    protected function respondError(
        string $message,
        int $status,
        array $errors = [],
        ?string $code = null,
        array $meta = []
    ): JsonResponse {
        return ApiResponse::error($message, $status, $errors, $code, $meta);
    }
}
