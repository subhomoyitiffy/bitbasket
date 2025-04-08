<?php
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\FaqCategoryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberUserController;
use App\Http\Controllers\MemberSubjectController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

/* Admin Panel */
    Route::match(['get'], '/', [UserController::class, 'login']);
    Route::post('signin', [UserController::class, 'login'])->name('signin');
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
        /* access & permission */
            /* modules */
                Route::get('module/list', [ModuleController::class, 'list']);
                Route::match(['get', 'post'], 'module/add', [ModuleController::class, 'add']);
                Route::match(['get', 'post'], 'module/edit/{id}', [ModuleController::class, 'edit']);
                Route::get('module/delete/{id}', [ModuleController::class, 'delete']);
                Route::get('module/change-status/{id}', [ModuleController::class, 'change_status']);
            /* modules */
            /* roles */
                Route::get('role/list', [RoleController::class, 'list']);
                Route::match(['get', 'post'], 'role/add', [RoleController::class, 'add']);
                Route::match(['get', 'post'], 'role/edit/{id}', [RoleController::class, 'edit']);
                Route::get('role/delete/{id}', [RoleController::class, 'delete']);
                Route::get('role/change-status/{id}', [RoleController::class, 'change_status']);
            /* roles */
            /* admin users */
                Route::get('admin-users/list', [AdminUserController::class, 'list']);
                Route::match(['get', 'post'], 'admin-users/add', [AdminUserController::class, 'add']);
                Route::match(['get', 'post'], 'admin-users/edit/{id}', [AdminUserController::class, 'edit']);
                Route::get('admin-users/delete/{id}', [AdminUserController::class, 'delete']);
                Route::get('admin-users/change-status/{id}', [AdminUserController::class, 'change_status']);
            /* admin users */
        /* access & permission */
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
            Route::get('member/membership-select-package/{id}', [MemberController::class, 'membershipSelectPackage']);
            Route::get('member/subscription-checkout/{id}/{id2}', [MemberController::class, 'subscriptionCheckout']);
            Route::match(['get', 'post'], 'member/subscription-payment', [MemberController::class, 'subscriptionPayment']);
        /* members */
        /* SMEs */
            Route::get('member-subject/list/{id}', [MemberSubjectController::class, 'list']);
            Route::match(['get', 'post'], 'member-subject/add', [MemberSubjectController::class, 'add']);
            Route::match(['get', 'post'], 'member-subject/edit/{id}', [MemberSubjectController::class, 'edit']);
            Route::get('member-subject/delete/{id}', [MemberSubjectController::class, 'delete']);
            Route::get('member-subject/change-status/{id}', [MemberSubjectController::class, 'change_status']);
        /* SMEs */
        /* SMEs */
            Route::get('member-user/list/{id}', [MemberUserController::class, 'list']);
            Route::match(['get', 'post'], 'member-user/add', [MemberUserController::class, 'add']);
            Route::match(['get', 'post'], 'member-user/edit/{id}', [MemberUserController::class, 'edit']);
            Route::get('member-user/delete/{id}', [MemberUserController::class, 'delete']);
            Route::get('member-user/change-status/{id}', [MemberUserController::class, 'change_status']);
        /* SMEs */
        /* subscribers */
            Route::get('subscriber/list', [SubscriberController::class, 'list']);
            Route::get('subscriber/delete/{id}', [SubscriberController::class, 'delete']);
            Route::get('subscriber/change-status/{id}', [SubscriberController::class, 'change_status']);
        /* subscribers */
        /* notifications */
            Route::get('notification/list', [NotificationController::class, 'list']);
            Route::match(['get', 'post'], 'notification/add', [NotificationController::class, 'add']);
            Route::match(['get', 'post'], 'notification/edit/{id}', [NotificationController::class, 'edit']);
            Route::get('notification/delete/{id}', [NotificationController::class, 'delete']);
            Route::get('notification/change-status/{id}', [NotificationController::class, 'change_status']);
            Route::get('notification/change-status-send/{id}', [NotificationController::class, 'change_status_send']);
        /* notifications */
        /* institutes */
            Route::get('institute/list', [InstituteController::class, 'list']);
            Route::match(['get', 'post'], 'institute/add', [InstituteController::class, 'add']);
            Route::match(['get', 'post'], 'institute/edit/{id}', [InstituteController::class, 'edit']);
            Route::get('institute/delete/{id}', [InstituteController::class, 'delete']);
            Route::get('institute/change-status/{id}', [InstituteController::class, 'change_status']);
        /* institutes */
        /* students */
            Route::get('student/list', [StudentController::class, 'list']);
            Route::match(['get', 'post'], 'student/add', [StudentController::class, 'add']);
            Route::match(['get', 'post'], 'student/edit/{id}', [StudentController::class, 'edit']);
            Route::get('student/delete/{id}', [StudentController::class, 'delete']);
            Route::get('student/change-status/{id}', [StudentController::class, 'change_status']);
        /* students */
    });
/* Admin Panel */

Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    return "Cache cleared!";
});

Route::get('/check-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token(),
    ]);
});