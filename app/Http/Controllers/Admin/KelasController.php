<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar semua kelas.
     */
    public function index()
    {
        // ambil semua kelas, urut nama_kelas
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        // ✅ PAKAI VIEW admins.kelas.index (bukan manage lagi)
        return view('admins.kelas.index', compact('daftarKelas'));
    }

    /**
     * Form tambah kelas baru.
     */
    public function create()
    {
        return view('admins.kelas.create');
    }

    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => [
                'required',
                'string',
                'max:50',
                'unique:kelas,nama_kelas',
            ],
            'semester'   => ['required', 'integer', 'min:1'],
            'periode'    => ['required', 'string', 'max:50'],
        ], [], [
            'nama_kelas' => 'Nama Kelas',
            'semester'   => 'Semester',
            'periode'    => 'Periode',
        ]);

        Kelas::create($data);

        return redirect()
            ->route('admins.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * (Opsional) Tampilkan detail satu kelas.
     * Kalau tidak dipakai, boleh abaikan / tidak dipanggil di route.
     */
    public function show(Kelas $kela)
    {
        // diarahkan saja ke halaman edit
        return redirect()->route('admins.kelas.edit', $kela->id);
    }

    /**
     * Form edit kelas.
     */
    public function edit(Kelas $kela)
    {
        // karena nama parameter route resource untuk "kelas" jadi {kela}
        $kelas = $kela;

        return view('admins.kelas.edit', compact('kelas'));
    }

    /**
     * Update data kelas.
     */
    public function update(Request $request, Kelas $kela)
    {
        $kelas = $kela;

        $data = $request->validate([
            'nama_kelas' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama_kelas')->ignore($kelas->id),
            ],
            'semester'   => ['required', 'integer', 'min:1'],
            'periode'    => ['required', 'string', 'max:50'],
        ], [], [
            'nama_kelas' => 'Nama Kelas',
            'semester'   => 'Semester',
            'periode'    => 'Periode',
        ]);

        $kelas->update($data);

        return redirect()
            ->route('admins.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Hapus kelas.
     */
    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()
            ->route('admins.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
