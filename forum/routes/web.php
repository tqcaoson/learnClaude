<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\RateLimitLogin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Guest Only
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('auth.register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('auth.register.post');

    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('auth.login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware(RateLimitLogin::class)
        ->name('auth.login.submit');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('auth.logout');

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/

Route::get('/email/verify-sent', [AuthController::class, 'verifyEmailSent'])
    ->name('auth.verify-email-sent');

Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])
    ->name('auth.verify-email');

/*
|--------------------------------------------------------------------------
| Posts - Public
|--------------------------------------------------------------------------
*/

Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

Route::get('/posts/{id}/view', [PostController::class, 'show'])
    ->name('posts.show');

/*
|--------------------------------------------------------------------------
| Authenticated Actions
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Posts
    |--------------------------------------------------------------------------
    */

    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts/store', [PostController::class, 'store'])
        ->name('posts.store');

    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');

    Route::patch('/posts/{id}/update', [PostController::class, 'update'])
        ->name('posts.update');

    Route::delete('/posts/{id}/delete', [PostController::class, 'delete'])
        ->name('posts.delete');

    /*
    |--------------------------------------------------------------------------
    | Comments
    |--------------------------------------------------------------------------
    */

    Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::delete('/comments/{id}', [CommentController::class, 'delete'])
        ->name('comments.delete');
});

/*
|--------------------------------------------------------------------------
| Additional Feature Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/phase1.php';
require __DIR__.'/phase2.php';
require __DIR__.'/phase3.php';
require __DIR__.'/phase4.php';