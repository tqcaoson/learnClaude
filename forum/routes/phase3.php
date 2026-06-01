<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\ReportController;

// Groups - Public
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::get('/groups/invitations/{token}/accept', [GroupMemberController::class, 'acceptInvitation'])->name('groups.accept-invitation');

// Groups - Auth
Route::middleware('auth')->group(function () {
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{slug}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::patch('/groups/{slug}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{slug}', [GroupController::class, 'destroy'])->name('groups.destroy');

    Route::get('/groups/{slug}/members', [GroupMemberController::class, 'index'])->name('groups.members');
    Route::post('/groups/{slug}/join', [GroupMemberController::class, 'join'])->name('groups.join');
    Route::delete('/groups/{slug}/leave', [GroupMemberController::class, 'leave'])->name('groups.leave');
    Route::post('/groups/{slug}/invite', [GroupMemberController::class, 'invite'])->name('groups.invite');
    Route::patch('/groups/{slug}/members/{userId}/role', [GroupMemberController::class, 'updateRole'])->name('groups.members.role');
    Route::delete('/groups/{slug}/members/{userId}', [GroupMemberController::class, 'kick'])->name('groups.members.kick');

    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/groups', [\App\Http\Controllers\My\GroupController::class, 'index'])->name('groups.index');
    });
});

// Admin - Moderation & Reports & Groups
Route::prefix('admin')->middleware(['auth', 'role:admin,moderator'])->name('admin.')->group(function () {
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{id}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');
    Route::patch('/reports/{id}/dismiss', [\App\Http\Controllers\Admin\ReportController::class, 'dismiss'])->name('reports.dismiss');

    Route::patch('/posts/{id}/hide', [\App\Http\Controllers\Admin\ModerationController::class, 'hidePost'])->name('posts.hide');
    Route::patch('/posts/{id}/show', [\App\Http\Controllers\Admin\ModerationController::class, 'showPost'])->name('posts.show');
    Route::patch('/posts/{id}/lock', [\App\Http\Controllers\Admin\ModerationController::class, 'lockPost'])->name('posts.lock');
    Route::patch('/posts/{id}/unlock', [\App\Http\Controllers\Admin\ModerationController::class, 'unlockPost'])->name('posts.unlock');
    Route::patch('/comments/{id}/hide', [\App\Http\Controllers\Admin\ModerationController::class, 'hideComment'])->name('comments.hide');
    Route::patch('/comments/{id}/show', [\App\Http\Controllers\Admin\ModerationController::class, 'showComment'])->name('comments.show');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/groups', [\App\Http\Controllers\Admin\GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/{slug}', [\App\Http\Controllers\Admin\GroupController::class, 'show'])->name('groups.show');
    Route::patch('/groups/{slug}/suspend', [\App\Http\Controllers\Admin\GroupController::class, 'suspend'])->name('groups.suspend');
    Route::patch('/groups/{slug}/activate', [\App\Http\Controllers\Admin\GroupController::class, 'activate'])->name('groups.activate');
    Route::delete('/groups/{slug}', [\App\Http\Controllers\Admin\GroupController::class, 'destroy'])->name('groups.destroy');
});

// Groups - Public detail (must be after all static /groups/* routes)
Route::get('/groups/{slug}', [GroupController::class, 'show'])->name('groups.show');
