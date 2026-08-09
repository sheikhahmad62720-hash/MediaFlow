<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PlatformController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    });

    // Public, rate-limited media inspection and download creation.
    Route::middleware('throttle:analyze')->group(function (): void {
        Route::post('media/analyze', [MediaController::class, 'analyze'])->name('media.analyze');
        Route::post('downloads', [DownloadController::class, 'store'])
            ->name('downloads.store')
            ->middleware('throttle:download');
    });

    // Authenticated user history.
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('downloads', [DownloadController::class, 'index'])->name('downloads.index');
    });

    // Track a single download. Guest downloads are addressable only by their
    // random UUID, so no authentication is required to poll their status.
    Route::get('downloads/{download}', [DownloadController::class, 'show'])->name('downloads.show');

    // File retrieval: open download, protected by a signed URL so anonymous
    // visitors who just created a download can stream their file.
    Route::get('downloads/{download}/file', [DownloadController::class, 'file'])
        ->name('downloads.file')
        ->middleware('signed');

    // Public read-only reference data.
    Route::get('platforms', [PlatformController::class, 'index'])->name('platforms.index');
    Route::get('platforms/{platform}', [PlatformController::class, 'show'])->name('platforms.show');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');

    // Public contact form submission.
    Route::post('contact', [ContactMessageController::class, 'store'])
        ->name('contact.store')
        ->middleware('throttle:download');

    // Administrator-only endpoints.
    Route::middleware(['auth:sanctum', 'admin'])->group(function (): void {
        Route::get('admin/dashboard', [DashboardController::class, 'stats'])->name('admin.dashboard');
        Route::get('admin/contact-messages', [ContactMessageController::class, 'index'])->name('admin.contact-messages.index');
        Route::put('admin/contact-messages/{contact_message}', [ContactMessageController::class, 'markRead'])->name('admin.contact-messages.read');
        Route::delete('admin/contact-messages/{contact_message}', [ContactMessageController::class, 'destroy'])->name('admin.contact-messages.destroy');
        Route::get('admin/downloads', [DownloadController::class, 'index'])->name('admin.downloads.index');
        Route::get('admin/storage', [DashboardController::class, 'storage'])->name('admin.storage');
        Route::get('admin/platforms', [PlatformController::class, 'index'])->name('admin.platforms.index');
        Route::get('admin/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
        Route::get('admin/settings', [SettingController::class, 'adminIndex'])->name('admin.settings.index');
        Route::put('admin/settings/{setting}', [SettingController::class, 'update'])->name('admin.settings.update');
    });
});
