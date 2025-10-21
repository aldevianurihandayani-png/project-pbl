<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\NotificationController;

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
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
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

// Halaman daftar logbook
Route::resource('logbooks', LogbookController::class);
Route::get('/logbooks/index', function () {
    return redirect()->route('logbooks.index');
});


// ==============================
// Autentikasi
// ==============================

// Form Register
Route::get('/register', function () {
    return view('register');
})->name('register');

// Proses Register

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

Route::prefix('dosenpenguji')->name('dosenpenguji.')->group(function () {
    Route::get('/', fn() => redirect()->route('dosenpenguji.dashboard'));
    Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');
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


//mahasiswa
Route::get('/mahasiswa/dashboard', function () {
    return view('mahasiswa.dashboard');
})->name('mahasiswa.dashboard');

//logbook mahasiswa 
Route::get('/mahasiswa/logbook', [LogbookController::class, 'mahasiswaIndex'])->name('mahasiswa.logbook');

//kelompok mahasiswa 
Route::get('/mahasiswa/kelompok', function () {
    return view('mahasiswa.kelompok');
})->name('mahasiswa.kelompok');

//milestone mahasiswa
Route::get('/mahasiswa/milestone', function () {
    return view('mahasiswa.milestone');
})->name('mahasiswa.milestone');

// penilaaian mahasiswa 
Route::get('/mahasiswa/penilaian', function () {
    return view('mahasiswa.penilaian');
})->name('mahasiswa.penilaian');


Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');
    Route::resource('/kelompok', DosenKelompokController::class)->names('kelompok');
    Route::view('/mahasiswa', 'dosen.mahasiswa')->name('mahasiswa');
    Route::resource('/milestone', DosenMilestoneController::class)->only(['index', 'edit', 'update']);
    Route::view('/logbook', 'dosen.logbook')->name('logbook');

    Route::resource('kelompok', AdminKelompokController::class)->only(['index']);
    Route::resource('logbook', AdminLogbookController::class)->only(['index']);
    Route::get('/feedback', [App\Http\Controllers\Admin\FeedbackController::class,'index'])->name('feedback.index');
    Route::get('/feedback/create', [App\Http\Controllers\Admin\FeedbackController::class,'create'])->name('feedback.create');
    Route::post('/feedback', [App\Http\Controllers\Admin\FeedbackController::class,'store'])->name('feedback.store');
    Route::post('/feedback/{feedback}/reply', [App\Http\Controllers\Admin\FeedbackController::class,'reply'])->name('feedback.reply');
    Route::patch('/feedback/{feedback}/status', [App\Http\Controllers\Admin\FeedbackController::class,'setStatus'])->name('feedback.setStatus');
    Route::delete('/feedback/{feedback}', [App\Http\Controllers\Admin\FeedbackController::class,'destroy'])->name('feedback.destroy');

    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi', [NotificationController::class, 'store'])->name('notifikasi.store');
    Route::get('/notifikasi/create', [NotificationController::class, 'create'])->name('notifikasi.create');
    Route::get('/notifikasi/{notification}', [NotificationController::class, 'show'])->name('notifikasi.show');
    Route::get('/notifikasi/{notification}/edit', [NotificationController::class, 'edit'])->name('notifikasi.edit');
    Route::put('/notifikasi/{notification}', [NotificationController::class, 'update'])->name('notifikasi.update');
    Route::delete('/notifikasi/{notification}', [NotificationController::class, 'destroy'])->name('notifikasi.destroy');
    Route::post('/notifikasi/markAll', [NotificationController::class, 'markAllRead'])->name('notifikasi.markAll');
    Route::get('/notifikasi/{notification}/read', [NotificationController::class, 'markRead'])->name('notifikasi.read');

});
