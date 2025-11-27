<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Dosen; // <-- TAMBAHKAN INI
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /** ==================== LIST ==================== **/
    public function index()
    {
        $matakuliah = MataKuliah::orderBy('kode_mk')
            ->paginate(10)
            ->withQueryString();

        return view('admins.matakuliah.index', compact('matakuliah'));
    }

    /** ==================== FORM CREATE ==================== **/
    public function create()
    {
        // View create sekarang cuma butuh form, tidak perlu daftar dosen
        return view('admins.matakuliah.create');
    }

    /** ==================== SIMPAN BARU ==================== **/
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'kode_mk'    => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode_mk'],
                'nama_mk'    => ['required', 'string', 'max:150'],
                'sks'        => ['required', 'integer', 'min:1'],
                'semester'   => ['required', 'integer', 'min:1'],

                // ambil nama dosen dari input teks
                'nama_dosen' => ['required', 'string', 'max:150'],

                // field tambahan
                'jabatan'    => ['nullable', 'string', 'max:100'],
                'nip'        => ['nullable', 'string', 'max:50'],
                'no_telp'    => ['nullable', 'string', 'max:50'],
            ],
            [],
            [
                'kode_mk'    => 'Kode Mata Kuliah',
                'nama_mk'    => 'Nama Mata Kuliah',
                'sks'        => 'Jumlah SKS',
                'semester'   => 'Semester',
                'nama_dosen' => 'Dosen Pengampu',
            ]
        );

        MataKuliah::create([
            'kode_mk'    => $validated['kode_mk'],
            'nama_mk'    => $validated['nama_mk'],
            'sks'        => $validated['sks'],
            'semester'   => $validated['semester'],
            'nama_dosen' => $validated['nama_dosen'],
            'jabatan'    => $validated['jabatan'] ?? null,
            'nip'        => $validated['nip'] ?? null,
            'no_telp'    => $validated['no_telp'] ?? null,
            // kalau masih ada kolom id_dosen di tabel dan tidak dipakai lagi:
            'id_dosen'   => null,
        ]);

        return redirect()->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    /** ==================== FORM EDIT ==================== **/
    public function edit(MataKuliah $matakuliah)
    {
        // ambil daftar dosen untuk datalist di form edit
        $dosens = Dosen::orderBy('nama_dosen')->get();

        return view('admins.matakuliah.edit', compact('matakuliah', 'dosens'));
    }

    /** ==================== UPDATE DATA ==================== **/
    public function update(Request $request, MataKuliah $matakuliah)
    {
        $validated = $request->validate(
            [
                // kalau mau izinkan ubah kode_mk, buka komentar ini:
                // 'kode_mk' => [
                //     'required', 'string', 'max:20',
                //     Rule::unique('mata_kuliah', 'kode_mk')->ignore($matakuliah->kode_mk, 'kode_mk'),
                // ],

                'nama_mk'    => ['required', 'string', 'max:150'],
                'sks'        => ['required', 'integer', 'min:1'],
                'semester'   => ['required', 'integer', 'min:1'],
                'nama_dosen' => ['required', 'string', 'max:150'],

                'jabatan'    => ['nullable', 'string', 'max:100'],
                'nip'        => ['nullable', 'string', 'max:50'],
                'no_telp'    => ['nullable', 'string', 'max:50'],
            ],
            [],
            [
                'nama_mk'    => 'Nama Mata Kuliah',
                'sks'        => 'Jumlah SKS',
                'semester'   => 'Semester',
                'nama_dosen' => 'Dosen Pengampu',
            ]
        );

        $matakuliah->update([
            // kalau izinkan ubah kode_mk, tambahkan:
            // 'kode_mk'    => $validated['kode_mk'],
            'nama_mk'    => $validated['nama_mk'],
            'sks'        => $validated['sks'],
            'semester'   => $validated['semester'],
            'nama_dosen' => $validated['nama_dosen'],
            'jabatan'    => $validated['jabatan'] ?? null,
            'nip'        => $validated['nip'] ?? null,
            'no_telp'    => $validated['no_telp'] ?? null,
            'id_dosen'   => null,
        ]);

        return redirect()->route('admins.matakuliah.index')
            ->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    /** ==================== HAPUS ==================== **/
    public function destroy(MataKuliah $matakuliah)
    {
        $matakuliah->delete();

        return redirect()->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
