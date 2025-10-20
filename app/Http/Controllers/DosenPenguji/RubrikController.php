<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use App\Models\Rubrik;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class RubrikController extends Controller
{
    public function index(Request $request)
    {
        $mk = $request->query('matakuliah');

        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);

        $rubriks = collect();
        if ($mk) {
            $rubriks = Rubrik::where('kode_mk', $mk)
                        ->orderBy('urutan')
                        ->paginate(10)
                        ->withQueryString();
        }

        return view('dosenpenguji.rubrik', compact('matakuliah','rubriks','mk'));
    }

    public function create()
    {
        $matakuliahOptions = MataKuliah::orderBy('nama_mk')->get();
        return view('dosenpenguji.rubrik.create', compact('matakuliahOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20',
            'nama_rubrik' => 'required|string|max:255',
            'bobot' => 'required|integer|min:0|max:100',
            'urutan' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Rubrik::create($request->all());

        return redirect()->route('dosenpenguji.rubrik.index')
                         ->with('success', 'Rubrik berhasil ditambahkan.');
    }

    public function edit(Rubrik $rubrik)
    {
        $matakuliahOptions = MataKuliah::orderBy('nama_mk')->get();
        return view('dosenpenguji.rubrik.edit', compact('rubrik', 'matakuliahOptions'));
    }

    public function update(Request $request, Rubrik $rubrik)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20',
            'nama_rubrik' => 'required|string|max:255',
            'bobot' => 'required|integer|min:0|max:100',
            'urutan' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $rubrik->update($request->all());

        return redirect()->route('dosenpenguji.rubrik.index')
                         ->with('success', 'Rubrik berhasil diperbarui.');
    }

    public function destroy(Rubrik $rubrik)
    {
        $rubrik->delete();

        return redirect()->route('dosenpenguji.rubrik.index')
                         ->with('success', 'Rubrik berhasil dihapus.');
    }
}
