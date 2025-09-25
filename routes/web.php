<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogbookController;

// Halaman Home
Route::get('/', function () {
    return view('home');
});

// Halaman About
Route::get('/about', function () {
    return view('about');
});

// Halaman Register (form register)
Route::get('/register', function () {
    return view('register');
})->name('register.form');

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

// ==================== LOGBOOK ====================

// Tampilkan form logbook
Route::get('/logbook', [LogbookController::class, 'create'])->name('logbook.create');

// Proses simpan logbook
Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store');
