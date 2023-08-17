<?php

use App\Http\Controllers\AccountingsController;
use App\Http\Controllers\Admin\ProfilesController as AdminProfilesController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('auth', [AuthController::class, 'auth'])->withoutMiddleware('auth:sanctum');
});

Route::group(['prefix' => 'password'], function () {
    Route::post('reset', [ResetPasswordController::class, 'reset'])->withoutMiddleware('auth');
    Route::post('forgot', [ForgotPasswordController::class, 'sendResetLinkEmail'])->withoutMiddleware('auth');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'home'], function () {
    Route::get('payment-info', [HomeController::class, 'getPaymentInfo']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'users'], function () {
    Route::get('/', [UsersController::class, 'getUsers']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'accountings'], function () {
    Route::get('/', [AccountingsController::class, 'getAccountings']);
    Route::get('/{id}', [AccountingsController::class, 'getAccounting'])->where('id', '[0-9]+');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'individual-accountings'], function () {
    Route::get('/', [IndividualAccountingsController::class, 'getIndividualAccountings']);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'role:master,manager,accountant,camp', 'prefix' => 'users'], function () {
        Route::get('/', [AdminUsersController::class, 'getUsers']);
        Route::get('/{id}', [AdminUsersController::class, 'getUser'])->where('id', '[0-9]+');
        Route::put('/{id}', [AdminUsersController::class, 'editUser'])->where('id', '[0-9]+');
    });
    Route::group(['middleware' => 'role:master,manager', 'prefix' => 'profiles'], function () {
        Route::put('/{id}', [AdminProfilesController::class, 'editProfile'])->where('id', '[0-9]+');
    });
});
