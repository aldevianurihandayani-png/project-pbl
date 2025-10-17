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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

use App\Http\Controllers\DosenPenguji\MahasiswaController as DPMahasiswaController;
use App\Http\Controllers\DosenPenguji\PenilaianController;
use App\Http\Controllers\DosenPenguji\RubrikController;
use App\Http\Controllers\DosenPenguji\KelompokController as DPKelompokController;
use App\Http\Controllers\DosenPenguji\MatakuliahController as DPMatakuliahController;
use App\Http\Controllers\DosenPenguji\CPMKController;

// Dosen Penguji Routes
Route::middleware(['auth','role:dosen_penguji'])
  ->prefix('dosenpenguji')->name('dosenpenguji.')
  ->group(function () {
    Route::get('/dashboard', fn() => view('dosenpenguji.dashboard'))->name('dashboard');
    Route::get('/mahasiswa', [DPMahasiswaController::class, 'index'])->name('mahasiswa');
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::post('/penilaian/save', [PenilaianController::class, 'bulkSave'])->name('penilaian.bulkSave');
    Route::delete('/penilaian/grade/{nim}/{rubric_id}', [PenilaianController::class, 'deleteGrade'])->name('penilaian.deleteGrade');
    Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
    Route::post('/penilaian/import', [PenilaianController::class, 'import'])->name('penilaian.import');
    Route::get('/rubrik', [RubrikController::class, 'index'])->name('rubrik.index');
    Route::get('/kelompok', [DPKelompokController::class, 'index'])->name('kelompok');
    Route::get('/matakuliah', [DPMatakuliahController::class, 'index'])->name('matakuliah');
    Route::get('/cpmk', [CPMKController::class, 'index'])->name('cpmk.index');
// ==============================
// PROFIL DOSEN PENGUJI
// ==============================

// Tampil profil (sudah ada — biarkan jika sudah)
Route::get('/profile', fn () => view('dosenpenguji.profile'))->name('profile');

// Form edit profil
Route::get('/profile/edit', fn () => view('dosenpenguji.profile-edit'))->name('profile.edit');

// Simpan perubahan
Route::put('/profile', function (Request $request) {
    $user = auth()->user();

    $validated = $request->validate([
        'nama'     => 'nullable|string|max:255',
        'name'     => 'nullable|string|max:255',
        'email'    => 'required|email',
        'password' => 'nullable|min:6',
    ]);

    $data = [
        'nama'  => $validated['nama'] ?? ($validated['name'] ?? $user->nama),
        'email' => $validated['email'],
    ];

    if (!empty($validated['password'])) {
        $data['password'] = Hash::make($validated['password']);
    }

    $user->update($data);
    auth()->setUser($user->fresh());

    // ⬇️ Redirect ke halaman profil (bukan back)
    return redirect()->route('dosenpenguji.profile')
        ->with('success', 'Perubahan berhasil disimpan.');
})->name('profile.update');
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

//kelompok mahasiswa 
Route::get('/mahasiswa/kelompok', function () {
    return view('mahasiswa.kelompok');
})->name('mahasiswa.kelompok');

//milestone mahasiswa
Route::get('/mahasiswa/milestone', function () {
    return view('mahasiswa.milestone');
})->name('mahasiswa.milestone');

// penilaian mahasiswa 
Route::get('/mahasiswa/penilaian', function () {
    return view('mahasiswa.penilaian');
})->name('mahasiswa.penilaian');



Route::view('/register', 'register')->name('register'); // halaman form
Route::post('/register', [UserController::class, 'register'])->name('register.post'); // <— PASTIKAN 'register' di sini