<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        DB::beginTransaction();

        try {

            $user = User::create($data);
            if (!$token = auth('api')->login($user)) {
                throw new \Exception('Failed to generate token');
            }
            DB::commit();
            return [
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $this->formatUserResponse($user),
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ];
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error("Registration Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ];
        }
    }

    public function login($email, $password)
    {
        $credentials = ['email' => $email, 'password' => $password];
        $token = auth('api')->attempt($credentials);
        if (!$token) {
            throw ValidationException::withMessages([
                'email' => ['The entered data is incorrect']
            ]);
        }
        $user = auth('api')->user();

        return [
            'success' => true,
            'message' => 'login successful',
            'user' => $this->formatUserResponse($user),
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,

            'permissions' => $user->getAllPermissions(),
            'permissions' => $user->getPermissionsByModule()
        ];
    }

    public function logout(): array
    {
        try {

            auth('api')->logout();

            return [
                'success' => true,
                'message' => 'Logged out successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Logout failed: ' . $e->getMessage(),
            ];
        }
    }



    private function formatUserResponse(User $user): array
    {
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'avatar' => $user->avatar,
            'is_active' => $user->is_active,
            'last_login' => $user->last_login?->format('Y-m-d H:i:s'),
            'created_at' => $user->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
