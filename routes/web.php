<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

// System routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile']);
});

// Frontend routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Taxonomy routes
Route::get('/category/{slug}', [PostController::class, 'byCategory'])->name('category');
Route::get('/tag/{slug}', [PostController::class, 'byTag'])->name('tag');
Route::get('/author/{name}', [PostController::class, 'byAuthor'])->name('author');
Route::get('/archive/{year}/{month?}', [PostController::class, 'archive'])->name('archive');

// Comment routes
Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('comments.store');

// Custom post type routes - must be before the catch-all
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('post.show');

// Page routes (catch-all for pages) - must be last
Route::get('/{slug}', [PostController::class, 'showPage'])->name('page.show')
    ->where('slug', '[a-z0-9\-]+');
