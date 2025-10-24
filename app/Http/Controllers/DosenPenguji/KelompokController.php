<?php

// app/Http/Controllers/DosenPenguji/KelompokController.php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\ProyekPbl;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class KelompokController extends Controller
{
    /**
     * Menampilkan data untuk dashboard dosen penguji dengan query yang dioptimalkan.
     */
    public function dashboard()
    {
        // OPTIMASI: Menggunakan eager loading (with) untuk mengambil relasi proyek, anggota, dan mahasiswa
        // dalam satu query utama untuk menghindari N+1 problem.
        $kelompoks = Kelompok::with(['proyekPbl', 'anggota', 'mahasiswa'])
            ->paginate(10); // OPTIMASI: Menggunakan paginasi untuk data yang besar.

        $totalProyek = ProyekPbl::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalKelompok = Kelompok::count();

        return view('dosenpenguji.dashboard', compact('kelompoks', 'totalProyek', 'totalMahasiswa', 'totalKelompok'));
    }

    /**
     * Menampilkan daftar kelompok dengan fungsionalitas pencarian yang dioptimalkan.
     */
    public function index(Request $request)
    {
        $search = $request->query('q');

        // OPTIMASI: Eager loading untuk relasi 'proyek' dan 'anggota.mahasiswa'
        // untuk menghindari N+1 problem saat menampilkan data di view.
        $kelompok = Kelompok::with(['proyekPbl', 'anggota.mahasiswa'])
            ->when($search, function($q) use ($search) {
                $q->where('nama_kelompok', 'like', "%{$search}%")
                  ->orWhereHas('proyekPbl', fn($qp) => $qp->where('judul', 'like', "%{$search}%"));
            })
            ->orderBy('nama_kelompok')
            ->paginate(10)
            ->withQueryString();

        return view('dosenpenguji.kelompok', compact('kelompok'));
    }
}
