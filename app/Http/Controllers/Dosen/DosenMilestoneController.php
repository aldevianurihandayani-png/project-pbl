<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DosenMilestoneController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->toString();
        $status = $request->string('status')->toString(); // menunggu|disetujui|ditolak

        $milestones = Milestone::query()
            // sesuaikan relasi kalau ada:
            // ->with(['mahasiswa', 'kelompok', 'proyek'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('deskripsi', 'like', "%{$q}%");
                });
            })
            ->when(in_array($status, ['menunggu', 'disetujui', 'ditolak'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('dosen.milestone.index', compact('milestones', 'q', 'status'));
    }

    public function edit(Milestone $milestone): View
    {
        return view('dosen.milestone.edit', compact('milestone'));
    }

    public function update(Request $request, Milestone $milestone): RedirectResponse
    {
        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal'   => ['required', 'date'],
            // kalau ada field lain, tambahkan di sini
            // 'link'   => ['nullable','url'],
            // 'nilai'  => ['nullable','numeric','min:0','max:100'],
        ]);

        $milestone->update($data);

        return redirect()
            ->route('dosen.milestone.index')
            ->with('success', 'Milestone berhasil diperbarui.');
    }

    public function approve(Milestone $milestone): RedirectResponse
    {
        // opsional: cegah approve kalau sudah bukan menunggu
        if ($milestone->status !== 'menunggu') {
            return back()->with('error', 'Milestone sudah diproses.');
        }

        $milestone->update([
            'status' => 'disetujui',
        ]);

        return back()->with('success', 'Milestone berhasil disetujui.');
    }

    public function reject(Request $request, Milestone $milestone): RedirectResponse
    {
        // opsional: bisa terima alasan ditolak
        // $request->validate(['alasan' => ['nullable','string','max:500']]);

        if ($milestone->status !== 'menunggu') {
            return back()->with('error', 'Milestone sudah diproses.');
        }

        $milestone->update([
            'status' => 'ditolak',
            // 'catatan_dosen' => $request->alasan ?? null, // kalau kamu punya kolom ini
        ]);

        return back()->with('success', 'Milestone berhasil ditolak.');
    }
    public function show(Milestone $milestone)
{
    // kalau ada relasi, boleh tambahin ->load()
    // $milestone->load(['mahasiswa','kelompok']);

    return view('dosen.milestone.show', compact('milestone'));
}

}
