<?php

namespace App\Http\Controllers;

use App\Support\ApiResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class Controller
{
    protected function respondSuccess(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        $paginator = null;

        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $paginator = $data->resource;
        } elseif ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
            $paginator = $data;
        }

        if ($paginator !== null) {
            $pagination = [
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
            ];

            if ($paginator instanceof LengthAwarePaginator) {
                $pagination['last_page'] = $paginator->lastPage();
                $pagination['total'] = $paginator->total();
                $pagination['from'] = $paginator->firstItem();
            }

            $payload = [
                'items' => $data instanceof LengthAwarePaginator || $data instanceof Paginator
                    ? $data->items()
                    : $data,
                'pagination' => $pagination,
            ];

            return ApiResponse::success($payload, $message, $status, $meta);
        }

        if ($data instanceof Collection) {
            $data = $data->values();
        }

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
