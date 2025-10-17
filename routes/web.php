<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LogbookController;
use App\Models\Logbook;
use App\Models\Milestone;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\RubrikPenilaianController;
use App\Http\Controllers\DosenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Dosen\MilestoneController as DosenMilestoneController;




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
/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

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

    Route::resource('kelompok', AdminKelompokController::class)->only(['index', 'show']);
    Route::resource('logbook', AdminLogbookController::class)->only(['index']);
    Route::resource('feedback', AdminFeedbackController::class)->only(['index']);
    Route::resource('notifikasi', AdminNotifikasiController::class)->only(['index']);

});




Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');
    Route::resource('/kelompok', DosenKelompokController::class)->names('kelompok');
    Route::view('/mahasiswa', 'dosen.mahasiswa')->name('mahasiswa');
    Route::resource('/milestone', DosenMilestoneController::class)->only(['index', 'edit', 'update']);
    Route::view('/logbook', 'dosen.logbook')->name('logbook');
});


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
=======

Route::prefix('dosenpenguji')->name('dosenpenguji.')->group(function () {
    Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');
    Route::view('/mahasiswa', 'dosenpenguji.mahasiswa')->name('mahasiswa');
    Route::view('/kelompok', 'dosenpenguji.kelompok')->name('kelompok');
    Route::view('/penilaian', 'dosenpenguji.penilaian')->name('penilaian');
    Route::view('/rubrik', 'dosenpenguji.rubrik')->name('rubrik');
    Route::view('/matakuliah', 'dosenpenguji.matakuliah')->name('matakuliah');
});

Route::prefix('jaminanmutu')->name('jaminanmutu.')->group(function () {
    Route::view('/dashboard', 'jaminanmutu.dashboard')->name('dashboard');
    Route::view('/rubrik', 'jaminanmutu.rubrik')->name('rubrik');
    Route::view('/penilaian', 'jaminanmutu.penilaian')->name('penilaian');
});

Route::prefix('koordinator')->name('koordinator.')->group(function () {
    Route::view('/dashboard', 'koordinator.dashboard')->name('dashboard');
});

Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::view('/dashboard', 'mahasiswa.dashboard')->name('dashboard');
    Route::view('/kelompok', 'mahasiswa.kelompok')->name('kelompok');
    Route::view('/milestone', 'mahasiswa.milestone')->name('milestone');
    Route::view('/penilaian', 'mahasiswa.penilaian')->name('penilaian');
});
