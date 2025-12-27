<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
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
}
