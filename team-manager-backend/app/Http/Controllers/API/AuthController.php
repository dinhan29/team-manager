<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Interfaces\AuthRepositoryInterface;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * The authentication repository.
     *
     * @var AuthRepositoryInterface
     */
    private $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @param AuthRepositoryInterface $authRepository
     */
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Login a user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        return $this->authRepository->login($credentials);
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        return $this->authRepository->register($data);
    }

    /**
     * Logout the authenticated user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->authRepository->logout();
    }
}
