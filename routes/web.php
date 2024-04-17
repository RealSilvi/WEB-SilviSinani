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

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view(
        '/profiles/create-first-profile',
        'pages/profiles/create-first-profile'
    )->name('createFirstProfile');

    Route::view(
        '/profiles/new',
        'pages/profiles/new'
    )->name('createNewProfile');

});

Route::middleware(['auth', 'verified', 'userHasProfile'])->group(function () {

    Route::redirect('/', '/dashboard')->name('home');

    Route::get('/dashboard/{profile:nickname?}', \App\Http\Controllers\DashboardController::class)->name('dashboard');
    Route::get('/profiles/{profile:nickname?}', \App\Http\Controllers\DashboardController::class);
//    Route::get('/settings/{profile:nickname?}', \App\Http\Controllers\DashboardController::class);
//    Route::get('/chats/{profile:nickname?}', \App\Http\Controllers\DashboardController::class);
});


