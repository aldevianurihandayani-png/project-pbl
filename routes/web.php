<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;


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
use App\Http\Controllers\Koordinator\KelompokController as KoordinatorKelompokController;
use App\Http\Controllers\Koordinator\MahasiswaController as KoordinatorMahasiswaController;


// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;

// 🔽 TAMBAHAN UNTUK CRUD KELAS
use App\Http\Controllers\Admin\KelasController as AdminKelasController;

// Dosen (Pembimbing)
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Dosen\DosenMilestoneController as DosenMilestoneController;
use App\Http\Controllers\Dosen\DosenLogbookController;
use App\Http\Controllers\Dosen\DosenPembimbingController;
use App\Http\Controllers\Dosen\ProfileController;

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
use App\Http\Controllers\mahasiswa\MahasiswaProfileController;
use App\Http\Controllers\koordinator\KoordinatorProfileController;

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
*
/*
|--------------------------------------------------------------------------
| Verifikasi Email (AKTIF)
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
| 🔔 NOTIFIKASI – ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/notif', [NotificationController::class, 'index'])
    ->name('notif.index')
    ->middleware('auth');

Route::post('/notif/read-all', [NotificationController::class, 'readAll'])
    ->name('notif.readAll')
    ->middleware('auth');


/*
|--------------------------------------------------------------------------
| Verifikasi Email
|--------------------------------------------------------------------------
*/

// ✅ Halaman "Cek Email"
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

// ✅ Link verifikasi yang diklik user (TIDAK PERLU LOGIN)
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {

    // pastikan link signed & belum expired
    if (! $request->hasValidSignature()) {
        abort(403, 'Link verifikasi tidak valid atau sudah kadaluarsa.');
    }

    $user = User::findOrFail($id);

    // pastikan hash sesuai email user
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Hash verifikasi tidak cocok.');
    }

    // tandai email sebagai terverifikasi
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    // setelah verifikasi → ke login
    return redirect()->route('login')
        ->with('success', 'Email berhasil diverifikasi. Silakan login.');
})->name('verification.verify');

// (opsional) Kirim ulang link verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->validate(['email' => ['required', 'email']]);

    $user = User::where('email', $request->email)->firstOrFail();
    $user->sendEmailVerificationNotification();

    return back()->with('success', 'Link verifikasi berhasil dikirim ulang.');
})->name('verification.send');



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

        // NOTIFIKASI
        Route::resource('notifikasi', NotifikasiController::class);

        Route::get('notifikasi/{notification}/read', [NotifikasiController::class, 'markRead'])
            ->name('notifikasi.read');

        Route::post('notifikasi/markAll', [NotifikasiController::class, 'markAllRead'])
            ->name('notifikasi.markAll');

        Route::get('notifikasi/{notification}/detail', [NotifikasiController::class, 'detail'])
            ->name('notifikasi.detail');

        Route::patch('notifikasi/{notification}/unread', [NotifikasiController::class, 'markUnread'])
            ->name('notifikasi.unread');

        Route::get('notifikasi/{notifikasi}/edit', [NotifikasiController::class, 'edit'])
            ->name('notifikasi.edit');

        Route::put('notifikasi/{notifikasi}', [NotifikasiController::class, 'update'])
            ->name('notifikasi.update');

        Route::delete('notifikasi/{notifikasi}', [NotifikasiController::class, 'destroy'])
            ->name('notifikasi.destroy');

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

        // ✅ PROFIL MAHASISWA (HARUS DI DALAM GROUP)
        Route::get('/profile', [MahasiswaProfileController::class, 'show'])->name('profile');
        Route::get('/profile/edit', [MahasiswaProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [MahasiswaProfileController::class, 'update'])->name('profile.update');

        // NOTIFIKASI
        Route::get('notifikasi', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifikasi.index');
        Route::get('notifikasi/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifikasi.read');
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

        // ================== DASHBOARD ==================
        Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');

        // ================== PROFIL DOSEN ==================
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // ================== KELOMPOK ==================
        Route::resource('kelompok', DosenKelompokController::class)->names('kelompok');
        Route::get('kelompok/kelas/{kelas}', [DosenKelompokController::class, 'kelas'])
            ->name('kelompok.kelas');

        // ================== MAHASISWA ==================
Route::get('/mahasiswa', [DosenPembimbingController::class, 'index'])
    ->name('mahasiswa.index');

// 👉 DETAIL MAHASISWA PER KELAS (READ ONLY)
Route::get('/mahasiswa/kelas/{kelas}', [DosenPembimbingController::class, 'kelas'])
    ->name('mahasiswa.kelas');

Route::get('/mahasiswa/{id}', [DosenPembimbingController::class, 'show'])
    ->name('mahasiswa.show');
    


        // ================== MILESTONE ==================
        Route::resource('milestone', DosenMilestoneController::class)
            ->only(['index', 'show', 'edit', 'update'])
            ->names('milestone');

        Route::patch('milestone/{milestone}/approve', [DosenMilestoneController::class, 'approve'])
            ->name('milestone.approve');

        Route::patch('milestone/{milestone}/reject', [DosenMilestoneController::class, 'reject'])
            ->name('milestone.reject');

        // ================== LOGBOOK ==================
        Route::resource('logbook', DosenLogbookController::class)->names('logbook');

        Route::patch('logbook/{logbook}/toggle-status', [DosenLogbookController::class, 'toggleStatus'])
            ->name('logbook.toggleStatus');

        Route::put('logbook/{logbook}/nilai', [DosenLogbookController::class, 'updateNilai'])
            ->name('logbook.nilai.update');
    });





