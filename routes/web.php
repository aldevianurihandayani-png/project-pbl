<?php

use App\Http\Controllers\LogbookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ContactController; 
use App\Models\Milestone;
use App\Http\Controllers\KelompokController;

use Illuminate\Http\Request;

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

// ==============================
// Setelah Login
// ==============================

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('mahasiswa', MahasiswaController::class);
// sekarang /mahasiswa, /mahasiswa/create, /mahasiswa/{id}/edit, dst.

Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
Route::get('/mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
Route::post('/mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
Route::get('/mahasiswa/{nim}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
Route::put('/mahasiswa/{nim}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
Route::delete('/mahasiswa/{nim}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');




    //dashboard dosne pembimbing

Route::get('dosen/dashboard', function () {
    return view('dosen.dashboard');   // <— folder.view yg benar
})->name('dosen.dashboard');
// === Route lain diarahkan ke dashboard juga sementara ===
// (opsional) placeholder menu lain — sesuaikan bila sudah ada halamannya
Route::get('/dosen/mahasiswa', fn() => view('placeholders.mahasiswa'))->name('dosen.mahasiswa.index');
Route::get('/dosen/kelompok',  fn() => view('placeholders.kelompok'))->name('dosen.kelompok.index');
Route::get('/dosen/milestone', fn() => view('placeholders.milestone'))->name('dosen.milestone.index');
Route::get('/dosen/logbook',   fn() => view('placeholders.logbook'))->name('dosen.logbook.index');

Route::get('/dosen/milestone', function () {
    $milestones = Milestone::all();
    return view('milestone.index', compact('milestones'));
})->name('dosen.milestone');



// INDEX
Route::get('/dosen/milestone', function () {
    $milestones = Milestone::all();
    return view('milestone.index', compact('milestones'));
})->name('dosen.milestone');

// CREATE (tampilkan form)
Route::get('/dosen/milestone/create', function () {
    return view('milestone.create'); // resources/views/milestone/create.blade.php
})->name('dosen.milestone.create');

// STORE (simpan data)
Route::post('/dosen/milestone', function (Request $request) {
    $data = $request->validate([
        'minggu'   => ['required','integer'],
        'kegiatan' => ['required','string','max:255'],
        'deadline' => ['required','date'],
        'status'   => ['required','in:Belum,Pending,Selesai'],
    ]);
    Milestone::create($data);
    return redirect()->route('dosen.milestone')->with('success','Milestone ditambahkan.');
})->name('dosen.milestone.store');


// INDEX — khusus dosen pembimbing
Route::get('/dosen/milestone', function () {
    // kalau kosong, buat 1 contoh data
    $sample = [
        'minggu'   => 1,
        'kegiatan' => 'Menghitung manual TPK',
        'deadline' => '2025-10-09',
        'status'   => 'Pending',
        'approved' => 0,
    ];
    \App\Models\Milestone::firstOrCreate(
        ['minggu' => 1, 'kegiatan' => 'Menghitung manual TPK', 'deadline' => '2025-10-09'],
        $sample
    );

    $milestones = \App\Models\Milestone::orderBy('deadline')->get();
    return view('milestone.index', compact('milestones'));
})->name('dosen.milestone');

// APPROVE — dosen menandai selesai
Route::patch('/dosen/milestone/{id}/approve', function ($id) {
    $m = \App\Models\Milestone::findOrFail($id);
    $m->status = 'Selesai';
    $m->approved = 1;
    $m->approved_at = now();
    $m->save();

    return redirect()->route('dosen.milestone')->with('success','Milestone ditandai selesai.');
})->name('dosen.milestone.approve');

Route::patch('/dosen/milestone/{id}/status/{status}', function ($id, $status) {
    abort_unless(in_array($status, ['Belum','Pending','Selesai']), 404);

    $m = Milestone::findOrFail($id);
    $m->status = $status;

    // jika selesai, otomatis approved
    if ($status === 'Selesai') {
        $m->approved = 1;
        $m->approved_at = now();
    } else {
        $m->approved = 0;
        $m->approved_at = null;
    }

    $m->save();
    return back()->with('success', "Status diubah ke {$status}");
})->name('dosen.milestone.setStatus');


Route::patch('/dosen/milestone/{id}/status/{status}', function ($id, $status) {
    abort_unless(in_array($status, ['Belum','Pending','Selesai']), 404);
    $m = Milestone::findOrFail($id);
    $m->status = $status;
    if ($status === 'Selesai') { $m->approved = 1; $m->approved_at = now(); }
    else { $m->approved = 0; $m->approved_at = null; }
    $m->save();
    return back();
})->name('dosen.milestone.setStatus');

//kelompok dosen pembimbing
Route::get('/dosen/kelompok', [KelompokController::class, 'index'])->name('dosen.kelompok');

//Dosen penguji 

Route::get('/dosenpenguji/dashboard', function () {
    return view('dosenpenguji.dashboard');
})->name('dosenpenguji.dashboard');

//Mahasiswa 
Route::get('/mahasiswa/dashboard', function () {
    return view('mahasiswa.dashboard');
})->name('mahasiswa.dashboard');


Route::resource('mahasiswa', MahasiswaController::class);
// minimal untuk index saja:
// Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');

//routes kelompok
Route::resource('kelompok', \App\Http\Controllers\KelompokController::class);
