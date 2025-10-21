<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// ==============================
// Import Controller
// ==============================

// Auth
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

// Publik
use App\Http\Controllers\ContactController;

// Logbook umum (resources/views/logbooks/)
use App\Http\Controllers\LogbookController;

// Admin
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Dosen
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Dosen\MilestoneController as DosenMilestoneController;

// Dosen Penguji
use App\Http\Controllers\DosenPenguji\MahasiswaController as DPMahasiswaController;
use App\Http\Controllers\DosenPenguji\PenilaianController;
use App\Http\Controllers\DosenPenguji\RubrikController;
use App\Http\Controllers\DosenPenguji\KelompokController as DPKelompokController;
use App\Http\Controllers\DosenPenguji\MatakuliahController as DPMatakuliahController;
use App\Http\Controllers\DosenPenguji\CPMKController;


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


/*
|--------------------------------------------------------------------------
| Mahasiswa
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard utama mahasiswa
    Route::view('/dashboard', 'mahasiswa.dashboard')->name('dashboard');

    // Halaman lain mahasiswa
    Route::get('/logbook', [LogbookController::class, 'mahasiswaIndex'])->name('logbook');
    Route::view('/kelompok', 'mahasiswa.kelompok')->name('kelompok');
    Route::view('/milestone', 'mahasiswa.milestone')->name('milestone');
    Route::view('/penilaian', 'mahasiswa.penilaian')->name('penilaian');
});

/*
|--------------------------------------------------------------------------
| Logbooks (global)
| View di: resources/views/logbooks/
|--------------------------------------------------------------------------
*/
Route::resource('logbooks', LogbookController::class);

/*
|--------------------------------------------------------------------------
| Dosen
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');
    Route::resource('/kelompok', DosenKelompokController::class)->names('kelompok');
    Route::view('/mahasiswa', 'dosen.mahasiswa')->name('mahasiswa');
    Route::resource('/milestone', DosenMilestoneController::class)->only(['index', 'edit', 'update']);
    Route::view('/logbook', 'dosen.logbook')->name('logbook');

    // Feedback
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/create', [AdminFeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [AdminFeedbackController::class, 'store'])->name('feedback.store');
    Route::post('/feedback/{feedback}/reply', [AdminFeedbackController::class, 'reply'])->name('feedback.reply');
    Route::patch('/feedback/{feedback}/status', [AdminFeedbackController::class, 'setStatus'])->name('feedback.setStatus');
    Route::delete('/feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');

    // Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/create', [NotificationController::class, 'create'])->name('notifikasi.create');
    Route::post('/notifikasi', [NotificationController::class, 'store'])->name('notifikasi.store');
    Route::get('/notifikasi/{notification}', [NotificationController::class, 'show'])->name('notifikasi.show');
    Route::get('/notifikasi/{notification}/edit', [NotificationController::class, 'edit'])->name('notifikasi.edit');
    Route::put('/notifikasi/{notification}', [NotificationController::class, 'update'])->name('notifikasi.update');
    Route::delete('/notifikasi/{notification}', [NotificationController::class, 'destroy'])->name('notifikasi.destroy');
    Route::post('/notifikasi/markAll', [NotificationController::class, 'markAllRead'])->name('notifikasi.markAll');
    Route::get('/notifikasi/{notification}/read', [NotificationController::class, 'markRead'])->name('notifikasi.read');
});

/*
|--------------------------------------------------------------------------
| Dosen Penguji
|--------------------------------------------------------------------------
*/
Route::prefix('dosenpenguji')->name('dosenpenguji.')->group(function () {
    Route::redirect('/', '/dosenpenguji/dashboard');
    Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');

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

    // Profil
    Route::view('/profile', 'dosenpenguji.profile')->name('profile');
    Route::view('/profile/edit', 'dosenpenguji.profile-edit')->name('profile.edit');
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

        return redirect()->route('dosenpenguji.profile')->with('success', 'Perubahan berhasil disimpan.');
    })->name('profile.update');
});