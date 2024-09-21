<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    AuthController,
    EntryController,
    HomeController,
    UserController
};

Route::get('/', [HomeController::class, 'index']);

Route::post('/auth/signup', [AuthController::class, 'signUp']);
Route::post('/auth/signin', [AuthController::class, 'signIn']);

Route::middleware('auth.jwt')->group(function () {
    Route::prefix('entries/en')->controller(EntryController::class)->group(function () {
        Route::delete('/{word}/unfavorite', 'index');
        Route::post('/{word}/favorite', 'index');
        Route::get('/{word}', 'index');
        Route::get('/', 'index');
    });

    Route::prefix('user/me')->controller(UserController::class)->group(function () {
        Route::get('/favorites', 'index');
        Route::get('/history', 'index');
        Route::get('/', 'index');
    });
});