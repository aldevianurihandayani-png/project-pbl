<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    // Tampilkan semua data
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        if ($search = $request->get('search')) {
            $query->where('nim', 'like', "%$search%")
                  ->orWhere('nama', 'like', "%$search%")
                  ->orWhere('angkatan', 'like', "%$search%")
                  ->orWhere('no_hp', 'like', "%$search%");
        }

        $mahasiswa = $query->orderBy('angkatan', 'desc')->get();
        return view('mahasiswa.index', compact('mahasiswa', 'search'));
    }

    // Form tambah
    public function create()
    {
        return view('mahasiswa.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:15|unique:mahasiswa,nim',
            'nama' => 'required|string|max:100',
            'angkatan' => 'required|digits:4',
            'no_hp' => 'nullable|string|max:15',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    // Form edit
    public function edit($nim)
    {
        $mhs = Mahasiswa::findOrFail($nim);
        return view('mahasiswa.edit', compact('mhs'));
    }

    // Update data
    public function update(Request $request, $nim)
    {
        $mhs = Mahasiswa::findOrFail($nim);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'angkatan' => 'required|digits:4',
            'no_hp' => 'nullable|string|max:15',
        ]);

        $mhs->update($validated);

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($nim)
    {
        $mhs = Mahasiswa::findOrFail($nim);
        $mhs->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
