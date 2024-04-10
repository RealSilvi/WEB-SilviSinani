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

Route::middleware(['auth','verified','defaultProfile'])->group(function () {
    Route::get('/', function () {
        return view('pages/index');
    });
});

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/profile/new', function () {
        return view('pages/profile/new');
    });

    Route::get('/profile/edit', function () {
        return view('pages/profile/edit');
    });
});

