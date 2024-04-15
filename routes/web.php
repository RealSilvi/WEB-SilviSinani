<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified', 'defaultProfile'])->group(function () {

    Route::view(
        '/',
        'pages/index'
    );

    Route::view(
        '/profile/edit',
        'pages/profile/edit'
    );

    Route::view(
        '/profile/new',
        'pages/profile/new'
    );

});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view(
        '/profile/create-first-profile',
        'pages/profile/create-first-profile'
    )->name('createFirstProfile');

});

