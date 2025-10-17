<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Cpmk;

class CpmkController extends Controller
{
    public function index(Request $request)
    {
        $mk = $request->query('matakuliah');

        // SELALU ambil semua MK untuk dropdown (tanpa filter)
        $matakuliah = MataKuliah::orderBy('nama_mk')
                        ->get(['kode_mk','nama_mk']);

        // Default kosong agar Blade aman
        $cpmk = collect();

        // Jika user pilih MK, ambil CPMK-nya
        if ($mk) {
            $cpmk = Cpmk::where('kode_mk', $mk)
                    ->orderBy('urutan')
                    ->get(['kode','deskripsi','bobot','urutan']);
        }

        return view('dosenpenguji.cpmk', compact('matakuliah','cpmk','mk'));
    }
}
