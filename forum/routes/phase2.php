<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\My\CommentController as MyCommentController;
use App\Http\Controllers\My\PostController as MyPostController;
use App\Http\Controllers\ReactionController;
use Illuminate\Support\Facades\Route;

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/boards/{slug}', [CategoryController::class, 'show'])->name('boards.show');

// Reactions (AJAX)
Route::middleware('auth')->post('/reactions', [ReactionController::class, 'toggle'])->name('reactions.toggle');

// My content
Route::middleware('auth')->prefix('my')->name('my.')->group(function () {
    Route::get('/posts', [MyPostController::class, 'index'])->name('posts.index');
    Route::get('/comments', [MyCommentController::class, 'index'])->name('comments.index');
});

// Admin Categories & Boards
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{id}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/boards', [AdminCategoryController::class, 'storeboard'])->name('categories.boards.store');
    Route::patch('/boards/{id}', [AdminCategoryController::class, 'updateboard'])->name('boards.update');
    Route::delete('/boards/{id}', [AdminCategoryController::class, 'destroyboard'])->name('boards.destroy');
});

// Comment reply & edit (add to existing auth group concept)
Route::middleware('auth')->group(function () {
    Route::post('/comments/{id}/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->name('comments.reply');
    Route::get('/comments/{id}/edit', [\App\Http\Controllers\CommentController::class, 'edit'])->name('comments.edit');
    Route::patch('/comments/{id}', [\App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
});
