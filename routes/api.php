<?php

use App\Http\Controllers\UserController;
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
    Route::post('add', [UserController::class, 'add']);
    Route::get('list', [UserController::class, 'list']);
    Route::get('edit/{id}', [UserController::class, 'edit']);
    Route::post('update', [UserController::class, 'update']);
    Route::delete('delete/{id}', [UserController::class, 'delete']);
});

Route::group([
    "prefix" => "policy-and-terms"
], function () {
    Route::get('/', [PolicyAndTermsController::class, 'get']);
    Route::post('/save', [PolicyAndTermsController::class, 'store']);
});
