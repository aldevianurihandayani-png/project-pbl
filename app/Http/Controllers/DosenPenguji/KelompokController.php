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
     * Dashboard dosen penguji
     */
    public function dashboard()
    {
        $penguji = Auth::user();

        $kelompoks = Kelompok::with(['proyek', 'anggota', 'mahasiswas'])
            ->whereHas('penguji', function ($query) use ($penguji) {
                $query->where('penguji_id', $penguji->id);
            })
            ->paginate(10);

        $totalProyek    = ProyekPbl::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalKelompok  = Kelompok::count();

        return view('dosenpenguji.dashboard', compact(
            'kelompoks',
            'totalProyek',
            'totalMahasiswa',
            'totalKelompok'
        ));
    }

    /**
     * LIST KELOMPOK + SEARCH + FILTER
     */
    public function index(Request $request)
    {
        $search   = $request->query('q');
        $kelasKey = $request->query('kelas', 'all');
        $semester = $request->query('semester', 'all');

        // fallback kolom nama
        $nameCol = Schema::hasColumn('kelompoks', 'nama_kelompok')
            ? 'nama_kelompok'
            : (Schema::hasColumn('kelompoks', 'nama') ? 'nama' : 'id');

        $query = Kelompok::query()
            ->with([
                'proyek:id_proyek_pbl,id_kelompok,judul',
                'ketua:nim,nama,angkatan',
                'anggota.mahasiswa:nim,nama,angkatan',
            ]);

        // ===== SEARCH =====
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('anggota', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('nama_klien', 'like', "%{$search}%")

                  ->orWhereHas('ketua', function ($qq) use ($search) {
                      $qq->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  })

                  ->orWhereHas('anggota.mahasiswa', function ($qm) use ($search) {
                      $qm->where('nama', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                  })

                  ->orWhereHas('proyek', function ($qp) use ($search) {
                      $qp->where('judul', 'like', "%{$search}%");
                  });
            });
        }

        // ===== FILTER KELAS =====
        if ($kelasKey !== 'all') {
            $query->where('kelas', 'like', "%{$kelasKey}");
        }

        // ===== FILTER SEMESTER =====
        if ($semester !== 'all' && Schema::hasColumn('kelompoks', 'semester')) {
            $query->where('semester', $semester);
        }

        $kelompok = $query
            ->orderBy('kelas')
            ->orderBy($nameCol)
            ->paginate(10)
            ->withQueryString();

        return view('dosenpenguji.kelompok', compact('kelompok'));
    }

    /**
     * DAFTAR KELOMPOK DALAM SATU KELAS
     */
    public function kelas($kelas)
    {
        $kelompoks = Kelompok::with('proyek')
            ->where('kelas', $kelas)
            ->get();

        return view('dosenpenguji.kelompok-kelas', [
            'kelas'     => $kelas,
            'kelompoks' => $kelompoks,
        ]);
    }

    /**
     * DETAIL SATU KELOMPOK
     */
    public function show($id)
    {
        $kelompok = Kelompok::with([
                'proyek',
                'ketua',
                'mahasiswas',
                'penguji',
                'anggota.mahasiswa',
            ])
            ->findOrFail($id);

        return view('dosenpenguji.kelompok-show', compact('kelompok'));
    }
}
