<?php
use App\Http\Controllers\FaqCategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SubscriberController;
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
        Route::match(['get','post'], '/common-delete-image/{id1}/{id2}/{id3}/{id4}/{id5}', [UserController::class, 'commonDeleteImage']);
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
        /* FAQs */
            /* faq category */
                Route::get('faq-category/list', [FaqCategoryController::class, 'list']);
                Route::match(['get', 'post'], 'faq-category/add', [FaqCategoryController::class, 'add']);
                Route::match(['get', 'post'], 'faq-category/edit/{id}', [FaqCategoryController::class, 'edit']);
                Route::get('faq-category/delete/{id}', [FaqCategoryController::class, 'delete']);
                Route::get('faq-category/change-status/{id}', [FaqCategoryController::class, 'change_status']);
            /* faq category */
            /* faq */
                Route::get('faq/list', [FaqController::class, 'list']);
                Route::match(['get', 'post'], 'faq/add', [FaqController::class, 'add']);
                Route::match(['get', 'post'], 'faq/edit/{id}', [FaqController::class, 'edit']);
                Route::get('faq/delete/{id}', [FaqController::class, 'delete']);
                Route::get('faq/change-status/{id}', [FaqController::class, 'change_status']);
            /* faq */
        /* FAQs */
        /* package */
            Route::get('package/list', [PackageController::class, 'list']);
            Route::match(['get', 'post'], 'package/add', [PackageController::class, 'add']);
            Route::match(['get', 'post'], 'package/edit/{id}', [PackageController::class, 'edit']);
            Route::get('package/delete/{id}', [PackageController::class, 'delete']);
            Route::get('package/change-status/{id}', [PackageController::class, 'change_status']);
        /* package */
        /* members */
            Route::get('member/list', [MemberController::class, 'list']);
            Route::match(['get', 'post'], 'member/add', [MemberController::class, 'add']);
            Route::match(['get', 'post'], 'member/edit/{id}', [MemberController::class, 'edit']);
            Route::get('member/delete/{id}', [MemberController::class, 'delete']);
            Route::get('member/change-status/{id}', [MemberController::class, 'change_status']);
            Route::get('member/membership-history/{id}', [MemberController::class, 'membershipHistory']);
            Route::get('member/all-membership-history', [MemberController::class, 'allMembershipHistory']);
            Route::get('member/all-member-membership-plan', [MemberController::class, 'allMemberMembershipPlan']);
        /* members */
        /* subscribers */
            Route::get('subscriber/list', [SubscriberController::class, 'list']);
            Route::get('subscriber/delete/{id}', [SubscriberController::class, 'delete']);
            Route::get('subscriber/change-status/{id}', [SubscriberController::class, 'change_status']);
        /* subscribers */
    });
/* Admin Panel */

Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    return "Cache cleared!";
});