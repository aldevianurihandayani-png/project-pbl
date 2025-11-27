<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Hash;


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
// REGISTER (pakai controller)
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// LOGIN/LOGOUT
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Helper Redirect Per-Role (Satu Pintu)
|--------------------------------------------------------------------------
|
| Dipakai setelah login & setelah verifikasi email.
|
*/
Route::get('/redirect-role', function () {
    $user = Auth::user();
    if (!$user) return redirect()->route('login');

    $role = strtolower($user->role);

    return match ($role) {
        'admin'             => redirect()->route('admins.dashboard'),
        'mahasiswa'         => redirect()->route('mahasiswa.dashboard'),
        'dosen_pembimbing'  => redirect()->route('dosen.dashboard'),
        'dosen_penguji'     => redirect()->route('dosenpenguji.dashboard'),
        // sementara arahkan ke admin jika dashboard khusus belum ada:
        'jaminan_mutu'      => redirect()->route('admins.dashboard'),
        'koor_pbl'          => redirect()->route('admins.dashboard'),
        default             => redirect()->route('home'),
    };
})->name('redirect.role')->middleware(['auth']);

/*
|--------------------------------------------------------------------------
| Verifikasi Email
|--------------------------------------------------------------------------
*/
// Notice "cek email"
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Link verifikasi yang diklik user -> tandai verified -> redirect sesuai role
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    if (! $request->user()->hasVerifiedEmail()) {
        $request->fulfill();
    }
    return redirect()->route('redirect.role')->with('verified', true);
})->middleware(['auth','signed','throttle:6,1'])->name('verification.verify');

// Kirim ulang link verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth','throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| Admin (role: admin)  --> menghasilkan NAMA ROUTE: admins.dashboard
|--------------------------------------------------------------------------
*/
Route::prefix('admins')
    ->name('admins.')
    ->middleware(['auth','verified','role:admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // (rute admin lainmu lanjut di sini bila diperlukan)
        // Route::resource('matakuliah', AdminMataKuliahController::class);
        // Route::resource('mahasiswa', AdminMahasiswaController::class);
        // ...
    });

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
        
        // ==== Tambahkan rute profil admin di sini ====
        Route::view('/profile', 'admins.profile.index')->name('profile.index');
        Route::view('/profile/edit', 'admins.profile.edit')->name('profile.edit');
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
                $data['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }

            $user->update($data);
            auth()->setUser($user->fresh());

            return redirect()->route('admins.profile.index')->with('success', 'Profil berhasil diperbarui.');
        })->name('profile.update');
        // ==== sampai sini ====
});
