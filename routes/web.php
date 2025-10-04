<?php

use App\Http\Controllers\LogbookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ContactController; 

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

// Halaman Contact
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Halaman daftar logbook
Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index');

// Form tambah logbook
Route::get('/logbook/create', [LogbookController::class, 'create'])->name('logbook.create');

// Simpan logbook
Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store');

// ==============================
// Autentikasi
// ==============================

// Form Register
Route::get('/register', function () {
    return view('register');
})->name('register');

// Proses Register
Route::post('/register', [UserController::class, 'register'])->name('register.post');

//Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==============================
// Setelah Login
// ==============================

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('mahasiswa', MahasiswaController::class);
// sekarang /mahasiswa, /mahasiswa/create, /mahasiswa/{id}/edit, dst.

Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
Route::get('/mahasiswa/{nim}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
Route::put('/mahasiswa/{nim}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
Route::delete('/mahasiswa/{nim}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');



