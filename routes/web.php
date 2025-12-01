<?php

use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomePageController::class, 'index']);

// Authentication routes for web users (redirect to Filament login)
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Public routes for post creation (no auth required)
Route::get('/posts/create', [HomePageController::class, 'createPost'])->name('posts.create');
Route::post('/posts', [HomePageController::class, 'storePost'])->name('posts.store');

// User dashboard routes (optional auth for management)
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [HomePageController::class, 'dashboard'])->name('index');
    Route::get('/posts', [HomePageController::class, 'userPosts'])->name('posts');
    Route::get('/profile', [HomePageController::class, 'profile'])->name('profile');
    Route::put('/profile', [HomePageController::class, 'updateProfile'])->name('profile.update');
});
