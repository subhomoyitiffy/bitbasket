<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

/* Admin Panel */
    Route::match(['get', 'post'], '/', 'UserController@login');
    Route::match(['get','post'],'/forgot-password', 'UserController@forgotPassword');
    Route::match(['get','post'],'/validateOtp/{id}', 'UserController@validateOtp');
    Route::match(['get','post'],'/changePassword/{id}', 'UserController@changePassword');
    Route::group(['middleware' => ['user']], function(){
        Route::get('dashboard', 'UserController@dashboard');
        Route::get('logout', 'UserController@logout');
        Route::get('email-logs', 'UserController@emailLogs');
        Route::match(['get','post'],'/email-logs/details/{email}', 'UserController@emailLogsDetails');
        Route::get('login-logs', 'UserController@loginLogs');
        /* setting */
            Route::get('settings', 'UserController@settings');
            Route::post('profile-settings', 'UserController@profile_settings');
            Route::post('general-settings', 'UserController@general_settings');
            Route::post('change-password', 'UserController@change_password');
            Route::post('email-settings', 'UserController@email_settings');
            Route::post('email-template', 'UserController@email_template');
            Route::post('sms-settings', 'UserController@sms_settings');
            Route::post('footer-settings', 'UserController@footer_settings');
            Route::post('seo-settings', 'UserController@seo_settings');
            Route::post('payment-settings', 'UserController@payment_settings');
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