/*
|--------------------------------------------------------------------------
| Dosen Penguji (role: dosen_penguji)
|--------------------------------------------------------------------------
*/
Route::prefix('dosenpenguji')
    ->name('dosenpenguji.')
    ->middleware(['auth', 'role:dosen_penguji'])
    ->group(function () {

        Route::redirect('/', '/dosenpenguji/dashboard');
        Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');

        Route::get('/mahasiswa', [DPMahasiswaController::class, 'index'])
            ->name('mahasiswa');

        Route::get('/mahasiswa/kelas/{kelas}', [DPMahasiswaController::class, 'showByKelas'])
            ->name('mahasiswa.kelas');

        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
        Route::post('/penilaian', [PenilaianController::class, 'store'])->name('penilaian.store');
        Route::put('/penilaian/{penilaian}', [PenilaianController::class, 'update'])->name('penilaian.update');
        Route::delete('/penilaian/{penilaian}', [PenilaianController::class, 'destroy'])->name('penilaian.destroy');

        Route::post('/penilaian/save', [PenilaianController::class, 'bulkSave'])->name('penilaian.bulkSave');
        Route::delete('/penilaian/grade/{nim}/{rubric_id}', [PenilaianController::class, 'deleteGrade'])
            ->name('penilaian.deleteGrade');

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

        Route::get('/rubrik', [RubrikController::class, 'index'])->name('rubrik.index');
        Route::post('/rubrik', [RubrikController::class, 'store'])->name('rubrik.store');
        Route::put('/rubrik/{rubrik}', [RubrikController::class, 'update'])->name('rubrik.update');
        Route::delete('/rubrik/{rubrik}', [RubrikController::class, 'destroy'])->name('rubrik.destroy');

        Route::prefix('penilaian-item')->name('penilaian.item.')->group(function () {
            Route::get('/create', [PenilaianItemController::class, 'create'])->name('create');
            Route::post('/', [PenilaianItemController::class, 'store'])->name('store');
            Route::get('/{item}/edit', [PenilaianItemController::class, 'edit'])->name('edit');
            Route::put('/{item}', [PenilaianItemController::class, 'update'])->name('update');
            Route::delete('/{item}', [PenilaianItemController::class, 'destroy'])->name('destroy');
        });

        Route::get('/kelompok', [DPKelompokController::class, 'index'])
            ->name('kelompok');

        Route::get('/kelompok/{id}', [DPKelompokController::class, 'show'])
            ->name('kelompok.show');

        Route::get('/matakuliah', [DPMatakuliahController::class, 'index'])
            ->name('matakuliah');

        Route::get('/cpmk', [CpmkController::class, 'index'])->name('cpmk.index');
        Route::post('/cpmk', [CpmkController::class, 'store'])->name('cpmk.store');
        Route::put('/cpmk/{kode_mk}/{kode}', [CpmkController::class, 'quickUpdate'])
            ->name('cpmk.quickUpdate');
        Route::delete('/cpmk/{cpmk}', [CpmkController::class, 'destroy'])
            ->name('cpmk.destroy');

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

            return redirect()
                ->route('dosenpenguji.profile')
                ->with('success', 'Perubahan berhasil disimpan.');
        })->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| Koordinator PBL (role: koor_pbl)
|--------------------------------------------------------------------------
*/


