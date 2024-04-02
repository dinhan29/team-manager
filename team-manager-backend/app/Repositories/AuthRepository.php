<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Traits\ResponseAPI;
use App\Interfaces\AuthRepositoryInterface;
use Laravel\Sanctum\HasApiTokens;

class AuthRepository implements AuthRepositoryInterface
{
    use ResponseAPI, HasApiTokens;

    /**
     * Login a user with the given credentials.
     *
     * @param array $credentials
     * @return JsonResponse
     */
    public function login(array $credentials): JsonResponse
    {
        try {
            $data = [];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;

                $data = [
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                    ],
                    'token' => $token
                ];

                return $this->okResponse($data, 'User logined successfully');
            } else {
                return $this->unauthorizedResponse($data, 'Invalid credentials');
            }
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse($e->getMessage(), 'An error occurred while logging in');
        }
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function register(array $data): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Email Unique
            $existingUser = User::where('email', $data['email'])->first();
            if ($existingUser) {
                throw new \Exception('Email already exists');
            }

            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->save();

            // Create token
            $token = $user->createToken('myapptoken')->plainTextToken;

            $data = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token
            ];

            DB::commit();

            return $this->okResponse($data, 'User registered successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->internalServerErrorResponse($e->getMessage(), 'An error occurred while regist');
        }
    }

    /**
     * Logout the currently authenticated user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
    
            return $this->okResponse([], 'User logged out successfully');
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse($e->getMessage(), 'An error occurred while logout');
        }
    }

    /**
     * Get user by email.
     *
     * @param string $email
     * @return JsonResponse
     */
    public function getUserByEmail(string $email): JsonResponse
    {
        try {
            $user = User::where('email', $email)->first();

            if (!$user) {
                return $this->okResponse([], 'Get user by email successfully');
            }

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
    
            return $this->okResponse($data, 'Get user by email successfully');
        } catch (\Exception $e) {
            return $this->internalServerErrorResponse($e->getMessage(), 'An error occurred while logout');
        }
    }
}
