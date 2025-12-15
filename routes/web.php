<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Controllers umum
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LogbookController; // untuk MAHASISWA (global)
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TPK\TPKMahasiswaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DriveTestController;
use App\Http\Controllers\NotificationController;

// Koordinator
use App\Http\Controllers\Koordinator\PeringkatController;

// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\NotifikasiController;
// 🔽 TAMBAHAN UNTUK CRUD KELAS
use App\Http\Controllers\Admin\KelasController as AdminKelasController;

// Dosen (Pembimbing)
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Dosen\DosenMilestoneController as DosenMilestoneController;
use App\Http\Controllers\Dosen\DosenLogbookController;
use App\Http\Controllers\Dosen\DosenPembimbingController;


// Dosen Penguji
use App\Http\Controllers\DosenPenguji\MahasiswaController as DPMahasiswaController;
use App\Http\Controllers\DosenPenguji\PenilaianController;
use App\Http\Controllers\DosenPenguji\RubrikController;
use App\Http\Controllers\DosenPenguji\KelompokController as DPKelompokController;
use App\Http\Controllers\DosenPenguji\MatakuliahController as DPMatakuliahController;
use App\Http\Controllers\DosenPenguji\PenilaianItemController;

// MODEL quick-edit
use App\Http\Controllers\CpmkController;
use App\Models\Cpmk;
use App\Models\Rubrik;

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
// REGISTER
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// LOGIN/LOGOUT
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// LOGIN GOOGLE (SSO POLITALA)
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
/*
|--------------------------------------------------------------------------
| Verifikasi Email (NONAKTIF)
|--------------------------------------------------------------------------
*/
/*
// Notice "cek email"
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Link verifikasi yang diklik user → redirect sesuai role
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // set users.email_verified_at
    $user = Auth::user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admins.dashboard')->with('verified', true);
        case 'mahasiswa':
            return redirect()->route('mahasiswa.dashboard')->with('verified', true);
        case 'dosen_pembimbing':
            return redirect()->route('dosen.dashboard')->with('verified', true);
        case 'dosen_penguji':
            return redirect()->route('dosenpenguji.dashboard')->with('verified', true);
        case 'koordinator':
        case 'jaminan_mutu':
            return redirect()->route('admins.dashboard')->with('verified', true);
        default:
            return redirect()->route('home')->with('verified', true);
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

// Kirim ulang link verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
*/

/*
|--------------------------------------------------------------------------
/*
|--------------------------------------------------------------------------
| 🔔 NOTIFIKASI – ROUTE
|--------------------------------------------------------------------------
*/

// Halaman daftar notifikasi
Route::get('/notif', [NotificationController::class, 'index'])
    ->name('notif.index')
    ->middleware('auth');

// Tandai semua notifikasi sebagai sudah dibaca (POST)
Route::post('/notif/read-all', [NotificationController::class, 'readAll'])
    ->name('notif.readAll')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin (role: admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admins')
    ->name('admins.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('matakuliah', AdminMataKuliahController::class);
        Route::resource('mahasiswa', AdminMahasiswaController::class);
        Route::resource('dosen', DosenController::class);
        Route::resource('kelompok', AdminKelompokController::class)->only(['index', 'show']);
        Route::resource('logbook', AdminLogbookController::class)->only(['index']);

        Route::resource('kelas', AdminKelasController::class)->except(['show']);

        Route::resource('feedback', AdminFeedbackController::class);
        Route::patch(
            'feedback/{feedback}/status',
            [AdminFeedbackController::class, 'updateStatus']
        )->name('feedback.updateStatus');

        // =======================
        // ✅ NOTIFIKASI (PAKAI CONTROLLER YANG KAMU EDIT)
        // =======================
        Route::resource('notifikasi', NotifikasiController::class);

        Route::get(
            'notifikasi/{notification}/read',
            [NotifikasiController::class, 'markRead']
        )->name('notifikasi.read');

        Route::post(
            'notifikasi/markAll',
            [NotifikasiController::class, 'markAllRead']
        )->name('notifikasi.markAll');

        // ======================================================
        // ✅ TAMBAHAN: DETAIL + ICON ACTION (✔ 👁 ✏ 🗑)
        // ======================================================

        // 👁 detail notifikasi (klik 1 notif masuk ke detail)
        Route::get(
            'notifikasi/{notification}/detail',
            [NotifikasiController::class, 'detail']
        )->name('notifikasi.detail');

        // ↩ tandai belum dibaca (optional)
        Route::patch(
            'notifikasi/{notification}/unread',
            [NotifikasiController::class, 'markUnread']
        )->name('notifikasi.unread');

        // ✏ edit (kalau memang kamu pakai halaman edit)
        Route::get(
            'notifikasi/{notifikasi}/edit',
            [NotifikasiController::class, 'edit']
        )->name('notifikasi.edit');

        // 💾 update (submit edit)
        Route::put(
            'notifikasi/{notifikasi}',
            [NotifikasiController::class, 'update']
        )->name('notifikasi.update');

        // 🗑 delete
        Route::delete(
            'notifikasi/{notifikasi}',
            [NotifikasiController::class, 'destroy']
        )->name('notifikasi.destroy');
        // =======================

        Route::resource('profile', AdminProfileController::class);
        Route::resource('users', AdminUserController::class);

        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])
            ->name('users.approve');
        Route::post('/users/{user}/reject', [AdminUserController::class, 'reject'])
            ->name('users.reject');

        Route::get('/akun', [AdminUserController::class, 'index'])
            ->name('akun.index');
    });
