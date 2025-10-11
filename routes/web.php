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

// ==============================
// Setelah Login
// ==============================

// Proses Register
Route::post('/register', [UserController::class, 'store'])
    ->name('register.store');

<<<<<<< HEAD
    //dashboard dosne pembimbing
=======
    //dashboard dosen pembimbing
>>>>>>> 9042b67c88cefd3ab08cebdcd6251739418194c3
Route::middleware(['auth', 'role.dosen_pembimbing'])->group(function () {
    Route::get('/dosen/dashboard', fn () => view('dosen.dashboard'))
        ->name('dosen.dashboard');
});
Route::get('dosen/dashboard', function () {
    return view('dosen.dashboard');   // <— folder.view yg benar
})->name('dosen.dashboard');
// === Route lain diarahkan ke dashboard juga sementara ===
// (opsional) placeholder menu lain — sesuaikan bila sudah ada halamannya
Route::get('/dosen/mahasiswa', fn() => view('placeholders.mahasiswa'))->name('dosen.mahasiswa.index');
Route::get('/dosen/kelompok',  fn() => view('placeholders.kelompok'))->name('dosen.kelompok.index');
Route::get('/dosen/milestone', fn() => view('placeholders.milestone'))->name('dosen.milestone.index');
Route::get('/dosen/logbook',   fn() => view('placeholders.logbook'))->name('dosen.logbook.index');

Route::get('/dosen/milestone', function () {
    // data dummy
    $milestones = [
        [
            'id' => 1,
            'minggu' => 1,
            'kegiatan' => 'Menghitung manual TPK',
            'deadline' => '2025-10-09',
            'status' => 'Pending',
        ],
        [
            'id' => 2,
            'minggu' => 2,
            'kegiatan' => 'Analisis data kelompok',
            'deadline' => '2025-10-16',
            'status' => 'Belum',
        ],
        [
            'id' => 3,
            'minggu' => 3,
            'kegiatan' => 'Penyusunan laporan TPK',
            'deadline' => '2025-10-23',
            'status' => 'Selesai',
        ],
    ];

    return view('dosen.milestone', compact('milestones'));
});

//kelompok dosen pembimbing
Route::get('/dosen/kelompok', [KelompokController::class, 'index'])->name('dosen.kelompok');
Route::middleware(['auth']) // tambahkan middleware role jika perlu: ,'role:mahasiswa' / 'role:dosen_pembimbing'
    ->group(function () {
        Route::get('/kelompok',           [KelompokController::class, 'index'])->name('kelompok.index');
        Route::post('/kelompok',          [KelompokController::class, 'store'])->name('kelompok.store');
        Route::delete('/kelompok/{id}',   [KelompokController::class, 'destroy'])->name('kelompok.destroy');
    });
    Route::get('/kelompok', [App\Http\Controllers\KelompokController::class, 'index'])
    ->name('kelompok.index');


//Dosen penguji 

Route::get('/dosenpenguji/dashboard', function () {
    return view('dosenpenguji.dashboard');
})->name('dosenpenguji.dashboard');

//Mahasiswa 
Route::get('/mahasiswa/dashboard', function () {
    return view('mahasiswa.dashboard');
})->name('mahasiswa.dashboard');


Route::resource('mahasiswa', MahasiswaController::class);
// minimal untuk index saja:
// Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');

//Jaminan mutu
Route::get('/jaminanmutu/dashboard', function () {
    return view('jaminanmutu.dashboard');
})->name('jaminanmutu.dashboard');


//koordinator
Route::get('/koordinator/dashboard', function () {
    return view('koordinator.dashboard');
})->name('koordinator.dashboard');

//admins
Route::get('/admins/dashboard', function () {
    return view('admins.dashboard');
})->name('admins.dashboard');


