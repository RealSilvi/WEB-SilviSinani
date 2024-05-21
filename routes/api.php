<?php

use App\Http\Controllers\Api\FollowersController;
use App\Http\Controllers\Api\FollowingController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('users.profiles', ProfileController::class)->except(['edit', 'create']);
    Route::resource('users.profiles.followers', FollowersController::class)->only(['index', 'store', 'destroy']);
    Route::resource('users.profiles.following', FollowingController::class)->only(['index', 'store', 'destroy']);
    Route::post('/users/{user}/profiles/{profile}/news/seeAll', [NewsController::class, 'seeAll'])->name('users.profiles.news.seeAll');
    Route::resource('users.profiles.news', NewsController::class)->only(['store']);
    Route::resource('users.profiles.post', PostController::class)->only(['index','show', 'store', 'destroy']);
});
