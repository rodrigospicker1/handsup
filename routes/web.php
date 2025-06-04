<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckPermission;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;


Route::get('/login', function () { return view('login'); });
Route::get('/signup', function () { return view('signup'); })->name('signup');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/authUser', [AuthController::class, 'login'])->name('login');

Route::middleware(CheckPermission::class)->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('dashboard');
    Route::get('/event/create', function () { return view('event.create'); })->name('create_event');
    Route::post('/event/store', [EventController::class, 'store'])->name('create_event_post');
    Route::post('/event/subscribe', [EventController::class, 'subscribeEvent'])->name('subscribe_event');
});
