<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SendVerificationTokenController;
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

Route::prefix('v1')->group( function () {


    Route::group(['middleware' => 'guest'], function() {
        Route::post('tokens', SendVerificationTokenController::class)->name('tokens');
        Route::post('register', RegistrationController::class)->name('register');
        Route::post('login', [AuthenticationController::class, 'login'])->name('login');
        Route::post('password/reset/token', ForgetPasswordController::class)->name('forget.password');
        Route::post('password/reset', ResetPasswordController::class)->name('reset.password');
    });

    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::post('logout', [AuthenticationController::class, 'destroy'])->name('logout');
        Route::get('user', UserController::class)->name('user.detail');
    });
});





