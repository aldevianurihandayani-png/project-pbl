<?php

// app/Http/Controllers/DosenPenguji/KelompokController.php

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
     */
    public function dashboard()
    {
        $kelompoks = Kelompok::with(['proyekPbl', 'anggota', 'mahasiswas'])
            ->paginate(10);

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
     * List kelompok + search untuk dosen penguji.
     */
    public function index(Request $request)
    {
        $search = $request->query('q');

        // fallback nama kolom untuk order
        $nameCol = Schema::hasColumn('kelompoks', 'nama_kelompok')
            ? 'nama_kelompok'
            : (Schema::hasColumn('kelompoks', 'nama') ? 'nama' : 'id');

        $kelompok = Kelompok::query()
            ->with([
                'proyekPbl:id_proyek_pbl,id_kelompok,judul',
                'ketua:nim,nama,angkatan',
                'anggota.mahasiswa:nim,nama,angkatan',
            ])
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nama_klien', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhereHas('proyekPbl', fn($qp) =>
                        $qp->where('judul', 'like', "%{$search}%")
                  )
                  ->orWhereHas('anggota.mahasiswa', fn($qm) =>
                        $qm->where('nama', 'like', "%{$search}%")
                           ->orWhere('nim', 'like', "%{$search}%")
                  );
            })
            ->orderBy('kelas')
            ->orderBy($nameCol)
            ->paginate(10)
            ->withQueryString();

        // view index penguji: resources/views/dosenpenguji/kelompok.blade.php
        return view('dosenpenguji.kelompok', compact('kelompok'));
    }

    /**
     * DETAIL SATU KELOMPOK untuk dosen penguji.
     * Dipakai oleh route: dosenpenguji.kelompok.show
     */
    public function show($id)
    {
        // sekalian eager load relasi biar nggak N+1
        $kelompok = Kelompok::with([
                'proyekPbl',
                'ketua',
                'anggota.mahasiswa',
            ])
            ->findOrFail($id);

        // bikin view: resources/views/dosenpenguji/kelompok-show.blade.php
        return view('dosenpenguji.kelompok-show', compact('kelompok'));
    }
}
