<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\updateUserRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthJWTController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $users = $this->authService->getAllUsers($perPage);
        return response()->json($users);
    }
    public function show($id)
    {
        $user = $this->authService->getById($id);


        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function register(RegisterRequest $request)
    {

        $response = $this->authService->register($request->validated());

        if (!$response['success']) {
            return response()->json($response, 400);
        }

        return response()->json($response, 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        try {

            $response = $this->authService->login($credentials['email'], $credentials['password']);
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout()
    {

        $response = $this->authService->logout();

        if (!$response['success']) {
            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function me()
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'user' => $user,
            'permissions' => $user->getAllPermissions(),
            'permissions_by_module' => $user->getPermissionsByModule()
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'success' => true,
            'user' => auth('api')->user(),
            'token' => auth('api')->refresh(), // دالة التجديد من المكتبة
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function destroy($id)
    {
        $user = $this->authService->delete($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    public function update(updateUserRequest $request, $id)
    {

        try {
            $user = $this->authService->update($id, $request->validated());
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'user not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'user update successfully',
                'data' => $user
            ]);
        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'message' => $err->getMessage()
            ], 400);
        }
    }
}
