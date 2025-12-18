<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    public function index(Request $request)
    {
        $kelasAktif = $request->get('kelas'); // ini string nama kelas (contoh: "Kelas A")

        // list kelas untuk filter (alias nama_kelas -> nama)
        $kelasList = DB::table('kelas')
            ->select('nama_kelas as nama')
            ->orderBy('nama_kelas')
            ->get();

        // query base peringkat
        $base = DB::table('peringkats')
            ->select('nama_tpk', 'kelas', 'nilai_total', 'peringkat', 'jenis')
            ->when($kelasAktif, function ($q) use ($kelasAktif) {
                $q->where('kelas', $kelasAktif);
            });

        // pisah kelompok & mahasiswa
        $peringkatKelompok = (clone $base)
            ->where('jenis', 'kelompok')
            ->orderBy('peringkat')
            ->get();

        $peringkatMahasiswa = (clone $base)
            ->where('jenis', 'mahasiswa')
            ->orderBy('peringkat')
            ->get();

        return view('hasil.index', compact(
            'kelasList',
            'kelasAktif',
            'peringkatKelompok',
            'peringkatMahasiswa'
        ));
    }
}
