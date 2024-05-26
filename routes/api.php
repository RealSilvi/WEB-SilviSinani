<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CommentLikeController;
use App\Http\Controllers\Api\FollowersController;
use App\Http\Controllers\Api\FollowingController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostLikeController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('users.profiles', ProfileController::class)->except(['edit', 'create']);
    Route::resource('users.profiles.followers', FollowersController::class)->only(['index', 'store', 'destroy']);
    Route::resource('users.profiles.following', FollowingController::class)->only(['index', 'store', 'destroy']);
    Route::post('/users/{user}/profiles/{profile}/news/seeAll', [NewsController::class, 'seeAll'])->name('users.profiles.news.seeAll');
    Route::resource('users.profiles.news', NewsController::class)->only(['store']);
    Route::resource('users.profiles.posts', PostController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::delete('/users/{user}/profiles/{profile}/posts/{post}/likes/destroy', [PostLikeController::class, 'destroy'])->name('users.profiles.posts.likes.destroy');
    Route::resource('users.profiles.posts.likes', PostLikeController::class)->only(['index', 'store']);
    Route::resource('users.profiles.posts.comments', CommentController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::delete('/users/{user}/profiles/{profile}/posts/{post}/comments/{comment}/likes/destroy', [CommentLikeController::class, 'destroy'])->name('users.profiles.posts.comments.likes.destroy');
    Route::resource('users.profiles.posts.comments.likes', CommentLikeController::class)->only(['index', 'store']);
});
