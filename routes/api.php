<?php

use App\Http\Controllers\API\V1\ArticleController;
use App\Http\Controllers\API\V1\Auth\LoginController;
use App\Http\Controllers\API\V1\Auth\PasswordResetController;
use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Http\Controllers\API\V1\PersonalizedFeedController;
use App\Http\Controllers\API\V1\ProfileController;
use App\Http\Controllers\API\V1\UserPreferenceController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', LoginController::class);
    Route::post('register', RegisterController::class);

    Route::post('password/forgot', [PasswordResetController::class, 'store']);
    Route::post('password/reset', [PasswordResetController::class, 'update'])->name('password.reset');
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/logout', [ProfileController::class, 'destroy']);

    Route::get('/preferences', [UserPreferenceController::class, 'index']);
    Route::post('/preferences', [UserPreferenceController::class, 'store']);

    Route::get('personalized-feed', PersonalizedFeedController::class);

});

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);

