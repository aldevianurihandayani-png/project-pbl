<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;

class DosenLogbookController extends Controller
{
    public function index()
    {
        // Ambil logbook mahasiswa bimbingan (contoh)
        $logbooks = Logbook::all();

        return view('dosen.logbook.index', compact('logbooks'));
    }

    public function show(Logbook $logbook)
    {
        return view('dosen.logbook.show', compact('logbook'));
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
}
