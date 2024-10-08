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

Route::middleware('api_jwt')->group(function () {
    Route::prefix('entries/en')->controller(EntryController::class)->group(function () {
        Route::delete('/{word}/unfavorite', 'unfavorite');
        Route::post('/{word}/favorite', 'favorite');
        Route::get('/{word}', 'word');
        Route::get('/', 'index');
    });

    Route::prefix('user/me')->controller(UserController::class)->group(function () {
        Route::get('/favorites', 'favorites');
        Route::get('/history', 'history');
        Route::get('/', 'index');
    });
});