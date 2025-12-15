<?php

namespace App\Http\Controllers\TPK;

use App\Http\Controllers\Controller;
use App\Models\TpkKelompok;
use App\Models\TpkMahasiswa;

class TPKController extends Controller
{
    public function index()
    {
        // paginate 10 per tabel, pakai page name beda biar tidak saling ganggu
        $dataKelompok = TpkKelompok::orderBy('id', 'desc')
            ->paginate(10, ['*'], 'kelompok_page');

        $dataMahasiswa = TpkMahasiswa::orderBy('id', 'desc')
            ->paginate(10, ['*'], 'mahasiswa_page');

        return view('tpk.index', compact('dataKelompok', 'dataMahasiswa'));
    }
}