Route::prefix('koordinator')
    ->name('koordinator.')
    ->middleware(['auth', 'verified', 'role:koor_pbl'])
    ->group(function () {

       // ====== ATUR BOBOT ======
Route::get('peringkat/bobot', [PeringkatController::class, 'bobot'])
    ->name('peringkat.bobot');

Route::post('peringkat/bobot', [PeringkatController::class, 'storeBobot'])
    ->name('peringkat.bobot.store');


        // ================= DASHBOARD =================
        Route::view('/dashboard', 'koordinator.dashboard')->name('dashboard');

        // ================= PERINGKAT (INDEX) =================
        Route::get('peringkat', [PeringkatController::class, 'index'])
            ->name('peringkat.index');

        // ================= CREATE FORM =================
        Route::get('peringkat/mahasiswa/create', [PeringkatController::class, 'createMahasiswa'])
            ->name('peringkat.createMahasiswa');

        Route::get('peringkat/kelompok/create', [PeringkatController::class, 'createKelompok'])
            ->name('peringkat.createKelompok');

        // ================= STORE =================
        Route::post('peringkat/mahasiswa', [PeringkatController::class, 'storeMahasiswa'])
            ->name('peringkat.storeMahasiswa');

        Route::post('peringkat/kelompok', [PeringkatController::class, 'storeKelompok'])
            ->name('peringkat.storeKelompok');

        // ================= HITUNG ULANG =================
        Route::get('peringkat/calculate', [PeringkatController::class, 'calculate'])
            ->name('peringkat.calculate');

        // ================= EDIT (GENERIC – 1 VIEW) =================
        // dipakai di index.blade:
        // route('koordinator.peringkat.edit', ['type'=>'kelompok|mahasiswa','id'=>...])
        Route::get('peringkat/{type}/{id}/edit', [PeringkatController::class, 'edit'])
            ->whereIn('type', ['mahasiswa', 'kelompok'])
            ->whereNumber('id')
            ->name('peringkat.edit');

        // ================= UPDATE (GENERIC – FIX ERROR) =================
        // 🔥 INI YANG WAJIB ADA, kalau ini ga ada → edit ga bisa submit
        Route::put('peringkat/{type}/{id}', [PeringkatController::class, 'update'])
            ->whereIn('type', ['mahasiswa', 'kelompok'])
            ->whereNumber('id')
            ->name('peringkat.update');

        // ================= DELETE TPK (HARD DELETE) =================
        Route::post('peringkat/tpk/destroy', [PeringkatController::class, 'destroyTpk'])
            ->name('peringkat.destroyTpk');

        // ================= READ ONLY LAINNYA =================
        Route::get('/kelompok', [KoordinatorKelompokController::class, 'index'])
            ->name('kelompok');

        Route::get('/kelompok/{kelompok}', [KoordinatorKelompokController::class, 'show'])
            ->name('kelompok.detail');

        Route::get('/mahasiswa', [KoordinatorMahasiswaController::class, 'index'])
            ->name('mahasiswa.index');

        Route::get('/mahasiswa/{mahasiswa}', [KoordinatorMahasiswaController::class, 'show'])
            ->name('mahasiswa.show');
    });

    Route::get('Koordinator/profile', [KoordinatorProfileController::class, 'show'])->name('profile');
        Route::get('koordinator/profile/edit', [KoordinatorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('koordinator/profile', [KoordinatorProfileController::class, 'update'])->name('profile.update');


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

// 🔥 route kirim komentar dari mahasiswa
Route::post('logbooks/{logbook}/feedback', [LogbookController::class, 'storeFeedback'])
    ->name('logbooks.feedback.store');

use App\Http\Controllers\TPK\TPKKelompokController;
use App\Http\Controllers\TPK\TPKController;

Route::get('/tpk', [TPKController::class, 'index'])->name('tpk.index');

Route::prefix('tpk')->name('tpk.')->group(function () {

    // ===== KELOMPOK =====
    Route::prefix('kelompok')->name('kelompok.')->group(function () {
        Route::get('/', [TPKKelompokController::class, 'index'])->name('index');
        Route::get('/create', [TPKKelompokController::class, 'create'])->name('create');
        Route::post('/store', [TPKKelompokController::class, 'store'])->name('store');
        Route::get('/calculate', [TPKKelompokController::class, 'calculate'])->name('calculate');
    });

    // ===== MAHASISWA =====
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/', [TPKMahasiswaController::class, 'index'])->name('index');
        Route::get('/create', [TPKMahasiswaController::class, 'create'])->name('create');
        Route::post('/store', [TPKMahasiswaController::class, 'store'])->name('store');
        Route::get('/calculate', [TPKMahasiswaController::class, 'calculate'])->name('calculate');
    });

});

 