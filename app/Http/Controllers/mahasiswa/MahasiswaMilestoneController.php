<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Models\ProyekPbl;
use App\Models\Notification;
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

        // pastikan boolean
        $validated['status'] = $request->boolean('status');

        // cari proyek mahasiswa
        $user = Auth::user();
        $proyek = ProyekPbl::query()
            ->whereHas('kelompok.mahasiswas', function ($q) use ($user) {
                $q->where('nim', $user->nim);
            })
            ->first();

        $validated['id_proyek_pbl'] = $proyek->id_proyek_pbl ?? null;

        // simpan milestone
        $milestone = Milestone::create($validated);

        // 🔔 NOTIF GLOBAL — muncul di semua role
        Notification::create([
            'user_id' => null,
            'judul'   => 'Milestone baru dibuat',
            'pesan'   => 'Mahasiswa ' . ($user->nama ?? $user->name)
                        . ' membuat milestone "' . $milestone->deskripsi
                        . '" dan menunggu persetujuan.',
            'is_read' => 0,
        ]);

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

        // status baru
        $newStatus = $request->boolean('status');

        // status lama
        $oldStatus = $milestone->status;

        $validated['status'] = $newStatus;

        // update
        $milestone->update($validated);

        // 🔔 NOTIF GLOBAL jika milestone berubah menjadi disetujui
        if (!$oldStatus && $newStatus) {

            $user = Auth::user();

            Notification::create([
                'user_id' => null,
                'judul'   => 'Milestone disetujui',
                'pesan'   => 'Milestone "' . $milestone->deskripsi
                            . '" milik ' . ($user->nama ?? $user->name)
                            . ' telah disetujui.',
                'is_read' => 0,
            ]);
        }

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
