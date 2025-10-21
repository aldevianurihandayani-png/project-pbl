<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /** LIST */
    public function index() {
        $mataKuliah = MataKuliah::with('dosen')->paginate(10);
        return view('admins.matakuliah.index', compact('mataKuliah'));
    }

    /** FORM CREATE */
    public function create()
    {
        // untuk select, enak pakai pluck 'name' => 'id'
        $dosen = Dosen::orderBy('name')->pluck('name', 'id');

        return view('admins.matakuliah.create', compact('dosen'));
    }

    /** SIMPAN BARU */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mk'  => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode_mk'],
            'nama_mk'  => ['required', 'string', 'max:150'],
            'sks'      => ['required', 'integer', 'min:1'],
            'semester' => ['required', 'integer', 'min:1'],
            'id_dosen' => ['required', 'exists:dosen,id'],
        ]);

        MataKuliah::create($validated);

        return redirect()
            ->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    /** FORM EDIT */
    public function edit(string $kode_mk)
    {
        // pakai where karena PK string (kecuali model sudah diset primaryKey)
        $mk    = MataKuliah::where('kode_mk', $kode_mk)->firstOrFail();
        $dosen = Dosen::orderBy('name')->pluck('name', 'id');

        return view('admins.matakuliah.edit', compact('mk', 'dosen'));
    }

    /** UPDATE */
    public function update(Request $request, string $kode_mk)
    {
        $mk = MataKuliah::where('kode_mk', $kode_mk)->firstOrFail();

        $validated = $request->validate([
            // umumnya kode_mk tidak diubah saat update. Kalau mau bisa:
            // 'kode_mk' => ['required','string','max:20', Rule::unique('mata_kuliah','kode_mk')->ignore($mk->kode_mk,'kode_mk')],
            'nama_mk'  => ['required', 'string', 'max:150'],
            'sks'      => ['required', 'integer', 'min:1'],
            'semester' => ['required', 'integer', 'min:1'],
            'id_dosen' => ['required', 'exists:dosen,id'],
        ]);

        $mk->update($validated);

        return redirect()
            ->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    /** HAPUS */
    public function destroy(string $kode_mk)
    {
        $mk = MataKuliah::where('kode_mk', $kode_mk)->firstOrFail();
        $mk->delete();

        return redirect()
            ->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
