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

// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
// use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Dosen (Pembimbing)
use App\Http\Controllers\Dosen\KelompokController as DosenKelompokController;
use App\Http\Controllers\Dosen\MilestoneController as DosenMilestoneController;
use App\Http\Controllers\Dosen\LogbookController as DosenLogbookController;

// Dosen Penguji
use App\Http\Controllers\DosenPenguji\MahasiswaController as DPMahasiswaController;
use App\Http\Controllers\DosenPenguji\PenilaianController;
use App\Http\Controllers\DosenPenguji\RubrikController;
use App\Http\Controllers\DosenPenguji\KelompokController as DPKelompokController;
use App\Http\Controllers\DosenPenguji\MatakuliahController as DPMatakuliahController;
use App\Http\Controllers\DosenPenguji\CPMKController;
use App\Http\Controllers\DosenPenguji\PenilaianItemController;

// MODEL quick-edit
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
// REGISTER (pakai controller, jangan duplikasi dengan Route::view)
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// LOGIN/LOGOUT
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Verifikasi Email
|--------------------------------------------------------------------------
*/
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
            // ganti ke dashboard khusus jika sudah ada
            return redirect()->route('admins.dashboard')->with('verified', true);
        default:
            return redirect()->route('home')->with('verified', true);
    }
})->middleware(['auth','signed'])->name('verification.verify');

// Kirim ulang link verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth','throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Admin (role: admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admins')
    ->name('admins.')
    ->middleware(['auth','verified','role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('matakuliah', AdminMataKuliahController::class);
        Route::resource('mahasiswa', AdminMahasiswaController::class);
        Route::resource('kelompok', AdminKelompokController::class)->only(['index','show']);
        Route::resource('logbook', AdminLogbookController::class)->only(['index']);
        Route::resource('feedback', AdminFeedbackController::class);

        Route::resource('notifikasi', AdminNotifikasiController::class);
        Route::post('notifikasi/markAll', [AdminNotifikasiController::class, 'markAllRead'])->name('notifikasi.markAll');
        Route::get('notifikasi/{notification}/read', [AdminNotifikasiController::class, 'markRead'])->name('notifikasi.read');
        // Route::resource('profile', AdminProfileController::class);
});

/*
|--------------------------------------------------------------------------
| Mahasiswa (role: mahasiswa)
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')
    ->name('mahasiswa.')
    ->middleware(['auth','verified','role:mahasiswa'])
    ->group(function () {
        Route::view('/dashboard', 'mahasiswa.dashboard')->name('dashboard');
        Route::get('/logbook', [LogbookController::class, 'mahasiswaIndex'])->name('logbook');
        Route::view('/kelompok', 'mahasiswa.kelompok')->name('kelompok');
        Route::resource('milestone', \App\Http\Controllers\Mahasiswa\MahasiswaMilestoneController::class)->except(['show']);
        Route::view('/laporan-penilaian', 'mahasiswa.laporan-penilaian')->name('laporan-penilaian');
});

/*
|--------------------------------------------------------------------------
| Dosen Pembimbing (role: dosen_pembimbing)
|--------------------------------------------------------------------------
*/
Route::prefix('dosen')
    ->name('dosen.')
    ->middleware(['auth','verified','role:dosen_pembimbing'])
    ->group(function () {
        Route::view('/dashboard', 'dosen.dashboard')->name('dashboard');

        Route::resource('kelompok', DosenKelompokController::class)->names('kelompok');
        Route::view('/mahasiswa', 'dosen.mahasiswa')->name('mahasiswa');

        Route::resource('milestone', DosenMilestoneController::class)->only(['index','edit','update']);

        Route::resource('logbook', DosenLogbookController::class)->names('logbook');
        Route::patch('logbook/{logbook}/toggle-status', [DosenLogbookController::class, 'toggleStatus'])
            ->name('logbook.toggleStatus');
});

/*
|--------------------------------------------------------------------------
| Dosen Penguji (role: dosen_penguji)
|--------------------------------------------------------------------------
*/
Route::prefix('dosenpenguji')
    ->name('dosenpenguji.')
    ->middleware(['auth','verified','role:dosen_penguji'])
    ->group(function () {
        Route::redirect('/', '/dosenpenguji/dashboard');
        Route::view('/dashboard', 'dosenpenguji.dashboard')->name('dashboard');

        Route::get('/mahasiswa', [DPMahasiswaController::class, 'index'])->name('mahasiswa');

        // PENILAIAN
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
        Route::post('/penilaian/save', [PenilaianController::class, 'bulkSave'])->name('penilaian.bulkSave');
        Route::delete('/penilaian/grade/{nim}/{rubric_id}', [PenilaianController::class, 'deleteGrade'])->name('penilaian.deleteGrade');
        Route::get('/penilaian/export', [PenilaianController::class, 'export'])->name('penilaian.export');
        Route::post('/penilaian/import', [PenilaianController::class, 'import'])->name('penilaian.import');

        // RUBRIK
        Route::get('/rubrik', [RubrikController::class, 'index'])->name('rubrik.index');
        Route::put('/rubrik/{id}', function (Request $request, $id) {
            $data = $request->validate([
                'nama_rubrik' => ['required','string','max:255'],
                'deskripsi'   => ['nullable','string'],
                'bobot'       => ['required','numeric','min:0','max:100'],
                'urutan'      => ['required','integer','min:1'],
            ]);
            $updated = Rubrik::query()->whereKey($id)->update($data);
            return back()->with($updated ? 'success' : 'error',
                $updated ? 'Komponen rubrik berhasil diperbarui.' : 'Rubrik tidak ditemukan / gagal diperbarui.');
        })->name('rubrik.update');

        // CRUD Item Penilaian
        Route::prefix('penilaian-item')->name('penilaian.item.')->group(function () {
            Route::get('/create', [PenilaianItemController::class, 'create'])->name('create');
            Route::post('/', [PenilaianItemController::class, 'store'])->name('store');
            Route::get('/{item}/edit', [PenilaianItemController::class, 'edit'])->name('edit');
            Route::put('/{item}', [PenilaianItemController::class, 'update'])->name('update');
            Route::delete('/{item}', [PenilaianItemController::class, 'destroy'])->name('destroy');
        });

        // Master data
        Route::get('/kelompok', [DPKelompokController::class, 'index'])->name('kelompok');
        Route::get('/matakuliah', [DPMatakuliahController::class, 'index'])->name('matakuliah');
        Route::get('/cpmk', [CPMKController::class, 'index'])->name('cpmk.index');

        // Quick-Edit CPMK (by kode_mk + kode)
        Route::put('/cpmk/{kode_mk}/{kode}', function (Request $request, $kode_mk, $kode) {
            $data = $request->validate([
                'deskripsi' => ['required','string'],
                'bobot'     => ['required','numeric','min:0','max:100'],
                'urutan'    => ['required','integer','min:1'],
            ]);
            $updated = Cpmk::where('kode_mk', $kode_mk)->where('kode', $kode)->update($data);
            return back()->with($updated ? 'success' : 'error',
                $updated ? 'CPMK berhasil diperbarui.' : 'CPMK tidak ditemukan / gagal diperbarui.');
        })->name('cpmk.update');

        // Profil Penguji
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
| Resource Global (jika dipakai umum)
|--------------------------------------------------------------------------
*/
Route::resource('logbooks', LogbookController::class);
