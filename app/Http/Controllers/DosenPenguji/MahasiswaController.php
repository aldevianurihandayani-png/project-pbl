<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    /**
     * HALAMAN UTAMA
     * Menampilkan kartu per kelas (seperti halaman kelompok).
     * URL: /dosenpenguji/mahasiswa
     */
    public function index(Request $request)
    {
        // filter dari query string (optional)
        $filterKelas = $request->get('kelas');      // A, B, C, D, E, atau "Semua"
        $filterSmtr  = $request->get('semester');   // kalau ada kolom semester
        $keyword     = $request->get('q');          // cari nama / NIM

        $query = Mahasiswa::query();

        if ($filterKelas && $filterKelas !== 'Semua') {
            $query->where('kelas', $filterKelas);
        }

        // kalau di tabel mahasiswas BELUM ada kolom "semester", baris ini boleh dihapus
        if ($filterSmtr) {
            $query->where('semester', $filterSmtr);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('nim', 'like', "%{$keyword}%");
            });
        }

        // summary per kelas → bahan untuk kartu
        $kelasSummary = $query
            ->select('kelas', DB::raw('COUNT(*) as total_mahasiswa'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        // ⛔️ tadinya: 'dosenpenguji.mahasiswa.index'  → view-nya tidak ada
        // ✅ sekarang pakai file: resources/views/dosenpenguji/mahasiswa.blade.php
        return view('dosenpenguji.mahasiswa', compact(
            'kelasSummary',
            'filterKelas',
            'filterSmtr',
            'keyword'
        ));
    }

    /**
     * DETAIL PER KELAS
     * Menampilkan tabel mahasiswa (paginate + search) untuk satu kelas.
     * URL: /dosenpenguji/mahasiswa/kelas/{kelas}
     */
    public function showByKelas(Request $request, $kelas)
    {
        $search = $request->query('q');

        $query = Mahasiswa::with(['dosenPembimbing', 'proyekPbl', 'user'])
            ->where('kelas', $kelas);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $mahasiswa = $query
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        // pakai file: resources/views/dosenpenguji/mahasiswa_detail.blade.php
        return view('dosenpenguji.mahasiswa_detail', compact(
            'mahasiswa',
            'kelas',
            'search'
        ));
    }
}
