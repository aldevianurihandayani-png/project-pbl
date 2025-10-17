<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index() {
        $mataKuliah = MataKuliah::with('dosen')->paginate(10);
        return view('admins.matakuliah.index', compact('mataKuliah'));
    }

    public function create() {
        $dosen = Dosen::all();
        return view('admins.matakuliah.create', compact('dosen'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_mk' => 'required|unique:mata_kuliah',
            'nama_mk' => 'required',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'id_dosen' => 'required'
        ]);

        MataKuliah::create($request->all());
        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($kode_mk) {
        $mk = MataKuliah::findOrFail($kode_mk);
        $dosen = Dosen::all();
        return view('admins.matakuliah.edit', compact('mk', 'dosen'));
    }

    public function update(Request $request, $kode_mk) {
        $mk = MataKuliah::findOrFail($kode_mk);
        $request->validate([
            'nama_mk' => 'required',
            'sks' => 'required|integer',
            'semester' => 'required|integer',
            'id_dosen' => 'required'
        ]);
        $mk->update($request->all());
        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($kode_mk) {
        $mk = MataKuliah::findOrFail($kode_mk);
        $mk->delete();
        return redirect()->route('matakuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}