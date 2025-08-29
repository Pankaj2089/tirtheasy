<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/',[PagesController::class, 'index'])->name('index');

Route::get('/sign-up',[PagesController::class, 'signUp'])->name('sign-up');
Route::get('/about-us',[PagesController::class, 'aboutUs'])->name('about-us');

Route::any('/user-register',[UsersController::class, 'userRegister'])->name('userRegister');
Route::get('/contact-us',[PagesController::class, 'contactUs'])->name('contactUs');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

require "api.php";
require "admin.php";
