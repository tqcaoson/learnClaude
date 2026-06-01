<?php

use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Notifications
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

// Admin Stats
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
    Route::get('/', fn() => redirect()->route('admin.stats.index'))->name('dashboard');
});
