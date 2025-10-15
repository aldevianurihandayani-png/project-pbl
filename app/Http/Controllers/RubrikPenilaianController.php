<?php

namespace App\Http\Controllers;

use App\Models\RubrikPenilaian;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class RubrikPenilaianController extends Controller
{
    public function index()
    {
        $rubrik = RubrikPenilaian::with('mataKuliah')->paginate(10);
        return view('admin.rubrik.index', compact('rubrik'));
    }

    public function create()
    {
        $mataKuliah = MataKuliah::all();
        return view('admin.rubrik.create', compact('mataKuliah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_rubrik' => 'required|string|max:100',
            'kriteria'    => 'required|string',
            'bobot'       => 'required|numeric',
            'kode_mk'     => 'required|exists:mata_kuliah,kode_mk',
        ]);

        RubrikPenilaian::create($validated);
        return redirect()->route('rubrik.index')->with('success', 'Rubrik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rubrik = RubrikPenilaian::findOrFail($id);
        $mataKuliah = MataKuliah::all();
        return view('admin.rubrik.edit', compact('rubrik', 'mataKuliah'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_rubrik' => 'required|string|max:100',
            'kriteria'    => 'required|string',
            'bobot'       => 'required|numeric',
            'kode_mk'     => 'required|exists:mata_kuliah,kode_mk',
        ]);

        RubrikPenilaian::where('kode_rubrik', $id)->update($validated);
        return redirect()->route('rubrik.index')->with('success', 'Rubrik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        RubrikPenilaian::destroy($id);
        return redirect()->route('rubrik.index')->with('success', 'Rubrik berhasil dihapus.');
    }
}