/*

|--------------------------------------------------------------------------
| Mahasiswa (role: mahasiswa)
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')
    ->name('mahasiswa.')
    ->middleware(['auth', 'role:mahasiswa'])
    ->group(function () {
        Route::view('/dashboard', 'mahasiswa.dashboard')->name('dashboard');
        Route::get('/logbook', [LogbookController::class, 'mahasiswaIndex'])->name('logbook');
        Route::view('/kelompok', 'mahasiswa.kelompok')->name('kelompok');

        Route::resource(
            'milestone',
            \App\Http\Controllers\Mahasiswa\MahasiswaMilestoneController::class
        )->except(['show']);

        Route::view('/laporan-penilaian', 'mahasiswa.laporan-penilaian')->name('laporan-penilaian');
    });

/*
|--------------------------------------------------------------------------
| Dosen Pembimbing (role: dosen_pembimbing)
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')
    ->name('dosen.')
    ->middleware(['auth', 'role:dosen_pembimbing'])
    ->group(function () {

        Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');

        Route::resource('kelompok', DosenKelompokController::class)->names('kelompok');

        Route::view('/mahasiswa', 'dosen.mahasiswa')->name('mahasiswa');

        Route::resource('milestone', DosenMilestoneController::class)
            ->only(['index', 'edit', 'update']);

        // resource utama logbook (index, show, edit, dll)
        Route::resource('logbook', DosenLogbookController::class)->names('logbook');

        // toggle status logbook
        Route::patch('logbook/{logbook}/toggle-status', [DosenLogbookController::class, 'toggleStatus'])
            ->name('logbook.toggleStatus');

        // 🔥 route khusus untuk update nilai logbook
        Route::put('logbook/{logbook}/nilai', [DosenLogbookController::class, 'updateNilai'])
            ->name('logbook.nilai.update');

        // Halaman detail kelas (TI-3E, TI-3D, dst)
        Route::get('kelompok/kelas/{kelas}', [DosenKelompokController::class, 'kelas'])
            ->name('kelompok.kelas');
    });
// Halaman daftar mahasiswa bimbingan
Route::get('/dosen/mahasiswa', [DosenPembimbingController::class, 'index'])
    ->name('dosen.mahasiswa.index');

// Halaman detail mahasiswa
Route::get('/dosen/mahasiswa/{id}', [DosenPembimbingController::class, 'show'])
    ->name('dosen.mahasiswa.show');


/*
|--------------------------------------------------------------------------
| Dosen Penguji (role: dosen_penguji)
|--------------------------------------------------------------------------
*/
Route::prefix('dosenpenguji')
    ->name('dosenpenguji.')
    ->middleware(['auth', 'role:dosen_penguji'])
    ->group(function () {

        // Dashboard
        Route::redirect('/', '/dosenpenguji/dashboard');
        Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');

        // MAHASISWA
        Route::get('/mahasiswa', [DPMahasiswaController::class, 'index'])
            ->name('mahasiswa');

        Route::get('/mahasiswa/kelas/{kelas}', [DPMahasiswaController::class, 'showByKelas'])
            ->name('mahasiswa.kelas');

        // PENILAIAN (Gradebook + CRUD + Import/Export)
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
        Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::put('/penilaian/{penilaian}', [PenilaianController::class, 'update'])->name('penilaian.update');
        Route::delete('/penilaian/{penilaian}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

        Route::post('/penilaian/save', [PenilaianController::class, 'bulkSave'])->name('penilaian.bulkSave');
        Route::delete('/penilaian/grade/{nim}/{rubric_id}', [PenilaianController::class, 'deleteGrade'])
            ->name('penilaian.deleteGrade');

        // Export / Import
        Route::get('/penilaian/export/excel', [PenilaianController::class, 'exportExcelBaru'])
            ->name('penilaian.export.excel');
        Route::get('/penilaian/export/pdf', [PenilaianController::class, 'exportPdfBaru'])
            ->name('penilaian.export.pdf');
        Route::get('/penilaian/template', [PenilaianController::class, 'downloadTemplateBaru'])
            ->name('penilaian.template');
        Route::post('/penilaian/import', [PenilaianController::class, 'importExcelBaru'])
            ->name('penilaian.import');
        Route::get('/penilaian/export', [PenilaianController::class, 'exportExcelBaru'])
            ->name('penilaian.export');

        // RUBRIK
        Route::get('/rubrik', [RubrikController::class, 'index'])->name('rubrik.index');
        Route::post('/rubrik', [RubrikController::class, 'store'])->name('rubrik.store');
        Route::put('/rubrik/{rubrik}', [RubrikController::class, 'update'])->name('rubrik.update');
        Route::delete('/rubrik/{rubrik}', [RubrikController::class, 'destroy'])->name('rubrik.destroy');

        // Item Penilaian
        Route::prefix('penilaian-item')->name('penilaian.item.')->group(function () {
            Route::get('/create', [PenilaianItemController::class, 'create'])->name('create');
            Route::post('/', [PenilaianItemController::class, 'store'])->name('store');
            Route::get('/{item}/edit', [PenilaianItemController::class, 'edit'])->name('edit');
            Route::put('/{item}', [PenilaianItemController::class, 'update'])->name('update');
            Route::delete('/{item}', [PenilaianItemController::class, 'destroy'])->name('destroy');
        });

        /// Master data – Kelompok (dosen penguji)
        Route::get('/kelompok', [DPKelompokController::class, 'index'])
            ->name('kelompok');

        // detail satu kelompok (pakai controller::show yang sudah kamu buat)
        Route::get('/kelompok/{id}', [DPKelompokController::class, 'show'])
            ->name('kelompok.show');

        Route::get('/matakuliah', [DPMatakuliahController::class, 'index'])
            ->name('matakuliah');

        // CPMK
        Route::get('/cpmk', [CpmkController::class, 'index'])->name('cpmk.index');
        Route::post('/cpmk', [CpmkController::class, 'store'])->name('cpmk.store');
        Route::put('/cpmk/{kode_mk}/{kode}', [CpmkController::class, 'quickUpdate'])
            ->name('cpmk.quickUpdate');
        Route::delete('/cpmk/{cpmk}', [CpmkController::class, 'destroy'])
            ->name('cpmk.destroy');

        // PROFIL DOSEN PENGUJI (prefix /dosenpenguji)
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

            // update sekali saja
            $user->update($data);

            return redirect()
                ->route('dosenpenguji.profile')
                ->with('success', 'Perubahan berhasil disimpan.');
        })->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Koordinator PBL
|--------------------------------------------------------------------------
*/
Route::prefix('koordinator')
    ->name('koordinator.')
    ->middleware(['auth', 'role:koor_pbl'])
    ->group(function () {
        Route::view('/dashboard', 'koordinator.dashboard')->name('dashboard');

        // CRUD Kelola Peringkat (Koordinator)
        Route::resource('peringkat', PeringkatController::class);
    });

/*
|--------------------------------------------------------------------------
| Jaminan Mutu
|--------------------------------------------------------------------------
*/
Route::prefix('jaminanmutu')
    ->name('jaminanmutu.')
    ->middleware(['auth', 'role:jaminan_mutu'])
    ->group(function () {
        Route::view('/dashboard', 'jaminanmutu.dashboard')->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Profil Penguji Global (optional)
|--------------------------------------------------------------------------
*/
Route::view('/profile', 'dosenpenguji.profile')->name('profile');
Route::view('/profile/edit', 'dosenpenguji.profile-edit')->name('profile.edit');
Route::put('/profile', function (Request $request) {
    $user = Auth::user();

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

    // kalau mau benar-benar menyimpan perubahan global:
    // $user->update($data);

    return redirect()
        ->route('dosenpenguji.profile')
        ->with('success', 'Perubahan berhasil disimpan.');
})->name('profile.update');

/*
|--------------------------------------------------------------------------
| Resource Global
|--------------------------------------------------------------------------
*/
Route::resource('logbooks', LogbookController::class);

/*
|--------------------------------------------------------------------------
| Drive Test
|--------------------------------------------------------------------------
*/
Route::get('/drive-test', [DriveTestController::class, 'form']);
Route::post('/drive-test', [DriveTestController::class, 'upload'])->name('drive.test.upload');

/*
|--------------------------------------------------------------------------
| TPK MAHASISWA
|--------------------------------------------------------------------------
*/
Route::prefix('tpk/mahasiswa')->name('tpk.mahasiswa.')->group(function () {
    Route::get('/', [TPKMahasiswaController::class, 'index'])->name('index');
    Route::get('/create', [TPKMahasiswaController::class, 'create'])->name('create');
    Route::post('/store', [TPKMahasiswaController::class, 'store'])->name('store');
    Route::get('/calculate', [TPKMahasiswaController::class, 'calculate'])->name('calculate');
});

Route::resource('logbooks', LogbookController::class);

// 🔥 route kirim komentar dari mahasiswa
Route::post('logbooks/{logbook}/feedback', [LogbookController::class, 'storeFeedback'])
    ->name('logbooks.feedback.store');

use App\Http\Controllers\TPK\TPKController;

Route::get('/tpk', [TPKController::class, 'index'])->name('tpk.index');
