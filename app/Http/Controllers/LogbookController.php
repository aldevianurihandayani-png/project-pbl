<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogbookController extends Controller
{
    // Tampilkan form logbook
    public function create()
    {
        return view('logbook');
    }

    // Simpan data logbook ke database (dengan foto dokumentasi)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'aktivitas' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2 MB
        ]);

        $fotoPath = null;

        // Jika ada upload foto
        if ($request->hasFile('foto')) {
            // Simpan ke storage/app/public/logbook
            $fotoPath = $request->file('foto')->store('logbook', 'public');
        }

        // Simpan ke database (tabel: logbook)
        DB::table('logbook')->insert([
            'tanggal'     => $request->tanggal,
            'aktivitas'   => $request->aktivitas,
            'keterangan'  => $request->keterangan,
            'foto'        => $fotoPath, // simpan path foto kalau ada
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Redirect kembali ke form dengan pesan sukses
        return redirect()->route('logbook.create')->with('success', 'Logbook berhasil disimpan!');
    }
}
