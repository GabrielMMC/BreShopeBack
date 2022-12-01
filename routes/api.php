<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BreshopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\UserDataController;
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
    Route::post('register', [UserController::class, 'register']);
    Route::post('auth/login', [UserController::class, 'login']);
    Route::get('get_all_products', [ProductController::class, 'get_all_products']);
    Route::get('get_public_product/{id}', [ProductController::class, 'get_public_product']);
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('get_user/{id}', [UserController::class, 'get_user']);
    Route::patch('update_user', [UserController::class, 'update_user']);
    Route::delete('delete_user/{id}', [UserController::class, 'delete_user']);

    Route::get('get_user_data', [UserDataController::class, 'get_user_data']);
    Route::post('store_user_data', [UserDataController::class, 'store_user_data']);
    Route::post('update_user_data', [UserDataController::class, 'update_user_data']);

    Route::get('get_user_address', [UserAddressController::class, 'get_user_address']);
    Route::post('store_user_address', [UserAddressController::class, 'store_user_address']);
    Route::put('update_user_address', [UserAddressController::class, 'update_user_address']);

    Route::get('get_breshop', [BreshopController::class, 'get_breshop']);
    Route::post('store_breshop', [BreshopController::class, 'store_breshop']);

    Route::get('get_products', [ProductController::class, 'get_products']);
    Route::post('store_product', [ProductController::class, 'store_product']);
    Route::get('get_product/{id}', [ProductController::class, 'get_product']);
    Route::patch('update_product', [ProductController::class, 'update_product']);
    Route::delete('delete_product', [ProductController::class, 'delete_product']);

    Route::post('store_order', [OrderController::class, 'store_order']);
});
