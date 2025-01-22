<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

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
