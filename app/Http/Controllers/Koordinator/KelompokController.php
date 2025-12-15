<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * LIST DATA KELOMPOK (READ ONLY)
     */
    public function index(Request $request)
    {
        $query = Kelompok::query();

        // Filter semester + kelas (opsional)
        if ($request->filled('semester')) {
            $kelasFilter = 'TI-' . $request->semester;

            if ($request->filled('kelas')) {
                $kelasFilter .= $request->kelas;
            }

            $query->where('kelas', 'like', $kelasFilter . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('judul_proyek', 'like', "%{$search}%");
            });
        }

        $kelompoks = $query
            ->with('dosenPembimbing')
            ->get();

        return view('koordinator.kelompok.index', [
            'kelompoks' => $kelompoks,
            'request'   => $request,
        ]);
    }

    /**
     * DETAIL KELOMPOK
     */
    public function show(Kelompok $kelompok)
    {
        // ⬇⬇⬇ PERBAIKAN PENTING DI SINI ⬇⬇⬇
        $kelompok->load([
            'mahasiswas',       // ✅ sesuai model
            'dosenPembimbing',
            'ketua'
        ]);

        return view('koordinator.kelompok.detail', [
            'kelompok' => $kelompok,
        ]);
    }
}
