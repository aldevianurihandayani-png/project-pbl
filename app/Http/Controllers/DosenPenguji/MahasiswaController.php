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
        $filterSmtr  = $request->get('semester');   // cuma buat isi dropdown, TIDAK dipakai di query
        $keyword     = $request->get('q');          // cari nama / NIM

        $query = Mahasiswa::query();

        // filter kelas
        if ($filterKelas && $filterKelas !== 'Semua') {
            $query->where('kelas', $filterKelas);
        }

        // ⛔ JANGAN PAKAI FILTER SEMESTER, karena kolom semester ga ada di tabel
        // if ($filterSmtr) {
        //     $query->where('semester', $filterSmtr);
        // }

        // filter nama / nim
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%{$keyword}%")
                  ->orWhere('nim', 'like', "%{$keyword}%");
            });
        }

        // summary per kelas → bahan untuk kartu
        $kelasSummary = $query
            ->select('kelas', DB::raw('COUNT(*) as total_mahasiswa'))
            ->whereNotNull('kelas')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        // view: resources/views/dosenpenguji/mahasiswa/index.blade.php
        return view('dosenpenguji.mahasiswa.index', compact(
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

        // ❌ HAPUS with(['dosenPembimbing', 'proyekPbl', 'user'])
        $query = Mahasiswa::query()
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

        // view detail: resources/views/dosenpenguji/mahasiswa_detail.blade.php
        return view('dosenpenguji.mahasiswa_detail', compact(
            'mahasiswa',
            'kelas',
            'search'
        ));
    }
}
