<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\auth\AuthJWTController;


use App\Http\Controllers\Api\Admin\ProductController;


Route::prefix('admin')->group(function () {
    Route::apiResource('products', ProductController::class);
});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here are prefixed with /api
| Example: http://127.0.0.1:8000/api/test
|--------------------------------------------------------------------------
*/

//
// 🔹 Public test routes
//
Route::get('/test', function () {
    return response()->json([
        'status' => true,
        'message' => 'Laravel API is working'
    ]);
});

Route::apiResource('users', UserController::class);
Route::apiResource('brands', BrandController::class);

Route::prefix('auth')->group(function () {

    Route::post('register', [AuthJWTController::class, 'register']);
    Route::post('login', [AuthJWTController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthJWTController::class, 'logout']);
        // Route::post('refresh', ...);
        // Route::get('me', ...);
    });
});
