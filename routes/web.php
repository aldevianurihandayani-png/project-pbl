<?php

use App\Http\Controllers\LogbookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ContactController; 
use App\Models\Milestone;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\RubrikPenilaianController;
use App\Http\Controllers\DosenController;
use Illuminate\Http\Request;

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

// Dashboard Admin
Route::middleware(['auth','role:dosen_penguji'])->group(function () {
    Route::get('/dosenpenguji/dashboard', fn() => view('admins.dashboard'))->name('admins.dashboard');
});

// Dashboard Dosen Pembimbing
Route::middleware(['auth','role:dosen_pembimbing'])->group(function () {
    Route::get('/dosen/dashboard', fn() => view('dosen.dashboard'))->name('dosen.dashboard');
});

// Dashboard Mahasiswa
Route::middleware(['auth','role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', fn() => view('mahasiswa.dashboard'))->name('mahasiswa.dashboard');
});




// ==============================
// Setelah Login
// ==============================

// Proses Register
Route::post('/register', [UserController::class, 'store'])
    ->name('register.store');

    //dashboard dosne pembimbing
    //dashboard dosen pembimbing

Route::middleware(['auth', 'role.dosen_pembimbing'])->group(function () {
    Route::get('/dosen/dashboard', fn () => view('dosen.dashboard'))
        ->name('dosen.dashboard');
});
Route::get('dosen/dashboard', function () {
    return view('dosen.dashboard');   // <— folder.view yg benar
})->name('dosen.dashboard');

//kelompok dosen pembimbing
Route::get('dosen/kelompok', function () {
    return view('dosen.kelompok');   // <— folder.view yg benar
})->name('dosen.kelompok');

//mahasiswa pembimbing
Route::get('dosen/mahasiswa', function () {
    return view('dosen.mahasiswa');   // <— folder.view yg benar
})->name('dosen.mahasiswa');
//milestone pembimbing
Route::get('dosen/milestone', function () {
    return view('dosen.milestone');   // <— folder.view yg benar
})->name('dosen.milestone');
//logbook pembimbing
Route::get('dosen/logbook', function () {
    return view('dosen.logbook');   // <— folder.view yg benar
})->name('dosen.logbook');



//Dosen penguji 

Route::get('/dosenpenguji/dashboard', function () {
    return view('dosenpenguji.dashboard');
})->name('dosenpenguji.dashboard');
//mahasiswa penguji
Route::get('/dosenpenguji/mahasiswa', function () {
    return view('dosenpenguji.mahasiswa');
})->name('dosenpenguji.mahsiswa');
//kelompok penguji
Route::get('/dosenpenguji/kelompok', function () {
    return view('dosenpenguji.kelompok');
})->name('dosenpenguji.kelompok');
//penilaian penguji
Route::get('/dosenpenguji/penilaian', function () {
    return view('dosenpenguji.penilaian');
})->name('dosenpenguji.penilaian');
//rubrik penguji
Route::get('/dosenpenguji/rubrik', function () {
    return view('dosenpenguji.rubrik');
})->name('dosenpenguji.rubrik');
//matakuliah penguji
Route::get('/dosenpenguji/matakuliah', function () {
    return view('dosenpenguji.matakuliah');
})->name('dosenpenguji.matakuliah');


//Jaminan mutu
Route::get('/jaminanmutu/dashboard', function () {
    return view('jaminanmutu.dashboard');
})->name('jaminanmutu.dashboard');
//rubric jamtu 
Route::get('/jaminanmutu/rubrik', function () {
    return view('jaminanmutu.rubrik');
})->name('jaminanmutu.rubrik');
//penilaian jamtu
Route::get('/jaminanmutu/penilaian', function () {
    return view('jaminanmutu.penilaian');
})->name('jaminanmutu.penilaian');

//koordinator
Route::get('/koordinator/dashboard', function () {
    return view('koordinator.dashboard');
})->name('koordinator.dashboard');

//admins
Route::get('/admins/dashboard', function () {
    return view('admins.dashboard');
})->name('admins.dashboard');


//mahasiswa
Route::get('/mahasiswa/dashboard', function () {
    return view('mahasiswa.dashboard');
})->name('mahasiswa.dashboard');

//logbook mahasiswa 
Route::get('/mahasiswa/logbook', function () {
    return view('mahasiswa.logbook');
})->name('mahasiswa.logbook');