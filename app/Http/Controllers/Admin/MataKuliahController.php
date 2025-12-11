<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /** ==================== LIST PER KELAS ==================== **/
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas'); // ?kelas=Kelas A / Kelas B / dll

        // data tabel di bawah (detail per kelas)
        $matakuliahs = collect();
        if ($kelasFilter) {
            $matakuliahs = MataKuliah::where('kelas', $kelasFilter)
                ->orderBy('semester')
                ->orderBy('kode_mk')
                ->paginate(10)
                ->withQueryString();
        }

        // statistik jumlah MK per kelas (berdasarkan tabel mata_kuliah)
        $kelasStats = MataKuliah::select(
                'kelas',
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(semester) as min_semester'),
                DB::raw('MAX(semester) as max_semester')
            )
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get()
            ->keyBy('kelas'); // akses cepat: $kelasStats['Kelas A']

        // 🔹 daftar kelas master dari tabel `kelas`
        // dipakai untuk:
        // - kartu ringkasan per kelas
        // - dropdown filter kelas di view
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.matakuliah.index', [
            'matakuliahs'  => $matakuliahs,
            'kelasStats'   => $kelasStats,
            'kelasFilter'  => $kelasFilter,
            'daftarKelas'  => $daftarKelas,   // <= penting
        ]);
    }

    /** ==================== FORM CREATE ==================== **/
    public function create(Request $request)
    {
        // ambil kelas dari query kalau ada (?kelas=Kelas A)
        $kelasDefault = $request->query('kelas');

        // untuk dropdown di form: pakai collection model (bisa $row->nama_kelas di view)
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.matakuliah.create', compact('kelasDefault', 'daftarKelas'));
    }

    /** ==================== SIMPAN BARU ==================== **/
    public function store(Request $request)
    {
        // untuk validasi: ambil array nama_kelas saja
        $opsiKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $validated = $request->validate(
            [
                'kode_mk'    => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode_mk'],
                'nama_mk'    => ['required', 'string', 'max:150'],
                'sks'        => ['required', 'integer', 'min:1'],
                'semester'   => ['required', 'integer', 'min:1'],
                'kelas'      => ['required', 'string', Rule::in($opsiKelas)],

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
        // opsi kelas untuk dropdown (collection, biar di view bisa $row->nama_kelas)
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.matakuliah.edit', compact('matakuliah', 'daftarKelas'));
    }

    /** ==================== UPDATE DATA ==================== **/
    public function update(Request $request, MataKuliah $matakuliah)
    {
        // validasi tetap pakai array nama_kelas
        $opsiKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $validated = $request->validate(
            [
                'nama_mk'    => ['required', 'string', 'max:150'],
                'sks'        => ['required', 'integer', 'min:1'],
                'semester'   => ['required', 'integer', 'min:1'],
                'kelas'      => ['required', 'string', Rule::in($opsiKelas)],

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
