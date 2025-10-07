<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelompokController extends Controller
{
    // Method untuk halaman kelompok dosen pembimbing
    public function index()
    {
        // Data dummy sementara — nanti bisa diganti ambil dari database
        $kelompok = [
            [
                'nama' => 'Kelompok 1',
                'anggota' => ['Ani', 'Budi', 'Cici'],
                'dosen' => 'Dr. Andi',
            ],
            [
                'nama' => 'Kelompok 2',
                'anggota' => ['Deni', 'Eka', 'Fajar'],
                'dosen' => 'Ibu Sari',
            ],
            [
                'nama' => 'Kelompok 3',
                'anggota' => ['Gina', 'Hadi', 'Indra'],
                'dosen' => 'Pak Rafi',
            ],
        ];

        // kirim ke view dosen/kelompok.blade.php
        return view('dosen.kelompok', compact('kelompok'));
    }
}
