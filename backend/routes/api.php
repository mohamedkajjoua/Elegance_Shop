<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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
