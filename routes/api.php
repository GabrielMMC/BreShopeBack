<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\BreshopController;
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
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('store_user', [UserController::class, 'store_user']);
    Route::get('get_users', [UserController::class, 'get_users']);
    Route::get('get_user/{id}', [UserController::class, 'get_user']);
    Route::patch('update_user', [UserController::class, 'update_user']);
    Route::delete('delete_user/{id}', [UserController::class, 'delete_user']);

    Route::get('get_breshop/{id}', [BreshopController::class, 'get_breshop']);
    Route::post('store_breshop', [BreshopController::class, 'store_breshop']);
});

Route::group([
    "prefix" => "policy-and-terms"
], function () {
    Route::get('/', [PolicyAndTermsController::class, 'get']);
    Route::post('/save', [PolicyAndTermsController::class, 'store']);
});
