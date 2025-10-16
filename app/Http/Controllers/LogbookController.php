<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logbook; // pastikan model Logbook sudah ada

class LogbookController extends Controller
{
    /**
     * Tampilkan daftar logbook mahasiswa
     */
    public function index()
    {
        // Ambil data logbook terbaru dan paginate 10 per halaman
        $items = Logbook::latest()->paginate(10);

        // Kirim ke view mahasiswa/logbook.blade.php
        return view('mahasiswa.logbook', compact('items'));
    }

    /**
     * Form untuk membuat logbook baru (jika ada fitur tambah)
     */
    public function create()
    {
        return view('mahasiswa.logbook_create');
    }

    /**
     * Simpan data logbook baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'minggu' => 'required',
            'aktivitas' => 'required|string',
        ]);

        Logbook::create([
            'tanggal' => $request->tanggal,
            'minggu' => $request->minggu,
            'aktivitas' => $request->aktivitas,
            'status' => 'menunggu', // default status
            'lampiran_path' => $request->file('lampiran')
                ? $request->file('lampiran')->store('logbook')
                : null,
        ]);

        return redirect()->route('mhs.logbook.index')->with('success', 'Logbook berhasil ditambahkan.');
    }

    /**
     * Form edit logbook
     */
    public function edit(Logbook $logbook)
    {
        return view('mahasiswa.logbook_edit', compact('logbook'));
    }

    /**
     * Update data logbook
     */
    public function update(Request $request, Logbook $logbook)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'minggu' => 'required',
            'aktivitas' => 'required|string',
        ]);

        $logbook->update([
            'tanggal' => $request->tanggal,
            'minggu' => $request->minggu,
            'aktivitas' => $request->aktivitas,
        ]);

        return redirect()->route('mhs.logbook.index')->with('success', 'Logbook berhasil diperbarui.');
    }

    /**
     * Hapus logbook
     */
    public function destroy(Logbook $logbook)
    {
        $logbook->delete();
        return redirect()->route('mhs.logbook.index')->with('success', 'Logbook berhasil dihapus.');
    }

    /**
     * Download lampiran logbook
     */
    public function download(Logbook $logbook)
    {
        if ($logbook->lampiran_path && \Storage::exists($logbook->lampiran_path)) {
            return \Storage::download($logbook->lampiran_path);
        }

        return back()->with('error', 'Lampiran tidak ditemukan.');
    }
}
