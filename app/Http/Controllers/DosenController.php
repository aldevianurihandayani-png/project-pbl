<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    // Tampilkan daftar dosen + pencarian sederhana
    public function index(Request $request)
    {
        $q = $request->get('q');
        $dosen = Dosen::when($q, function ($query) use ($q) {
                $query->where('nama_dosen', 'like', "%{$q}%")
                      ->orWhere('nip', 'like', "%{$q}%")
                      ->orWhere('jabatan', 'like', "%{$q}%")
                      ->orWhere('no_telp', 'like', "%{$q}%");
            })
            ->orderBy('nama_dosen')
            ->paginate(10)
            ->appends(['q' => $q]);

        // ganti view sesuai foldermu, mis. 'admin.dosen.index'
        return view('dosen.index', compact('dosen', 'q'));
    }

    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_dosen' => 'required|string|max:150',
            'jabatan'    => 'nullable|string|max:100',
            'nip'        => 'nullable|integer',
            'no_telp'    => 'nullable|string|max:30',
        ]);

        Dosen::create($data);
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen) // route-model binding by primary key
    {
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $data = $request->validate([
            'nama_dosen' => 'required|string|max:150',
            'jabatan'    => 'nullable|string|max:100',
            'nip'        => 'nullable|integer',
            'no_telp'    => 'nullable|string|max:30',
        ]);

        $dosen->update($data);
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('dosen.index')->with('success', 'Dosen berhasil dihapus.');
    }
}
