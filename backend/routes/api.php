<?php

use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CartController;

use App\Http\Controllers\auth\AuthJWTController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\user\ProductSearchController;
use App\Http\Controllers\user\WishlistController;

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\admin\AdminOrderController;

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
    Route::get('users/{id}', [AuthJWTController::class, 'show']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthJWTController::class, 'me']);
        Route::post('refresh', [AuthJWTController::class, 'refresh']);
        Route::post('logout', [AuthJWTController::class, 'logout']);
        Route::put('users/{id}', [AuthJWTController::class, 'update']);
        Route::delete('users/{id}', [AuthJWTController::class, 'destroy']);

        Route::get('users', [AuthJWTController::class, 'index'])
            ->middleware('permission:view-users');
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
| cart Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Category Routes
|--------------------------------------------------------------------------
*/
Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);

    Route::middleware(['auth:api', 'role:admin,editor'])->group(function () {

        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
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
        Route::post('/', [BrandController::class, 'store']);
        Route::put('/{id}', [BrandController::class, 'update']);
        Route::get('/{id}', [BrandController::class, 'show']);
        Route::delete('/{id}', [BrandController::class, 'destroy']);
    });
});





Route::middleware(['auth:api', 'role:admin,editor'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
        Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']);

    });


/*|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------*/

Route::prefix('settings')->group(function () {

    Route::get('/', [SettingController::class, 'index']);
    Route::post('/', [SettingController::class, 'store']);
    Route::get('/{id}', [SettingController::class, 'show']);
    Route::put('/{id}', [SettingController::class, 'update']);
    Route::delete('/{id}', [SettingController::class, 'destroy']);



    Route::middleware(['auth:api', 'role:admin,editor'])->group(function () {
        //
    });
});
/*
|--------------------------------------------------------------------------
| home Routes
|--------------------------------------------------------------------------
*/
Route::prefix('home')->group(function () {

    Route::get('/getCategoryToHome', [HomeController::class, 'getCategoryToHome']);

    Route::middleware(['auth:api',])->group(function () {
        //
    });
});

/*|--------------------------------------------------------------------------

| Wishlist Routes

|--------------------------------------------------------------------------*/

Route::middleware('auth:api')->group(function () {

    Route::get('/wishlist', [WishlistController::class, 'index']);

    Route::post('/wishlist/toggle', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']);

});
/*
|--------------------------------------------------------------------------
| orders Routes
|--------------------------------------------------------------------------
*/



Route::middleware('auth:api')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);      // Order History
    Route::get('/orders/{id}', [OrderController::class, 'show']);  // Order Details
});

Route::get('/order-pdf/{id}', function($id) {
    $order = App\Models\Order::with('orderItems.productVariant.product', 'user', 'shippingAddress')->findOrFail($id);
    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('emails.invoice_pdf', compact('order'));
    return $pdf->stream('order-'.$order->id.'.pdf');
});


/*
|--------------------------------------------------------------------------
| adresses Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
});



