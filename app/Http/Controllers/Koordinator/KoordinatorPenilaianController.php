<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\MataKuliah;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KoordinatorPenilaianController extends Controller
{
    public function index(Request $request)
    {
        // dropdown MK dari DB
        $matakuliah = MataKuliah::orderBy('nama_mk')->get();

        // dropdown kelas dari DB (karena penilaians pakai kelas_id)
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $mkKode  = $request->query('matakuliah'); // isinya kode_mk
        $kelasId = $request->query('kelas');      // isinya id kelas (kelas_id)

        $query = Penilaian::with(['mahasiswa', 'matakuliah', 'kelas', 'dosen'])
            ->latest();

        // filter MK sesuai kolom DB: matakuliah_kode
        if (!empty($mkKode)) {
            $query->where('matakuliah_kode', $mkKode);
        }

        // filter kelas sesuai kolom DB: kelas_id
        if (!empty($kelasId)) {
            $query->where('kelas_id', $kelasId);
        }

        $penilaian = $query->paginate(10)->withQueryString();

        // PENTING: view kamu ada di koordinator/penilaian/index.blade.php
        return view('koordinator.penilaian.index', compact(
            'penilaian',
            'matakuliah',
            'kelasList',
            'mkKode',
            'kelasId'
        ));
    }

    public function show(Penilaian $penilaian)
    {
        $penilaian->load(['mahasiswa', 'matakuliah', 'kelas', 'dosen']);
        return view('koordinator.penilaian.show', compact('penilaian'));
    }
}
