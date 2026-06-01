<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

// Profile
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
});

// Password Reset
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// Admin Users
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('users.role');
    Route::patch('/users/{id}/ban', [AdminUserController::class, 'ban'])->name('users.ban');
    Route::patch('/users/{id}/activate', [AdminUserController::class, 'activate'])->name('users.activate');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});
