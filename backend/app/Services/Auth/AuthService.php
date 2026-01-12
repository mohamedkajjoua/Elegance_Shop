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
                'email' => ['Email or password is incorrect'],

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
            'permissions_by_module' => $user->getPermissionsByModule()
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
    public function getAllUsers($perPage = 10)
    {
        try {
            $users = User::paginate($perPage);
            return [
                'success' => true,
                'data' => $users

            ];
        } catch (\Exception $err) {
            return [
                'success' => false,
                'message' => 'Failed to fetch users' . $err->getMessage()
            ];
        }
    }
    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) return null;

        return $user->delete();
    }

    public function update($id, $data)
    {
        $user = User::findOrFail($id);


        if (isset($data['email']) && strtolower($data['email']) !== $user->email) {
            $existingEmail = User::where('email', strtolower($data['email']))->first();
            if ($existingEmail) {
                throw new \Exception('Email already exists');
            }
            $data['email'] = strtolower($data['email']);
        }


        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }


        if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {


            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }


            $path = $data['avatar']->store('avatars', 'public');


            $data['avatar'] = $path;
        }


        $user->update($data);

        return $user;
    }
    public function getById($id)
    {
        return  User::findOrFail($id);
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
