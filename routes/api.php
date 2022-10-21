<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BreshopController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['guest:api']], function () {
    Route::post('auth/login', [UserController::class, 'login']);
    Route::get('get_all_products', [ProductController::class, 'get_all_products']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('store_user', [UserController::class, 'store_user']);
    Route::get('get_users', [UserController::class, 'get_users']);
    Route::get('get_user/{id}', [UserController::class, 'get_user']);
    Route::patch('update_user', [UserController::class, 'update_user']);
    Route::delete('delete_user/{id}', [UserController::class, 'delete_user']);

    Route::get('get_breshop/{id}', [BreshopController::class, 'get_breshop']);
    Route::post('store_breshop', [BreshopController::class, 'store_breshop']);

    Route::get('get_products', [ProductController::class, 'get_products']);
    Route::post('store_product', [ProductController::class, 'store_product']);
    Route::get('get_product/{id}', [ProductController::class, 'get_product']);
    Route::patch('update_product', [ProductController::class, 'update_product']);
    Route::delete('delete_product', [ProductController::class, 'delete_product']);
});
