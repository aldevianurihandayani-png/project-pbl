<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
    public function index()
    {
        $logbooks = Logbook::latest()->paginate(10);
        return view('logbook.index', compact('logbooks'));
    }

    public function create()
    {
        return view('logbook.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'    => ['required','date'],
            'aktivitas'  => ['required','string','max:255'],
            'keterangan' => ['required','string'],
            'foto'       => ['nullable','image','mimes:jpg,jpeg,png','max:2048'], // 2MB
        ]);

        if ($request->hasFile('foto')) {
            // simpan ke storage/app/public/logbook
            $data['foto'] = $request->file('foto')->store('logbook', 'public');
        }

        Logbook::create($data);

        return redirect()->route('logbook.index')->with('success','Logbook berhasil disimpan.');
    }

    public function show(Logbook $logbook)
    {
        return view('logbook.show', compact('logbook'));
    }

    public function edit(Logbook $logbook)
    {
        return view('logbook.edit', compact('logbook'));
    }

    public function update(Request $request, Logbook $logbook)
    {
        $data = $request->validate([
            'tanggal'    => ['required','date'],
            'aktivitas'  => ['required','string','max:255'],
            'keterangan' => ['required','string'],
            'foto'       => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama (jika ada)
            if ($logbook->foto) {
                Storage::disk('public')->delete($logbook->foto);
            }
            $data['foto'] = $request->file('foto')->store('logbook', 'public');
        }

        $logbook->update($data);

        return redirect()->route('logbook.index')->with('success','Logbook berhasil diperbarui.');
    }

    public function destroy(Logbook $logbook)
    {
        if ($logbook->foto) {
            Storage::disk('public')->delete($logbook->foto);
        }
        $logbook->delete();

        return redirect()->route('logbook.index')->with('success','Logbook berhasil dihapus.');
    }
}
