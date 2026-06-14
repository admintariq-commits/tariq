<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GraduateController;
use App\Http\Controllers\Admin\AlertController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\JobMarketController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Ministry\DashboardController as MinistryDashboardController;
use App\Http\Controllers\Ministry\AlertController as MinistryAlertController;
use App\Http\Controllers\Ministry\AnalyticsController as MinistryAnalyticsController;
use App\Http\Controllers\Graduate\ProfileController;
use App\Http\Controllers\Graduate\JobMatchController;
use App\Http\Controllers\Graduate\ApplicationController;
use App\Http\Controllers\Graduate\AcademicVerificationController;
use App\Http\Controllers\Graduate\CareerResourceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\NotificationController;

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Temporary public route to view sample graduates (for dev/testing)
Route::get('/sample-graduates', function () {
    $faker = \Faker\Factory::create();
    $grads = \App\Models\Graduate::with(['user','course','university'])->orderBy('id', 'desc')->take(100)->get();
    $grads = $grads->map(function ($grad) use ($faker) {
        $grad->sample_password = 'password123';
        $grad->sample_zip = $faker->numerify('####');
        return $grad;
    });
    return view('sample-graduates', compact('grads'));
});


// Public pages
Route::get('/terms', function () {
    return view('public.terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('public.privacy');
})->name('privacy');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/terms-sw', function () {
    return view('legal.terms_sw');
});

Route::get('/privacy-sw', function () {
    return view('legal.privacy_sw');
});

