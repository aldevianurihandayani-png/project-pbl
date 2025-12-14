<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Feedback;              // 🔥 tambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   // 🔥 tambahkan

class DosenLogbookController extends Controller
{
    public function index()
    {
        // TODO: kalau mau, filter hanya logbook mahasiswa bimbingan dosen ini
        $logbooks = Logbook::all();

        return view('dosen.logbook.index', compact('logbooks'));
    }

    public function show(Logbook $logbook)
    {
        // 🔥 ambil semua feedback yang terkait logbook ini
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
     * Simpan / update nilai pembimbing (1–100) dari halaman detail logbook
     * + simpan komentar ke tabel feedback.
     */
    public function updateNilai(Request $request, Logbook $logbook)
    {
        $validated = $request->validate([
            'nilai'    => 'required|integer|min:1|max:100',
            'komentar' => 'nullable|string|max:1000',   // 🔥 komentar opsional
        ]);

        // simpan nilai ke logbook
        $logbook->nilai = $validated['nilai'];
        $logbook->save();

        // kalau ada komentar, simpan ke tabel feedback
        if (!empty($validated['komentar'])) {
            Feedback::create([
                'id_user'       => Auth::id(),        // dosen yang login
                'id_notifikasi' => $logbook->id,      // 🔥 asumsi: ini id_logbook
                'isi'           => $validated['komentar'],
                'status'        => 'baru',
                'tanggal'       => now(),
            ]);
        }

        // Balik lagi ke halaman detail logbook
        return redirect()
            ->route('dosen.logbook.show', $logbook->id)
            ->with('success', 'Nilai dan komentar logbook berhasil disimpan.');
    }
}
