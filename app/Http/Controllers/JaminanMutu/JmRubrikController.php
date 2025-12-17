<?php

namespace App\Http\Controllers\JaminanMutu;

use App\Http\Controllers\Controller;
use App\Models\Rubrik;
use App\Models\MataKuliah; // ✅ untuk dropdown MK
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class JmRubrikController extends Controller
{
    // READ ONLY: list rubrik + FILTER
    public function index(Request $request)
    {
        $q      = $request->query('q');          // search nama/deskripsi
        $mkKode = $request->query('matakuliah'); // kode_mk (string)

        // dropdown MK (kalau tabel mata_kuliah ada)
        $matakuliah = class_exists(MataKuliah::class)
            ? MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk'])
            : collect();

        $query = Rubrik::query()->latest();

        // ✅ search
        if ($q) {
            $query->where(function ($s) use ($q) {
                $s->where('nama_rubrik', 'like', "%{$q}%")
                  ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        // ✅ filter MK (aman walau kolomnya tidak ada)
        $table = (new Rubrik)->getTable();
        if ($mkKode) {
            if (Schema::hasColumn($table, 'matakuliah_kode')) {
                $query->where('matakuliah_kode', $mkKode);
            } elseif (Schema::hasColumn($table, 'kode_mk')) {
                $query->where('kode_mk', $mkKode);
            }
            // kalau rubrik memang tidak punya kolom MK -> otomatis skip (tidak error)
        }

        $rubrik = $query->paginate(10)->withQueryString();

        return view('jaminanmutu.rubrik.index', compact('rubrik', 'matakuliah', 'q', 'mkKode'));
    }

    // READ ONLY: detail rubrik
    public function show(Rubrik $rubrik)
    {
        return view('jaminanmutu.rubrik.show', compact('rubrik'));
    }
}
