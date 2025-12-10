<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peringkat;
use App\Models\Mahasiswa;

class PeringkatController extends Controller
{
    public function index()
    {
        $peringkats = Peringkat::with('mahasiswa')
            ->orderBy('mata_kuliah')
            ->orderBy('peringkat')
            ->paginate(10);

        return view('koordinator.peringkat.index', compact('peringkats'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $peringkat = new Peringkat;

        return view('koordinator.peringkat.create', compact('mahasiswas', 'peringkat'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswas,nim',
            'mata_kuliah'   => 'required|string|max:100',
            'nilai_total'   => 'required|numeric|min:0|max:100',
            'peringkat'     => 'required|integer|min:1',
            'semester'      => 'nullable|string|max:30',
            'tahun_ajaran'  => 'nullable|string|max:20',
        ]);

        Peringkat::create($data);

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data peringkat berhasil ditambahkan.');
    }

    public function edit(Peringkat $peringkat)
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();

        return view('koordinator.peringkat.edit', compact('peringkat', 'mahasiswas'));
    }

    public function update(Request $request, Peringkat $peringkat)
    {
        $data = $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswas,nim',
            'mata_kuliah'   => 'required|string|max:100',
            'nilai_total'   => 'required|numeric|min:0|max:100',
            'peringkat'     => 'required|integer|min:1',
            'semester'      => 'nullable|string|max:30',
            'tahun_ajaran'  => 'nullable|string|max:20',
        ]);

        $peringkat->update($data);

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data peringkat berhasil diperbarui.');
    }

    public function destroy(Peringkat $peringkat)
    {
        $peringkat->delete();

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data peringkat berhasil dihapus.');
    }
}
