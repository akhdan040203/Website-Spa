<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\PublicPostController;
use App\Http\Controllers\SitemapController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'homepagePosts' => Post::published()
            ->latest('published_at')
            ->paginate(3, ['*'], 'artikel_page')
            ->withQueryString()
            ->fragment('artikel'),
    ]);
})->name('home');

Route::get('/artikel', [PublicPostController::class, 'index'])->name('posts.index');
Route::get('/artikel/{post}', [PublicPostController::class, 'show'])->name('posts.show');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard', [
            'stats' => ['total' => Post::count(), 'published' => Post::where('status', 'published')->count(), 'draft' => Post::where('status', 'draft')->count()],
            'latestPosts' => Post::latest()->limit(5)->get(),
        ]);
    })->name('dashboard');
    Route::resource('posts', PostController::class)->except('show');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
