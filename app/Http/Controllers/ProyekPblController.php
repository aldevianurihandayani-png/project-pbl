<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\ProyekPbl;
use Illuminate\Http\Request;

class ProyekPblController extends Controller
{
    /** LIST DATA */
    public function index(Request $request)
    {
        $q = $request->get('q');

        $data = ProyekPbl::query()
            ->when($q, fn ($qr) => $qr->where('judul', 'like', "%{$q}%"))
            ->orderByDesc('id_proyek_pbl')
            ->paginate(12)
            ->appends($request->only('q'));

        return view('koordinator.proyek_pbl.index', compact('data', 'q'));
    }

    /** FORM CREATE */
    public function create()
    {
        return view('koordinator.proyek_pbl.create');
    }

    /** SIMPAN DATA */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        ProyekPbl::create($validated);

        return redirect()
            ->route('koordinator.proyek-pbl.index')
            ->with('success', 'Proyek PBL berhasil dibuat.');
    }

    /** FORM EDIT */
    public function edit(ProyekPbl $proyek_pbl)
    {
        return view('koordinator.proyek_pbl.edit', [
            'proyek' => $proyek_pbl,
        ]);
    }

    /** UPDATE DATA */
    public function update(Request $request, ProyekPbl $proyek_pbl)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        $proyek_pbl->update($validated);

        return redirect()
            ->route('koordinator.proyek-pbl.index')
            ->with('success', 'Proyek PBL diperbarui.');
    }

    /** HAPUS DATA */
    public function destroy(ProyekPbl $proyek_pbl)
    {
        $proyek_pbl->delete();

        return redirect()
            ->route('koordinator.proyek-pbl.index')
            ->with('success', 'Proyek PBL dihapus.');
    }
}
