<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\KelompokController as AdminKelompokController;
use App\Http\Controllers\Admin\LogbookController as AdminLogbookController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LogbookController;
use App\Models\Logbook;
use App\Models\Milestone;

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
    Route::view('/kelompok', 'dosen.kelompok')->name('kelompok');
    Route::view('/mahasiswa', 'dosen.mahasiswa')->name('mahasiswa');
    Route::view('/milestone', 'dosen.milestone')->name('milestone');
    Route::view('/logbook', 'dosen.logbook')->name('logbook');
});


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


/*
|--------------------------------------------------------------------------
| Mahasiswa (wajib login & role:mahasiswa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:mahasiswa'])
    ->prefix('mahasiswa')->as('mhs.')
    ->group(function () {


        Route::get('/dashboard', function () {
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('login');
            }

            // Ambil data dinamis
            $logbooks = Logbook::where('user_id', $user->id)->latest()->take(3)->get();
            $milestones = Milestone::orderBy('deadline')->get();

            // Data untuk dikirim ke view
            $data = [
                'nama' => $user->name,
                'jumlahLogbook' => $logbooks->count(),
                'totalMilestone' => $milestones->count(),
                'milestoneSelesai' => $milestones->where('status', 'Selesai')->count(),
                'anggotaKelompok' => 0, // Anggap 0 karena tidak ada pencarian kelompok
                'logbooks' => $logbooks,
                'milestones' => $milestones,
            ];

            return view('mahasiswa.dashboard', $data);
        })->name('dashboard');

        // Logbook (controller agar pagination/links berfungsi)
        Route::get('/logbook', [LogbookController::class, 'index'])->name('logbook.index');
        Route::post('/logbook', [LogbookController::class, 'store'])->name('logbook.store');
        Route::get('/logbook/{logbook}/edit', [LogbookController::class, 'edit'])->name('logbook.edit');
        Route::put('/logbook/{logbook}', [LogbookController::class, 'update'])->name('logbook.update');
        Route::delete('/logbook/{logbook}', [LogbookController::class, 'destroy'])->name('logbook.destroy');
        Route::get('/logbook/{logbook}/download', [LogbookController::class, 'download'])->name('logbook.download');

        Route::view('/kelompok', 'mahasiswa.kelompok')->name('kelompok');
        Route::view('/milestone', 'mahasiswa.milestone')->name('milestone');
        Route::view('/penilaian', 'mahasiswa.penilaian')->name('penilaian');
    });
