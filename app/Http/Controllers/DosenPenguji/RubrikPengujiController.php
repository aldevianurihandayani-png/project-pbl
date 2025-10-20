<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use App\Models\Rubrik;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RubrikPengujiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $matakuliahOptions = MataKuliah::orderBy('nama_mk')->get();
        $selectedMk = $request->input('matakuliah');
        $search = $request->input('search');

        $query = Rubrik::with('mataKuliah')->orderBy('kode_mk')->orderBy('urutan');

        if ($selectedMk) {
            $query->where('kode_mk', $selectedMk);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_rubrik', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $rubriks = $query->paginate(10)->withQueryString();

        return view('dosenpenguji.rubrik.index', compact('rubriks', 'matakuliahOptions', 'selectedMk', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $matakuliahOptions = MataKuliah::orderBy('nama_mk')->get();
        return view('dosenpenguji.rubrik.create', compact('matakuliahOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'exists:mata_kuliah,kode_mk'],
            'nama_rubrik' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rubriks')->where(fn ($query) => $query->where('kode_mk', $request->kode_mk))
            ],
            'bobot' => ['required', 'integer', 'min:0', 'max:100'],
            'urutan' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Rubrik::create($validated);

        return redirect()->route('rubrik.index')->with('success', 'Rubrik berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rubrik $rubrik)
    {
        $matakuliahOptions = MataKuliah::orderBy('nama_mk')->get();
        return view('dosenpenguji.rubrik.edit', compact('rubrik', 'matakuliahOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rubrik $rubrik)
    {
        $validated = $request->validate([
            'kode_mk' => ['required', 'string', 'exists:mata_kuliah,kode_mk'],
            'nama_rubrik' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rubriks')->where(fn ($query) => $query->where('kode_mk', $request->kode_mk))->ignore($rubrik->id)
            ],
            'bobot' => ['required', 'integer', 'min:0', 'max:100'],
            'urutan' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $rubrik->update($validated);

        return redirect()->route('rubrik.index')->with('success', 'Rubrik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubrik $rubrik)
    {
        $rubrik->delete();
        return redirect()->route('rubrik.index')->with('success', 'Rubrik berhasil dihapus.');
    }
}
