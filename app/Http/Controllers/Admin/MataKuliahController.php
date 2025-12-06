<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /** ==================== LIST PER KELAS ==================== **/
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas'); // ?kelas=A

        // kalau sedang lihat detail satu kelas
        $matakuliahs = collect();
        if ($kelasFilter) {
            $matakuliahs = MataKuliah::where('kelas', $kelasFilter)
                ->orderBy('semester')
                ->orderBy('kode_mk')
                ->paginate(10)
                ->withQueryString();
        }

        // statistik jumlah MK per kelas
        $kelasStats = MataKuliah::select(
                'kelas',
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(semester) as min_semester'),
                DB::raw('MAX(semester) as max_semester')
            )
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get()
            ->keyBy('kelas'); // akses cepat: $kelasStats['A']

        return view('admins.matakuliah.index', compact('matakuliahs', 'kelasStats', 'kelasFilter'));
    }

    /** ==================== FORM CREATE ==================== **/
    public function create(Request $request)
    {
        // ambil kelas dari query kalau ada (?kelas=A)
        $kelasDefault = $request->query('kelas');

        return view('admins.matakuliah.create', compact('kelasDefault'));
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
                'kelas'      => ['required', 'string', Rule::in(['A','B','C','D','E'])],

                'nama_dosen' => ['nullable', 'string', 'max:150'],
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
                'kelas'      => 'Kelas',
                'nama_dosen' => 'Dosen Pengampu',
            ]
        );

        MataKuliah::create([
            'kode_mk'    => strtoupper($validated['kode_mk']),
            'nama_mk'    => $validated['nama_mk'],
            'sks'        => $validated['sks'],
            'semester'   => $validated['semester'],
            'kelas'      => $validated['kelas'],
            'nama_dosen' => $validated['nama_dosen'] ?? null,
            'jabatan'    => $validated['jabatan'] ?? null,
            'nip'        => $validated['nip'] ?? null,
            'no_telp'    => $validated['no_telp'] ?? null,
            'id_dosen'   => null,
        ]);

        return redirect()->route('admins.matakuliah.index', ['kelas' => $validated['kelas']])
            ->with('success', 'Mata kuliah per kelas berhasil ditambahkan.');
    }

    /** ==================== FORM EDIT ==================== **/
    public function edit(MataKuliah $matakuliah)
    {
        return view('admins.matakuliah.edit', compact('matakuliah'));
    }

    /** ==================== UPDATE DATA ==================== **/
    public function update(Request $request, MataKuliah $matakuliah)
    {
        $validated = $request->validate(
            [
                'nama_mk'    => ['required', 'string', 'max:150'],
                'sks'        => ['required', 'integer', 'min:1'],
                'semester'   => ['required', 'integer', 'min:1'],
                'kelas'      => ['required', 'string', Rule::in(['A','B','C','D','E'])],

                'nama_dosen' => ['nullable', 'string', 'max:150'],
                'jabatan'    => ['nullable', 'string', 'max:100'],
                'nip'        => ['nullable', 'string', 'max:50'],
                'no_telp'    => ['nullable', 'string', 'max:50'],
            ],
            [],
            [
                'nama_mk'    => 'Nama Mata Kuliah',
                'sks'        => 'Jumlah SKS',
                'semester'   => 'Semester',
                'kelas'      => 'Kelas',
                'nama_dosen' => 'Dosen Pengampu',
            ]
        );

        $matakuliah->update([
            'nama_mk'    => $validated['nama_mk'],
            'sks'        => $validated['sks'],
            'semester'   => $validated['semester'],
            'kelas'      => $validated['kelas'],
            'nama_dosen' => $validated['nama_dosen'] ?? null,
            'jabatan'    => $validated['jabatan'] ?? null,
            'nip'        => $validated['nip'] ?? null,
            'no_telp'    => $validated['no_telp'] ?? null,
        ]);

        return redirect()->route('admins.matakuliah.index', ['kelas' => $validated['kelas']])
            ->with('success', 'Data mata kuliah per kelas berhasil diperbarui.');
    }

    /** ==================== HAPUS ==================== **/
    public function destroy(MataKuliah $matakuliah)
    {
        $kelas = $matakuliah->kelas;
        $matakuliah->delete();

        return redirect()->route('admins.matakuliah.index', ['kelas' => $kelas])
            ->with('success', 'Mata kuliah per kelas berhasil dihapus.');
    }
}
