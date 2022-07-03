<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('throttle:10,1')->group(function () {

    Route::post('/auth/login', [LoginController::class, 'login']);
    Route::get('/products', [ProductController::class, 'index']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::apiResource('/products',ProductController::class)->except('index');
        Route::apiResource('/category',CategoryController::class);
    });

    Route::apiResource('/cart',CartController::class);
});
