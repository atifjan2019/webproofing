<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GooglePropertiesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScreenshotController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SiteGoogleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Site routes
    Route::resource('sites', SiteController::class)->except(['edit', 'update']);

    // Screenshot routes
    Route::get('/sites/{site}/screenshots', [ScreenshotController::class, 'index'])->name('sites.screenshots');
    Route::post('/sites/{site}/screenshots', [ScreenshotController::class, 'capture'])->name('sites.screenshots.capture');
    Route::delete('/sites/{site}/screenshots/{screenshot}', [ScreenshotController::class, 'destroy'])->name('sites.screenshots.destroy');
    Route::get('/sites/{site}/screenshots/{screenshot}/image', [ScreenshotController::class, 'image'])->name('sites.screenshots.image');

    // Analytics routes
    Route::get('/sites/{site}/analytics', [App\Http\Controllers\SiteAnalyticsController::class, 'show'])->name('sites.analytics');

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

require __DIR__ . '/auth.php';
