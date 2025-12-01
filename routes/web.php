<?php

use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomePageController::class, 'index']);

// Basic authentication routes for public users
Route::middleware('guest')->group(function () {
    Route::get('/login', [HomePageController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HomePageController::class, 'login']);
    Route::get('/register', [HomePageController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [HomePageController::class, 'register']);
});

// Public routes for post creation (no auth required)
Route::get('/posts/create', [HomePageController::class, 'createPost'])->name('posts.create');
Route::post('/posts', [HomePageController::class, 'storePost'])->name('posts.store');

// User dashboard routes (optional auth for management)
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [HomePageController::class, 'dashboard'])->name('index');
    Route::get('/posts', [HomePageController::class, 'userPosts'])->name('posts');
    Route::get('/profile', [HomePageController::class, 'profile'])->name('profile');
    Route::put('/profile', [HomePageController::class, 'updateProfile'])->name('profile.update');

    // Verification routes
    Route::get('/verification', [HomePageController::class, 'verification'])->name('verification');
    Route::post('/verification', [HomePageController::class, 'submitVerification'])->name('verification.submit');
});

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('message', 'You have been logged out successfully.');
})->name('logout');
