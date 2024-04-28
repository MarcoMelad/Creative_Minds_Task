<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Interfaces\UserAuthRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly UserAuthRepositoryInterface $authRepository)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authRepository->register($request->validated());
        return response()->json($result, $result['status_code']);
    }
    public function verify(Request $request): JsonResponse
    {
        $result = $this->authRepository->verify($request);
        return response()->json($result, $result['status_code']);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authRepository->login($request->validated());
        return response()->json($result, $result['status_code']);
    }

    public function user_profile(): JsonResponse
    {
        $result = $this->authRepository->user_profile();
        return response()->json($result, $result['status_code']);
    }
    public function logout(): JsonResponse
    {
        $result = $this->authRepository->logout();
        return response()->json($result, $result['status_code']);
    }
    public function nearestDelivery()
    {
        return $this->authRepository->nearestDelivery();
    }
}
