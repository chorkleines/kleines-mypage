<?php

use App\Http\Controllers\AccountingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndividualAccountingsController;
use App\Http\Controllers\UsersController;
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

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'auth'], function () {
    Route::post('me', [AuthController::class, 'me']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'home'], function () {
    Route::get('payment_info', [HomeController::class, 'getPaymentInfo']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'users'], function () {
    Route::get('/', [UsersController::class, 'getUsers']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'accountings'], function () {
    Route::get('/', [AccountingsController::class, 'getAccountings']);
    Route::get('/{id}', [AccountingsController::class, 'getAccounting'])->where('id', '[0-9]+');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'individual_accountings'], function () {
    Route::get('/', [IndividualAccountingsController::class, 'getIndividualAccountings']);
});
