<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /** ==================== LIST ==================== **/
    public function index()
    {
        $matakuliah = MataKuliah::with('dosen:id_dosen,nama_dosen')
            ->orderBy('kode_mk')
            ->paginate(10)
            ->withQueryString();

        return view('admins.matakuliah.index', compact('matakuliah'));
    }

    /** ==================== FORM CREATE ==================== **/
    public function create()
    {
        // kirim daftar dosen untuk datalist (opsional)
        $dosens = Dosen::orderBy('nama_dosen')->get(['id_dosen','nama_dosen']);
        return view('admins.matakuliah.create', compact('dosens'));
    }

    /** ==================== SIMPAN BARU ==================== **/
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'kode_mk'   => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode_mk'],
                'nama_mk'   => ['required', 'string', 'max:150'],
                'sks'       => ['required', 'integer', 'min:1'],
                'semester'  => ['required', 'integer', 'min:1'],

                // dosen diisi manual lewat nama (opsional)
                'nama_dosen' => ['nullable', 'string', 'max:150'],
                'jabatan'    => ['nullable', 'string', 'max:100'],
                'nip'        => ['nullable', 'string', 'max:50'],
                'no_telp'    => ['nullable', 'string', 'max:50'],
            ],
            [],
            [
                'kode_mk'   => 'Kode Mata Kuliah',
                'nama_mk'   => 'Nama Mata Kuliah',
                'sks'       => 'Jumlah SKS',
                'semester'  => 'Semester',
                'nama_dosen'=> 'Nama Dosen',
            ]
        );

        // Buat/ambil dosen jika nama diisi
        $dosenId = null;
        if (!empty($validated['nama_dosen'])) {
            $dosen = Dosen::updateOrCreate(
                ['nama_dosen' => trim($validated['nama_dosen'])],
                [
                    'jabatan' => $validated['jabatan'] ?? null,
                    'nip'     => $validated['nip'] ?? null,
                    'no_telp' => $validated['no_telp'] ?? null,
                ]
            );
            $dosenId = $dosen->id_dosen;
        }

        MataKuliah::create([
            'kode_mk'   => $validated['kode_mk'],
            'nama_mk'   => $validated['nama_mk'],
            'sks'       => $validated['sks'],
            'semester'  => $validated['semester'],
            'id_dosen'  => $dosenId, // boleh null
        ]);

        return redirect()->route('admins.matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    /** ==================== FORM EDIT ==================== **/
    public function edit(MataKuliah $matakuliah)
    {
        // kirim daftar dosen untuk datalist (opsional)
        $dosens = Dosen::orderBy('nama_dosen')->get(['id_dosen','nama_dosen']);
        return view('admins.matakuliah.edit', compact('matakuliah','dosens'));
    }

    /** ==================== UPDATE DATA ==================== **/
    public function update(Request $request, MataKuliah $matakuliah)
    {
        $validated = $request->validate(
            [
                // jika kamu juga mengizinkan ganti kode_mk, pakai rule unique ignore
                // 'kode_mk' => ['required','string','max:20', Rule::unique('mata_kuliah','kode_mk')->ignore($matakuliah->kode_mk,'kode_mk')],
                'nama_mk'   => ['required', 'string', 'max:150'],
                'sks'       => ['required', 'integer', 'min:1'],
                'semester'  => ['required', 'integer', 'min:1'],

                'nama_dosen' => ['nullable', 'string', 'max:150'],
                'jabatan'    => ['nullable', 'string', 'max:100'],
                'nip'        => ['nullable', 'string', 'max:50'],
                'no_telp'    => ['nullable', 'string', 'max:50'],
            ],
            [],
            [
                'nama_mk'   => 'Nama Mata Kuliah',
                'sks'       => 'Jumlah SKS',
                'semester'  => 'Semester',
                'nama_dosen'=> 'Nama Dosen',
            ]
        );

        // Default: pertahankan dosen lama
        $dosenId = $matakuliah->id_dosen;

        // Jika nama dosen diisi, upsert lalu ambil id barunya
        if (!empty($validated['nama_dosen'])) {
            $dosen = Dosen::updateOrCreate(
                ['nama_dosen' => trim($validated['nama_dosen'])],
                [
                    'jabatan' => $validated['jabatan'] ?? null,
                    'nip'     => $validated['nip'] ?? null,
                    'no_telp' => $validated['no_telp'] ?? null,
                ]
            );
            $dosenId = $dosen->id_dosen;
        }

        $matakuliah->update([
            // jika mengizinkan ganti kode_mk, tambahkan 'kode_mk' => $validated['kode_mk'],
            'nama_mk'  => $validated['nama_mk'],
            'sks'      => $validated['sks'],
            'semester' => $validated['semester'],
            'id_dosen' => $dosenId,
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
