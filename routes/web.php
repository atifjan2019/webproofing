<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GooglePropertiesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScreenshotController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteGoogleController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', function () {
    return view('docs');
})->name('docs');

Route::view('/pricing', 'pricing')->name('pricing');
Route::post('/pricing/feedback', [App\Http\Controllers\PricingFeedbackController::class, 'store'])->name('pricing.feedback');

// Stripe Webhook (no CSRF, no auth)
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes (Disabled for Public Testing)
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Site routes
    Route::resource('sites', SiteController::class)->except(['edit', 'update']);

    // Screenshot routes
    Route::get('/sites/{site}/screenshots', [ScreenshotController::class, 'index'])->name('sites.screenshots');
    Route::post('/sites/{site}/screenshots', [ScreenshotController::class, 'capture'])->name('sites.screenshots.capture');
    Route::delete('/sites/{site}/screenshots/{screenshot}', [ScreenshotController::class, 'destroy'])->name('sites.screenshots.destroy');
    Route::get('/sites/{site}/screenshots/{screenshot}/image', [ScreenshotController::class, 'image'])->name('sites.screenshots.image');

    // Analytics routes
    Route::get('/sites/{site}/analytics', [App\Http\Controllers\SiteAnalyticsController::class, 'show'])->name('sites.analytics');

    // PageSpeed routes
    Route::get('/sites/{site}/pagespeed', [App\Http\Controllers\SitePageSpeedController::class, 'show'])->name('sites.pagespeed');
    Route::post('/sites/{site}/pagespeed/analyze', [App\Http\Controllers\SitePageSpeedController::class, 'analyze'])->name('sites.pagespeed.analyze');

    // =============================================
    // Billing Routes
    // =============================================
    Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');

    // =============================================
    // Google OAuth Routes (User-level)
    // =============================================
    Route::get('/google/connect', [GoogleController::class, 'connect'])->name('google.connect');
    Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
    Route::post('/google/disconnect', [GoogleController::class, 'disconnect'])->name('google.disconnect');
    Route::get('/google/status', [GoogleController::class, 'status'])->name('google.status');

    // =============================================
    // Google Properties API Routes
    // =============================================
    Route::get('/google/ga4/properties', [GooglePropertiesController::class, 'listGa4Properties'])->name('google.ga4.properties');
    Route::get('/google/gsc/properties', [GooglePropertiesController::class, 'listGscProperties'])->name('google.gsc.properties');

    // Site-specific Google property selection
    Route::post('/sites/{site}/ga4/select', [GooglePropertiesController::class, 'selectGa4Property'])->name('sites.ga4.select');
    Route::post('/sites/{site}/gsc/select', [GooglePropertiesController::class, 'selectGscProperty'])->name('sites.gsc.select');
    Route::get('/sites/{site}/metrics', [GooglePropertiesController::class, 'getSiteMetrics'])->name('sites.metrics');

    // =============================================
    // Site Google Configuration Page
    // =============================================
    Route::get('/sites/{site}/google', [SiteGoogleController::class, 'show'])->name('sites.google');
    Route::get('/sites/{site}/google/configure', [SiteGoogleController::class, 'configure'])->name('sites.google.configure');
    Route::post('/sites/{site}/google/configure', [SiteGoogleController::class, 'store'])->name('sites.google.store');
});

// =============================================
// Admin Routes (Super Admin Only)
// =============================================
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\Admin\AdminDashboardController::class, 'users'])->name('users');
    Route::get('/users/create', [App\Http\Controllers\Admin\AdminDashboardController::class, 'createUser'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\AdminDashboardController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\AdminDashboardController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/toggle-free-access', [App\Http\Controllers\Admin\AdminDashboardController::class, 'toggleFreeAccess'])->name('users.toggleFreeAccess');
    Route::post('/users/{user}/suspend', [App\Http\Controllers\Admin\AdminDashboardController::class, 'suspendUser'])->name('users.suspend');
    Route::post('/users/{user}/unsuspend', [App\Http\Controllers\Admin\AdminDashboardController::class, 'unsuspendUser'])->name('users.unsuspend');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminDashboardController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{user}/end-trial', [App\Http\Controllers\Admin\AdminDashboardController::class, 'endUserTrial'])->name('users.endTrial');
    Route::post('/users/{user}/update-services', [App\Http\Controllers\Admin\AdminDashboardController::class, 'updateUserServices'])->name('users.updateServices');
    Route::get('/sites', [App\Http\Controllers\Admin\AdminDashboardController::class, 'sites'])->name('sites');
    Route::post('/sites/{site}/pause', [App\Http\Controllers\Admin\AdminDashboardController::class, 'pauseSite'])->name('sites.pause');
    Route::post('/sites/{site}/resume', [App\Http\Controllers\Admin\AdminDashboardController::class, 'resumeSite'])->name('sites.resume');
});

require __DIR__ . '/auth.php';

