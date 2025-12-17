<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\ProyekPbl;
use Illuminate\Http\Request;

class ProyekPblController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $data = ProyekPbl::query()
            ->when($q, fn ($qr) => $qr->where('judul', 'like', "%{$q}%"))
            ->orderByDesc('id_proyek_pbl')
            ->paginate(10)
            ->appends($request->only('q'));

        return view('koordinator.proyek_pbl.index', compact('data', 'q'));
    }

    public function create()
    {
        return view('koordinator.proyek_pbl.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
        ]);

        ProyekPbl::create($data);

        return redirect()
            ->route('koordinator.proyek-pbl.index')
            ->with('success', 'Proyek PBL berhasil ditambahkan.');
    }

    public function edit(ProyekPbl $proyek_pbl)
    {
        return view('koordinator.proyek_pbl.edit', ['item' => $proyek_pbl]);
    }

    public function update(Request $request, ProyekPbl $proyek_pbl)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
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
