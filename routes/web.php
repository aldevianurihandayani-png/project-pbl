<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

// ==============================
// Halaman Publik
// ==============================

// Halaman Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Halaman About
Route::get('/about', function () {
    return view('about');
})->name('about');

// Halaman Logbook
Route::get('/logbook', function () {
    return view('logbook');
})->name('logbook');

// ==============================
// Autentikasi
// ==============================

// Form Register
Route::get('/register', function () {
    return view('register');
})->name('register');

// Proses Register
Route::post('/register', [UserController::class, 'register'])->name('register.post');

// Form Login
Route::get('/login', [LoginController::class, 'index'])->name('login');

// Proses Login (ubah namanya supaya sesuai dengan login.blade.php)
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

// Logout
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ==============================
// Setelah Login
// ==============================

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');