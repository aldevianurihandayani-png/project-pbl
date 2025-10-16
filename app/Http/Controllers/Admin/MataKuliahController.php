<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $matakuliah = MataKuliah::with('dosen')->latest()->paginate(10);
        return view('admins.matakuliah.index', compact('matakuliah'));
    }

    public function create()
    {
        $dosens = User::where('role', 'dosen')->get();
        return view('admins.matakuliah.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'nama_dosen' => 'required|string|exists:users,name', // Validate name exists
        ]);

        // Find user by name
        $dosen = User::where('name', $request->nama_dosen)->firstOrFail();

        // Merge the found id_dosen into the request
        $data = $request->except('nama_dosen');
        $data['id_dosen'] = $dosen->id;

        MataKuliah::create($data);

        return redirect()->route('admins.matakuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $matakuliah)
    {
        $dosens = User::where('role', 'dosen')->get();
        return view('admins.matakuliah.edit', compact('matakuliah', 'dosens'));
    }

    public function update(Request $request, MataKuliah $matakuliah)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliah,kode_mk,' . $matakuliah->kode_mk . ',kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'nama_dosen' => 'required|string|exists:users,name', // Validate name exists
        ]);

        // Find user by name
        $dosen = User::where('name', $request->nama_dosen)->firstOrFail();

        // Merge the found id_dosen into the request
        $data = $request->except('nama_dosen');
        $data['id_dosen'] = $dosen->id;

        $matakuliah->update($data);

        return redirect()->route('admins.matakuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $matakuliah)
    {
        $matakuliah->delete();
        return redirect()->route('admins.matakuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
