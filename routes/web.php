<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

/* Admin Panel */
    Route::match(['get', 'post'], '/', [UserController::class, 'login']);
    Route::match(['get','post'],'/forgot-password', [UserController::class, 'forgotPassword']);
    Route::match(['get','post'],'/validateOtp/{id}', [UserController::class, 'validateOtp']);
    Route::match(['get','post'],'/resendOtp/{id}', [UserController::class, 'resendOtp']);
    Route::match(['get','post'],'/resetPassword/{id}', [UserController::class, 'resetPassword']);
    Route::group(['middleware' => ['auth:user']], function(){
        Route::get('dashboard', [UserController::class, 'dashboard']);
        Route::get('logout', [UserController::class, 'logout']);
        Route::get('email-logs', [UserController::class, 'emailLogs']);
        Route::match(['get','post'],'/email-logs/details/{email}', [UserController::class, 'emailLogsDetails']);
        Route::get('login-logs', [UserController::class, 'loginLogs']);
        /* setting */
            Route::get('settings', [UserController::class, 'settings']);
            Route::post('profile-settings', [UserController::class, 'profile_settings']);
            Route::post('general-settings', [UserController::class, 'general_settings']);
            Route::post('change-password', [UserController::class, 'change_password']);
            Route::post('email-settings', [UserController::class, 'email_settings']);
            Route::post('email-template', [UserController::class, 'email_template']);
            Route::post('sms-settings', [UserController::class, 'sms_settings']);
            Route::post('footer-settings', [UserController::class, 'footer_settings']);
            Route::post('seo-settings', [UserController::class, 'seo_settings']);
            Route::post('payment-settings', [UserController::class, 'payment_settings']);
        /* setting */
    });
/* Admin Panel */

Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    return "Cache cleared!";
});