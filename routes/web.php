<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

// Halaman Home
Route::get('/', function () {
    return view('home');
});

// Halaman About
Route::get('/about', function () {
    return view('about');
});

// Proses Register
Route::post('/register', [UserController::class, 'register'])->name('register');

// Halaman Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

// Logout (gunakan POST agar lebih aman)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Halaman Beranda (setelah login)
Route::get('/home', function () {
    return view('home');
})->name('home');
