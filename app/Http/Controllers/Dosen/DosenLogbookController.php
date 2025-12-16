<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenLogbookController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::all();
        return view('dosen.logbook.index', compact('logbooks'));
    }

    public function show(Logbook $logbook)
    {
        $feedback = Feedback::where('id_notifikasi', $logbook->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('dosen.logbook.show', compact('logbook', 'feedback'));
    }

    public function edit(Logbook $logbook)
    {
        return view('dosen.logbook.edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        $logbook->update($request->all());
        return back()->with('success', 'Logbook diperbarui');
    }

    public function destroy(Logbook $logbook)
    {
        $logbook->delete();
        return back()->with('success', 'Logbook dihapus');
    }

    public function toggleStatus(Logbook $logbook)
    {
        $logbook->status = !$logbook->status;
        $logbook->save();

        return back()->with('success', 'Status logbook diperbarui');
    }

    /**
     * Update nilai pembimbing + komentar (opsional)
     */
    public function updateNilai(Request $request, Logbook $logbook)
    {
        $validated = $request->validate([
            'nilai'    => 'required|integer|min:1|max:100',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $logbook->nilai = $validated['nilai'];
        $logbook->save();

        if (!empty($validated['komentar'])) {
            Feedback::create([
                'id_user'       => Auth::id(),
                'id_notifikasi' => $logbook->id,
                'isi'           => $validated['komentar'],
                'status'        => 'baru',
                'tanggal'       => now(),
            ]);
        }

        return redirect()
            ->route('dosen.logbook.show', $logbook->id)
            ->with('success', 'Nilai dan komentar berhasil disimpan.');
    }

    /**
     * 🔥 TAMBAH KOMENTAR SAJA (FORM "Tambahkan komentar...")
     */
    public function storeKomentar(Request $request, Logbook $logbook)
    {
        $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'id_user'       => Auth::id(),
            'id_notifikasi' => $logbook->id,
            'isi'           => $request->komentar,
            'status'        => 'baru',
            'tanggal'       => now(),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
