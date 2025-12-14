<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;   // ✅ WAJIB: extends Controller
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenPembimbingController extends Controller
{
    // 🔵 HALAMAN LIST MAHASISWA BIMBINGAN
    public function index()
    {
        // Ambil ID dosen yang login
        $dosenId = Auth::user()->id_dosen;   // sama saja dengan auth()->user()

        // Ambil semua mahasiswa yang dibimbing dosen ini
        $mahasiswas = Mahasiswa::where('id_dosen', $dosenId)->get();

        return view('dosen.mahasiswa.index', compact('mahasiswas'));
    }

    // 🔵 HALAMAN DETAIL MAHASISWA
    // kalau mau pakai route model binding:
    // public function show(Mahasiswa $mahasiswa)
    public function show($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        return view('dosen.mahasiswa.show', compact('mahasiswa'));
    }
}
