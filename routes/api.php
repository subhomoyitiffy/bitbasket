<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\ForgotpasswordController;
use App\Http\Controllers\Api\UserSubscriptionController;
use App\Http\Controllers\Api\MemberUserController;
use App\Http\Controllers\Api\MemberTeacherController;
use App\Http\Controllers\Api\ContactrequestController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/sign-up', [RegistrationController::class, 'registration']);
Route::post('/sign-up/otp-verification', [RegistrationController::class, 'register_verification']);

Route::post('/forgot-password', [ForgotpasswordController::class, 'forgot_password']);
Route::post('/forgot-password/otp-verification', [ForgotpasswordController::class, 'otp_verification']);
Route::post('/forgot-password/reset_password', [ForgotpasswordController::class, 'reset_password']);

Route::get('/state-list', [CommonController::class, 'getStates']);
Route::get('/faq-list', [CommonController::class, 'getFaqs']);

Route::resource('/contact-request', ContactrequestController::class);
// Route::resource('/user-subscription', UserSubscriptionController::class);

Route::group([
    'middleware' => ['auth:api'],
    // 'prifix' => 'admin'
], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'getUser']);
    Route::post('/change-password', [AuthController::class, 'change_password']);
    Route::post('/update-profile', [AuthController::class, 'update_profile']);


    Route::get('/package-list/{status}', [CommonController::class, 'getPackages']);

    Route::get('/user-subscription/invoice', [UserSubscriptionController::class, 'invoice_list']);
    Route::resource('/user-subscription', UserSubscriptionController::class);

    Route::post('/member-user/change-status/{id}', [MemberUserController::class, 'change_status']);
    Route::resource('/member-user', MemberUserController::class);

    Route::post('/member-teacher/change-status/{id}', [MemberTeacherController::class, 'change_status']);
    Route::resource('/member-teacher', MemberTeacherController::class);
});

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */
