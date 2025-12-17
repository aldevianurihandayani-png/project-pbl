<?php

namespace App\Http\Controllers\JaminanMutu;

use App\Http\Controllers\Controller;
use App\Models\Rubrik;
use App\Models\MataKuliah; // ✅ untuk dropdown MK
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class JmRubrikController extends Controller
{
    // READ ONLY: list rubrik + FILTER (tanpa pagination)
    public function index(Request $request)
    {
        $q      = $request->query('q');          // search nama/deskripsi
        $mkKode = $request->query('matakuliah'); // kode_mk (string)

        // dropdown MK
        $matakuliah = class_exists(MataKuliah::class)
            ? MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk'])
            : collect();

        $rubrikModel = new Rubrik();
        $table = $rubrikModel->getTable();

        $query = Rubrik::query()->latest();

        // ✅ cari kolom FK MK yang benar di tabel rubrik
        $mkColumn = null;
        foreach (['matakuliah_kode', 'kode_mk', 'mata_kuliah_kode', 'mk_kode'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                $mkColumn = $col;
                break;
            }
        }

        // ✅ FILTER MK (pakai kolom yang ditemukan)
        if (!empty($mkKode) && $mkColumn) {
            $query->where($mkColumn, $mkKode);
        }

        // ✅ SEARCH (nama/deskripsi) — aman kalau kolomnya beda
        if (!empty($q)) {
            $query->where(function ($s) use ($q, $table) {
                if (Schema::hasColumn($table, 'nama_rubrik')) {
                    $s->orWhere('nama_rubrik', 'like', "%{$q}%");
                }
                if (Schema::hasColumn($table, 'deskripsi')) {
                    $s->orWhere('deskripsi', 'like', "%{$q}%");
                }
                // fallback kalau ternyata kolomnya beda
                if (Schema::hasColumn($table, 'nama')) {
                    $s->orWhere('nama', 'like', "%{$q}%");
                }
                if (Schema::hasColumn($table, 'keterangan')) {
                    $s->orWhere('keterangan', 'like', "%{$q}%");
                }
            });
        }

        // ✅ TANPA PAGINATION: tampilkan semua
        $rubrik = $query->get();

        return view('jaminanmutu.rubrik.index', compact('rubrik', 'matakuliah', 'q', 'mkKode'));
    }

    // READ ONLY: detail rubrik
    public function show(Rubrik $rubrik)
    {
        return view('jaminanmutu.rubrik.show', compact('rubrik'));
    }
}
