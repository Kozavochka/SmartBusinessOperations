<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return $this->respondError('Invalid credentials.', 401, [], 'INVALID_CREDENTIALS');
        }

        return $this->respondSuccess($this->tokenPayload($token), 'Authenticated.');
    }

    public function me(): JsonResponse
    {
        return $this->respondSuccess(Auth::guard('api')->user());
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return $this->respondSuccess(null, 'Logged out.');
    }

    public function refresh(): JsonResponse
    {
        $token = Auth::guard('api')->refresh();

        return $this->respondSuccess($this->tokenPayload($token), 'Token refreshed.');
    }

    private function tokenPayload(string $token): array
    {
        $ttl = Auth::guard('api')->factory()->getTTL();

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60,
        ];
    }
}
