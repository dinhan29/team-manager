<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\JsonResponse;

interface AuthRepositoryInterface
{
    /**
     * Login a user.
     *
     * @param array $credentials
     * @return JsonResponse
     */
    public function login(array $credentials): JsonResponse;

    /**
     * Register a new user.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function register(array $data): JsonResponse;

    /**
     * Logout the currently authenticated user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse;

    /**
     * Get user by email.
     *
     * @param string $email
     * @return JsonResponse
     */
    public function getUserByEmail(string $email): JsonResponse;
}
