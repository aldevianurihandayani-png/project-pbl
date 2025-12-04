<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Milestone;

class DosenMilestoneController extends Controller
{
    public function index(Request $request)
    {
        $query = Milestone::query();

        // filter cari deskripsi
        if ($request->filled('search')) {
            $query->where('deskripsi', 'like', '%'.$request->search.'%');
        }

        // urutkan terbaru dulu
        $milestones = $query->orderByDesc('tanggal')->paginate(10);

        return view('dosen.milestone.table', compact('milestones'));
    }

    public function edit(Milestone $milestone)
    {
        return view('dosen.milestone.edit', compact('milestone'));
    }

    public function update(Request $request, Milestone $milestone)
    {
        $request->validate([
            'status' => 'required|in:Belum,Sedang,Berhasil', // sesuaikan dengan enum/status di DB
        ]);

        $milestone->status = $request->status;
        $milestone->save();

        return redirect()
            ->route('dosen.milestone.index')
            ->with('success', 'Status milestone berhasil diperbarui.');
    }
}
