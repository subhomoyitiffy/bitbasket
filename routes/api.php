<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\ForgotpasswordController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/sign-up', [RegistrationController::class, 'registration']);
Route::post('/sign-up/otp-verification', [RegistrationController::class, 'register_verification']);

Route::post('/forgot-password', [ForgotpasswordController::class, 'forgot_password']);
Route::post('/forgot-password/otp-verification', [ForgotpasswordController::class, 'otp_verification']);
Route::post('/forgot-password/reset_password', [ForgotpasswordController::class, 'reset_password']);

Route::get('/state-list', [CommonController::class, 'getStates']);

Route::group([
    'middleware' => ['auth:api'],
    // 'prifix' => 'admin'
], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/index', [AuthController::class, 'getUser']);
});

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */
