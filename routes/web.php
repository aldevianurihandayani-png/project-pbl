<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Dosen\MilestoneController as DosenMilestoneController;

// Dosen

// Dosen Penguji
use App\Http\Controllers\DosenPenguji\MahasiswaController;
use App\Http\Controllers\DosenPenguji\MahasiswaController as DPMahasiswaController;
use App\Http\Controllers\DosenPenguji\PenilaianController;
use App\Http\Controllers\DosenPenguji\RubrikController;
use App\Http\Controllers\DosenPenguji\KelompokController as DPKelompokController;
use App\Http\Controllers\DosenPenguji\MatakuliahController;
use App\Http\Controllers\DosenPenguji\MatakuliahController as DPMatakuliahController;
// Alias supaya simbol CPMKController mengarah ke kelas CpmkController milikmu
use App\Http\Controllers\DosenPenguji\CpmkController as CPMKController;
// >>> ADD: controller CRUD item penilaian
use App\Http\Controllers\DosenPenguji\PenilaianItemController;

// MODEL untuk quick-edit CPMK
use App\Models\Cpmk;
// >>> ADD: MODEL untuk quick-edit Rubrik
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
Route::view('/register', 'register')->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.post');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Dosen Penguji Routes (versi tanpa middleware; dibiarkan apa adanya)
Route::prefix('dosenpenguji')->name('dosenpenguji.')->group(function () {
    Route::get('/', fn() => redirect()->route('dosenpenguji.dashboard'));
    Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::post('/penilaian/save', [PenilaianController::class, 'bulkSave'])->name('penilaian.bulkSave');
    Route::delete('/penilaian/grade/{nim}/{rubric_id}', [PenilaianController::class, 'deleteGrade'])->name('penilaian.deleteGrade');
    Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
    Route::post('/penilaian/import', [PenilaianController::class, 'import'])->name('penilaian.import');
    Route::get('/rubrik', [RubrikController::class, 'index'])->name('rubrik.index');
    Route::get('/kelompok', [DPKelompokController::class, 'index'])->name('kelompok');
    Route::get('/matakuliah', [MatakuliahController::class, 'index'])->name('matakuliah');
    Route::get('/cpmk', [CPMKController::class, 'index'])->name('cpmk.index');

    // ==============================
    // PROFIL DOSEN PENGUJI
    // ==============================
    Route::get('/profile', fn () => view('dosenpenguji.profile'))->name('profile');
    Route::get('/profile/edit', fn () => view('dosenpenguji.profile-edit'))->name('profile.edit');

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
Route::prefix('admins')->name('admins.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('matakuliah', AdminMataKuliahController::class);
    Route::resource('mahasiswa', AdminMahasiswaController::class);
    Route::resource('kelompok', AdminKelompokController::class)->only(['index', 'show']);
    Route::resource('logbook', AdminLogbookController::class)->only(['index']);
    Route::resource('feedback', AdminFeedbackController::class);
    Route::resource('notifikasi', AdminNotifikasiController::class);
    Route::post('notifikasi/markAll', [AdminNotifikasiController::class, 'markAllRead'])->name('notifikasi.markAll');
    Route::get('notifikasi/{notification}/read', [AdminNotifikasiController::class, 'markRead'])->name('notifikasi.read');
});

/*
|--------------------------------------------------------------------------
| Mahasiswa
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')->name('mahasiswa.')->middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::view('/dashboard', 'mahasiswa.dashboard')->name('dashboard');
    Route::get('/logbook', [LogbookController::class, 'mahasiswaIndex'])->name('logbook');
    Route::view('/kelompok', 'mahasiswa.kelompok')->name('kelompok');
    Route::view('/milestone', 'mahasiswa.milestone')->name('milestone');
    Route::view('/penilaian', 'mahasiswa.penilaian')->name('penilaian');
});

/*
|--------------------------------------------------------------------------
| Dosen
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')->name('dosen.')->middleware(['auth', 'role:dosen_pembimbing'])->group(function () {
    Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');
    Route::resource('kelompok', DosenKelompokController::class)->names('kelompok');
    Route::view('/mahasiswa', 'dosen.mahasiswa')->name('mahasiswa');
    Route::resource('milestone', DosenMilestoneController::class)->only(['index', 'edit', 'update']);
    Route::view('/logbook', 'dosen.logbook')->name('logbook');
});


/*
|--------------------------------------------------------------------------
| Dosen Penguji (wajib login) — DI SINI KITA TAMBAH ROUTE PUT CPMK dan RUBRIK
|--------------------------------------------------------------------------
*/
Route::prefix('dosenpenguji')->name('dosenpenguji.')->middleware(['auth', 'role:dosen_penguji'])->group(function () {
    Route::redirect('/', '/dosenpenguji/dashboard');
    Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');
    Route::get('/mahasiswa', [DPMahasiswaController::class, 'index'])->name('mahasiswa');
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::post('/penilaian/save', [PenilaianController::class, 'bulkSave'])->name('penilaian.bulkSave');
    Route::delete('/penilaian/grade/{nim}/{rubric_id}', [PenilaianController::class, 'deleteGrade'])->name('penilaian.deleteGrade');
    Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
    Route::post('/penilaian/import', [PenilaianController::class, 'import'])->name('penilaian.import');
    Route::get('/rubrik', [RubrikController::class, 'index'])->name('rubrik.index');

    // >>> ADD: CRUD item penilaian (create/edit/update/delete)
    Route::prefix('penilaian-item')->name('penilaian.item.')->group(function () {
        Route::get('/create', [PenilaianItemController::class, 'create'])->name('create');
        Route::post('/', [PenilaianItemController::class, 'store'])->name('store');
        Route::get('/{item}/edit', [PenilaianItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [PenilaianItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [PenilaianItemController::class, 'destroy'])->name('destroy');
    });

    // >>> ADD: UPDATE Rubrik (Quick-Edit modal) — by ID
    Route::put('/rubrik/{id}', function (\Illuminate\Http\Request $request, $id) {
        $data = $request->validate([
            'nama_rubrik' => ['required','string','max:255'],
            'deskripsi'   => ['nullable','string'],
            'bobot'       => ['required','numeric','min:0','max:100'],
            'urutan'      => ['required','integer','min:1'],
            // 'kode_mk'   => ['nullable','string'],
        ]);

        $updated = Rubrik::query()->whereKey($id)->update($data);

        return back()->with($updated ? 'success' : 'error',
            $updated ? 'Komponen rubrik berhasil diperbarui.' : 'Rubrik tidak ditemukan / gagal diperbarui.');
    })->name('dosenpenguji.rubrik.update');

    Route::get('/kelompok', [DPKelompokController::class, 'index'])->name('kelompok');
    Route::get('/matakuliah', [DPMatakuliahController::class, 'index'])->name('matakuliah');
    Route::get('/cpmk', [CPMKController::class, 'index'])->name('cpmk.index');

    // ====== TAMBAHAN BARU: UPDATE CPMK (Quick-Edit modal) ======
    Route::put('/cpmk/{kode_mk}/{kode}', function (Request $request, $kode_mk, $kode) {
        $data = $request->validate([
            'deskripsi' => ['required','string'],
            'bobot'     => ['required','numeric','min:0','max:100'],
            'urutan'    => ['required','integer','min:1'],
        ]);

        $updated = Cpmk::where('kode_mk', $kode_mk)
                       ->where('kode', $kode)
                       ->update($data);

        return back()->with($updated ? 'success' : 'error',
            $updated ? 'CPMK berhasil diperbarui.' : 'CPMK tidak ditemukan / gagal diperbarui.');
    })->name('cpmk.update');

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

/*
|--------------------------------------------------------------------------
| Logbooks (global)
|--------------------------------------------------------------------------
*/
Route::resource('logbooks', LogbookController::class);
