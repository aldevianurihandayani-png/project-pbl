<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\ProyekPbl;
use Illuminate\Http\Request;

class ProyekPblController extends Controller
{
    public function index()
    {
        $items = ProyekPbl::orderByDesc('id_proyek_pbl')->paginate(10);
        return view('koordinator.proyek_pbl.index', compact('items'));
    }

    public function create()
    {
        return view('koordinator.proyek_pbl.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'tanggal'     => ['nullable', 'date'],
            'kode_mk'     => ['required', 'integer'],
            'id_dosen'    => ['required', 'integer'],
            'id_kelompok' => ['required', 'integer'],
        ]);

        ProyekPbl::create($data);

        return redirect()
            ->route('koordinator.proyek-pbl.index')
            ->with('success', 'Proyek PBL berhasil ditambahkan.');
    }

    public function show(ProyekPbl $proyek_pbl)
    {
        return view('koordinator.proyek_pbl.show', ['item' => $proyek_pbl]);
    }

    public function edit(ProyekPbl $proyek_pbl)
    {
        return view('koordinator.proyek_pbl.edit', ['item' => $proyek_pbl]);
    }

    public function update(Request $request, ProyekPbl $proyek_pbl)
    {
        $data = $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'tanggal'     => ['nullable', 'date'],
            'kode_mk'     => ['required', 'integer'],
            'id_dosen'    => ['required', 'integer'],
            'id_kelompok' => ['required', 'integer'],
        ]);

        $proyek_pbl->update($data);

        return redirect()
            ->route('koordinator.proyek-pbl.index')
            ->with('success', 'Proyek PBL berhasil diupdate.');
    }

    public function destroy(ProyekPbl $proyek_pbl)
    {
        $proyek_pbl->delete();

        return redirect()
            ->route('koordinator.proyek-pbl.index')
            ->with('success', 'Proyek PBL berhasil dihapus.');
    }
}
