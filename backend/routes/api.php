<?php

use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;


use App\Http\Controllers\auth\AuthJWTController;
use App\Http\Controllers\user\ProductSearchController;

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
    Route::get('/search', [ProductSearchController::class, 'search']);
    Route::get('/filter', [ProductSearchController::class, 'filter']);
    Route::get('/featured', [ProductSearchController::class, 'featured']);
    Route::get('/best-sellers', [ProductSearchController::class, 'bestSellers']);

    Route::get('/', [ProductController::class, 'index']);



    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/{id}/related', [ProductSearchController::class, 'related']);


    Route::middleware(['auth:api', 'role:admin,editor'])->group(function () {
        Route::post('/', [ProductController::class, 'store'])->middleware('permission:products.create');;
        Route::put('/{id}', [ProductController::class, 'update'])->middleware('permission:products.update');;
        Route::delete('/{id}', [ProductController::class, 'destroy'])->middleware('permission:products.destroy');;
        Route::patch('/{id}/toggle-status', [ProductController::class, 'toggleStatus']);
    });
});

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/
Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);

    Route::middleware(['auth:api', 'role:admin,editor'])->group(function () {
        //
    });
});

/*
|--------------------------------------------------------------------------
| brands Routes
|--------------------------------------------------------------------------
*/
Route::prefix('brands')->group(function () {

    Route::get('/', [BrandController::class, 'index']);

    Route::middleware(['auth:api', 'role:admin,editor'])->group(function () {
        //
    });
});