// Public geojson file endpoint
Route::get('/geojson/{filename}', function ($filename) {
    $path = public_path('geojson/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, ['Content-Type' => 'application/json']);
});

// Graduate registration
Route::get('/graduate/register', [RegisterController::class, 'showGraduateRegisterForm'])->name('graduate.register');
Route::post('/graduate/register', [RegisterController::class, 'registerGraduate'])->name('graduate.register.post');

// OTP endpoints for registration
Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

// Temporary dev-only test route to trigger OTP send without CSRF (only in non-production)
if (env('APP_ENV') !== 'production') {
    Route::get('/otp/send-test/{phone}', function ($phone) {
        request()->merge(['phone' => $phone]);
        return app(\App\Http\Controllers\Auth\OtpController::class)->send(request());
    })->name('otp.send-test');

    Route::get('/otp/clear/{phone}', function ($phone) {
        $phoneKey = preg_replace('/\s+/', '', $phone);
        \Illuminate\Support\Facades\Cache::forget('otp:send_count:'.$phoneKey);
        \Illuminate\Support\Facades\Cache::forget('otp:cooldown:'.$phoneKey);
        \Illuminate\Support\Facades\Cache::forget('otp:'.$phoneKey);
        \Illuminate\Support\Facades\Cache::forget('otp:attempts:'.$phoneKey);
        return response()->json(['status' => 'cleared']);
    })->name('otp.clear');

    Route::get('/artisan/config-clear', function () {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        return response()->json(['status' => 'config cleared']);
    })->name('artisan.config-clear');
}

// Academic verification (before registration or standalone)
Route::get('/verify-academic', [AcademicVerificationController::class, 'show'])->name('verify-academic');
Route::post('/verify-academic', [AcademicVerificationController::class, 'verify']);
Route::get('/verify-academic/status/{indexNumber}', [AcademicVerificationController::class, 'checkStatus'])->name('verify-academic.status');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    
    // Admin routes: only admin users can access admin pages.
    Route::middleware([
        \App\Http\Middleware\CheckRole::class . ':admin'
    ])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/heatmap', [DashboardController::class, 'heatmap'])->name('heatmap');
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
        Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
        Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');
        Route::post('/settings/test-mail', [DashboardController::class, 'testMail'])->name('settings.test-mail');
        Route::post('/settings/clear-cache', [DashboardController::class, 'clearCache'])->name('settings.clear-cache');
        Route::post('/settings/backup', [DashboardController::class, 'backup'])->name('settings.backup');
        Route::resource('graduates', GraduateController::class);
        Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
        Route::post('/alerts/{alert}/send', [AlertController::class, 'send'])->name('alerts.send');
        
        // Security routes
        Route::prefix('security')->name('security.')->group(function () {
            Route::get('/dashboard', [SecurityController::class, 'dashboard'])->name('dashboard');
            Route::get('/suspicious', [SecurityController::class, 'suspiciousUsers'])->name('suspicious');
            Route::get('/vpn', [SecurityController::class, 'vpnUsers'])->name('vpn');
            Route::get('/documents', [SecurityController::class, 'documentQueue'])->name('documents');
            Route::post('/documents/{graduate}/verify', [SecurityController::class, 'verifyDocument'])->name('documents.verify');
            Route::get('/users/{user}', [SecurityController::class, 'viewUser'])->name('user.view');
            Route::post('/users/{user}/toggle-suspicious', [SecurityController::class, 'toggleSuspicious'])->name('user.toggle-suspicious');
            Route::get('/export-log', [SecurityController::class, 'exportLog'])->name('export-log');
        });
        
        // Analytics routes
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
            Route::get('/employment-trends', [AnalyticsController::class, 'employmentTrends'])->name('employment-trends');
            Route::get('/salary-analysis', [AnalyticsController::class, 'salaryAnalysis'])->name('salary-analysis');
            Route::get('/skills-gap', [AnalyticsController::class, 'skillsGap'])->name('skills-gap');
            Route::get('/export/{report}', [AnalyticsController::class, 'exportReport'])->name('export');
        });
        
        // Job Market Intelligence routes
        Route::prefix('job-market')->name('job-market.')->group(function () {
            Route::get('/dashboard', [JobMarketController::class, 'dashboard'])->name('dashboard');
            Route::get('/', [JobMarketController::class, 'index'])->name('index');
            Route::get('/create', [JobMarketController::class, 'create'])->name('create');
            Route::post('/', [JobMarketController::class, 'store'])->name('store');
            Route::get('/{trend}', [JobMarketController::class, 'show'])->name('show');
            Route::post('/bulk-import', [JobMarketController::class, 'bulkImport'])->name('bulk-import');
        });
        
        // Universities routes
        Route::resource('universities', UniversityController::class);
        
        // Courses routes
        Route::resource('courses', CourseController::class);
    });
    
    // Graduate routes: only graduate users can manage their profile and applications.
    Route::middleware([
        \App\Http\Middleware\CheckRole::class . ':graduate'
    ])->prefix('graduate')->name('graduate.')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/job-matches', [JobMatchController::class, 'index'])->name('job-matches');
        Route::post('/apply/{job}', [ApplicationController::class, 'store'])->name('apply');
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications');
        
        // Career Resources routes
        Route::prefix('resources')->name('resources.')->group(function () {
            Route::get('/', [CareerResourceController::class, 'index'])->name('index');
            Route::get('/category/{category}', [CareerResourceController::class, 'category'])->name('category');
            Route::get('/search', [CareerResourceController::class, 'search'])->name('search');
            Route::get('/recommended', [CareerResourceController::class, 'recommended'])->name('recommended');
            Route::get('/{resource}', [CareerResourceController::class, 'show'])->name('show');
            Route::post('/{resource}/rate', [CareerResourceController::class, 'rate'])->name('rate');
        });
    });
    
    // Ministry routes: ministry officials have a separate analytics portal.
    Route::middleware([
        \App\Http\Middleware\CheckRole::class . ':ministry'
    ])->prefix('ministry')->name('ministry.')->group(function () {
        Route::get('/dashboard', [MinistryDashboardController::class, 'index'])->name('dashboard');
        Route::get('/alerts', [MinistryAlertController::class, 'index'])->name('alerts.index');
        Route::get('/analytics', [MinistryAnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
        Route::get('/analytics/employment-trends', [MinistryAnalyticsController::class, 'employmentTrends'])->name('analytics.employment-trends');
        Route::get('/analytics/salary-analysis', [MinistryAnalyticsController::class, 'salaryAnalysis'])->name('analytics.salary-analysis');
        Route::get('/analytics/skills-gap', [MinistryAnalyticsController::class, 'skillsGap'])->name('analytics.skills-gap');
        Route::get('/analytics/profile-completeness', [MinistryAnalyticsController::class, 'profileCompleteness'])->name('analytics.profile-completeness');
        Route::get('/export/graduates', [MinistryDashboardController::class, 'exportGraduates'])->name('export.graduates');
    });

    // Notification routes (available to all authenticated users)
    Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/delete-old', [NotificationController::class, 'deleteOld'])->name('delete-old');
    });
});
