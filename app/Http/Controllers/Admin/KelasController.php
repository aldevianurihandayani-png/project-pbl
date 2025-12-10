<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        // kalau mau, bisa dipakai untuk halaman khusus; tapi untuk kasusmu,
        // kita lebih pakai route store/update/destroy dari halaman lain.
        $kelas = Kelas::orderBy('nama')->get();
        return view('admins.kelas.index', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'kode'       => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $kelas = Kelas::create($request->only('nama', 'kode', 'keterangan'));

        // Kalau dipanggil dari halaman lain, bisa redirect balik ke URL sebelumnya
        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'kode'       => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $kela->update($request->only('nama', 'kode', 'keterangan'));

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->mahasiswas()->exists()) {
            return back()->with('error', 'Kelas masih digunakan oleh mahasiswa.');
        }

        $kela->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}