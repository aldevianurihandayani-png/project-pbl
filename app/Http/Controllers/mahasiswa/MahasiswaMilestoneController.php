<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\ProyekPbl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaMilestoneController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $data = Milestone::with('proyek')
            ->when($q, fn ($query) => $query->where('deskripsi', 'like', "%{$q}%"))
            ->orderByDesc('id_milestone')
            ->paginate(10)
            ->withQueryString();

        return view('milestone.index', compact('data', 'q'));
    }

    public function create()
    {
        return view('milestone.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string|max:255',
            'tanggal'   => 'required|date',
            'status'    => 'nullable|boolean',
        ]);

        // pastikan boolean 0/1
        $validated['status'] = $request->boolean('status');

        // Coba cari proyek user (opsional). Jika tak ketemu, biarkan null.
        $user = Auth::user();
        $idProyek = ProyekPbl::query()
            ->whereHas('kelompok.mahasiswas', function ($q) use ($user) {
                $q->where('nim', $user->nim);  // sesuaikan jika pakai kolom lain
            })
            ->value('id_proyek_pbl');

        $validated['id_proyek_pbl'] = $idProyek ?: null;

        Milestone::create($validated);

        return redirect()
            ->route('mahasiswa.milestone.index')
            ->with('success', 'Milestone berhasil dibuat.');
    }

    public function show(Milestone $milestone)
    {
        $milestone->load('proyek');
        return view('milestone.show', compact('milestone'));
    }

    public function edit(Milestone $milestone)
    {
        return view('milestone.edit', compact('milestone'));
    }

    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string|max:255',
            'tanggal'   => 'required|date',
            'status'    => 'nullable|boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        $milestone->update($validated);

        return redirect()
            ->route('mahasiswa.milestone.index')
            ->with('success', 'Milestone diperbarui.');
    }

    public function destroy(Milestone $milestone)
    {
        $milestone->delete();

        return redirect()
            ->route('mahasiswa.milestone.index')
            ->with('success', 'Milestone dihapus.');
    }
}
