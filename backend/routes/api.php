<?php

use App\Http\Controllers\admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


use App\Http\Controllers\auth\AuthJWTController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here are prefixed with /api
| Example: http://127.0.0.1:8000/api/test
|--------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {

    Route::post('register', [AuthJWTController::class, 'register']);
    Route::post('login', [AuthJWTController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthJWTController::class, 'logout']);
        Route::post('refresh', [AuthJWTController::class, 'refresh']);
        Route::get('me', [AuthJWTController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/
Route::prefix('products')->group(function () {
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/filter', [ProductController::class, 'filter']);
    Route::get('/featured', [ProductController::class, 'featured']);
    Route::get('/best-sellers', [ProductController::class, 'bestSellers']);

    Route::get('/', [ProductController::class, 'index']);



    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/{id}/related', [ProductController::class, 'related']);


    Route::middleware('auth:api')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::post('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [ProductController::class, 'toggleStatus']);
    });
});
