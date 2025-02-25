<?php

use App\Http\Controllers\CommentControlleur;
use App\Http\Controllers\PostControlleur;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserControlleur;
use App\Http\Controllers\CategoryControlleur;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes d'authentification JWT
Route::group(['prefix' => 'api'], function () {
    // Route unique pour le login JWT
    Route::post('auth/login', [AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::resource('users', UserControlleur::class);

Route::resource('posts', PostControlleur::class)->middleware(['auth']);
Route::get('show-post/{slug}', [PostControlleur::class, 'show_by_slug']);

Route::resource('comments', CommentControlleur::class)->middleware(['auth']);

Route::resource('category', CategoryControlleur::class);

require __DIR__ . '/auth.php';
