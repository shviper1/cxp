<?php

use App\Http\Controllers\HomepageController;
use App\Livewire\CreatePost;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class, 'index']);

// Authentication routes for web users (redirect to Filament login)
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/posts/create', CreatePost::class)
    ->middleware('auth')
    ->name('posts.create');
