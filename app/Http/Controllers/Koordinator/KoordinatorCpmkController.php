<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Cpmk;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class KoordinatorCpmkController extends Controller
{
    public function index(Request $request)
    {
        $mkKode = $request->query('matakuliah'); // kode_mk
        $q      = $request->query('q');          // search

        // dropdown MK
        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);

        $query = Cpmk::query()->latest();

        // cari kolom FK MK yang benar di tabel cpmk
        $mkColumn = null;
        foreach (['matakuliah_kode', 'kode_mk', 'mata_kuliah_kode'] as $col) {
            if (Schema::hasColumn('cpmk', $col)) {
                $mkColumn = $col;
                break;
            }
        }

        // FILTER MK
        if ($mkKode && $mkColumn) {
            $query->where($mkColumn, $mkKode);
        }

        // SEARCH (kode/deskripsi)
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('kode_cpmk', 'like', "%{$q}%")
                    ->orWhere('kode', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('uraian', 'like', "%{$q}%");
            });
        }

        // ✅ TANPA PAGINATION
        $cpmk = $query->get();

        return view('koordinator.cpmk.index', compact('cpmk','matakuliah','mkKode','q'));
    }

    public function show(Cpmk $cpmk)
    {
        return view('koordinator.cpmk.show', compact('cpmk'));
    }
}
