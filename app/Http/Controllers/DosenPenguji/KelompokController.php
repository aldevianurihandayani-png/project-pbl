<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\ProyekPbl;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class KelompokController extends Controller
{
    /**
     * Dashboard dosen penguji.
     * (MASIH pakai filter penguji – bisa kita ubah nanti kalau perlu)
     */
    public function dashboard()
    {
        $penguji = Auth::user();

        // Hanya ambil kelompok yang memang di-assign ke penguji ini
        $kelompoks = Kelompok::with(['proyekPbl', 'anggota', 'mahasiswas'])
            ->whereHas('penguji', function ($query) use ($penguji) {
                $query->where('penguji_id', $penguji->id);
            })
            ->paginate(10);

        // Total masih pakai count() global
        $totalProyek     = ProyekPbl::count();
        $totalMahasiswa  = Mahasiswa::count();
        $totalKelompok   = Kelompok::count();

        return view('dosenpenguji.dashboard', compact(
            'kelompoks',
            'totalProyek',
            'totalMahasiswa',
            'totalKelompok'
        ));
    }

    /**
     * LIST KELOMPOK + SEARCH + FILTER KELAS & SEMESTER (untuk dosen penguji).
     * UNTUK TES: TANPA FILTER penguji dulu, jadi semua kelompok kelihatan.
     */
    public function index(Request $request)
    {
        $search   = $request->query('q');            // teks pencarian
        $kelasKey = $request->query('kelas', 'all'); // A / B / C / D / E / all
        $semester = $request->query('semester', 'all');

        // fallback nama kolom untuk order
        $nameCol = Schema::hasColumn('kelompoks', 'nama_kelompok')
            ? 'nama_kelompok'
            : (Schema::hasColumn('kelompoks', 'nama') ? 'nama' : 'id');

        $query = Kelompok::query()
            ->with([
                'proyekPbl:id_proyek_pbl,id_kelompok,judul',
                'ketua:nim,nama,angkatan',
                'anggota.mahasiswa:nim,nama,angkatan',
            ]);
            // ⛔️ sementara TANPA whereHas('penguji') supaya data kelihatan semua

        // ========== FILTER PENCARIAN ==========
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('anggota', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('nama_klien', 'like', "%{$search}%")
                  // cari di ketua
                  ->orWhereHas('ketua', function ($qq) use ($search) {
                      $qq->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  })
                  // cari di anggota
                  ->orWhereHas('anggota.mahasiswa', function ($qm) use ($search) {
                      $qm->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  })
                  // cari di judul proyek
                  ->orWhereHas('proyekPbl', function ($qp) use ($search) {
                      $qp->where('judul', 'like', "%{$search}%");
                  });
            });
        }

        // ========== FILTER KELAS (A–E) ==========
        if ($kelasKey !== 'all') {
            // contoh: kelasKey = 'B' → semua kelas yang berakhiran "B" (TI-3B, TI-2B, dst)
            $query->where('kelas', 'like', "%{$kelasKey}");
        }

        // ========== FILTER SEMESTER (1–6) ==========
        if ($semester !== 'all' && Schema::hasColumn('kelompoks', 'semester')) {
            $query->where('semester', $semester);
        }

        // Urutkan dan paginate
        $kelompok = $query
            ->orderBy('kelas')
            ->orderBy($nameCol)
            ->paginate(10)
            ->withQueryString();

        // view index penguji: resources/views/dosenpenguji/kelompok.blade.php
        return view('dosenpenguji.kelompok', compact('kelompok'));
    }

    /**
     * DAFTAR KELOMPOK DALAM SATU KELAS (TI-3A, TI-3B, dst) untuk dosen penguji.
     * UNTUK TES: TANPA FILTER penguji dulu.
     */
    public function kelas($kelas)
    {
        $kelompoks = Kelompok::where('kelas', $kelas)->get();
        // kalau nanti mau dibatasi per penguji, baru tambah whereHas('penguji', ...) di sini

        return view('dosenpenguji.kelompok-kelas', [
            'kelas'     => $kelas,
            'kelompoks' => $kelompoks,
        ]);
    }

    /**
     * DETAIL SATU KELOMPOK untuk dosen penguji.
     * UNTUK TES: TANPA FILTER penguji dulu.
     */
    public function show($id)
    {
        $kelompok = Kelompok::with([
                'proyekPbl',
                'ketua',
                'mahasiswas',       // dipakai di tabel anggota di kelompok-show
                'penguji',          // kalau mau ditampilkan daftar penguji
                'anggota.mahasiswa' // kalau masih ada logika lama yang pakai ini
            ])
            ->findOrFail($id);
        // kalau nanti mau dibatasi per penguji, baru tambahkan whereHas('penguji', ...) di sini

        return view('dosenpenguji.kelompok-show', compact('kelompok'));
    }
}
