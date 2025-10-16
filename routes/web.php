<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LogbookController;
use App\Models\Logbook;
use App\Models\Milestone;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::view('/register', 'register')->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.post');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard per-ROLE (wajib login)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admins')->name('admins.')->group(function () {
    Route::view('/dashboard', 'admins.dashboard')->name('dashboard');
    Route::resource('matakuliah', AdminMataKuliahController::class);
    Route::resource('mahasiswa', AdminMahasiswaController::class);
    Route::resource('kelompok', AdminKelompokController::class)->only(['index']);
    Route::resource('logbook', AdminLogbookController::class)->only(['index']);
});



    Route::view('/dosen/dashboard', 'dosen.dashboard')->name('dosen.dashboard');
    Route::resource('/dosen/kelompok', DosenKelompokController::class)->names('dosen.kelompok');
    Route::view('/dosen/mahasiswa', 'dosen.mahasiswa')->name('dosen.mahasiswa');
    Route::view('/dosen/milestone', 'dosen.milestone')->name('dosen.milestone');
    Route::view('/dosen/logbook', 'dosen.logbook')->name('dosen.logbook');


    Route::view('/dosenpenguji/dashboard', 'dosenpenguji.dashboard')->name('dosenpenguji.dashboard');
    Route::view('/dosenpenguji/mahasiswa', 'dosenpenguji.mahasiswa')->name('dosenpenguji.mahasiswa');
    Route::view('/dosenpenguji/kelompok', 'dosenpenguji.kelompok')->name('dosenpenguji.kelompok');
    Route::view('/dosenpenguji/penilaian', 'dosenpenguji.penilaian')->name('dosenpenguji.penilaian');
    Route::view('/dosenpenguji/rubrik', 'dosenpenguji.rubrik')->name('dosenpenguji.rubrik');
    Route::view('/dosenpenguji/matakuliah', 'dosenpenguji.matakuliah')->name('dosenpenguji.matakuliah');

    Route::view('/jaminanmutu/dashboard', 'jaminanmutu.dashboard')->name('jaminanmutu.dashboard');
    Route::view('/jaminanmutu/rubrik', 'jaminanmutu.rubrik')->name('jaminanmutu.rubrik');
    Route::view('/jaminanmutu/penilaian', 'jaminanmutu.penilaian')->name('jaminanmutu.penilaian');

    Route::view('/koordinator/dashboard', 'koordinator.dashboard')->name('koordinator.dashboard');

    Route::view('/mahasiswa/dashboard', 'mahasiswa.dashboard')->name('kmahasiswa.dashboard');

