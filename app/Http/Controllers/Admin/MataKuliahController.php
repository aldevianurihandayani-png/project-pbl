<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Dosen; // ✅ TAMBAHAN
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    /** ==================== LIST PER KELAS ==================== **/
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas'); // ?kelas=Kelas A / Kelas B / dll

        // ✅ deteksi mode pencarian (overview)
        $hasSearch = $request->filled('q') || $request->filled('filter_kelas') || $request->filled('filter_semester');

        $matakuliahs = collect();

        // MODE 2: DETAIL PER KELAS (tetap)
        if ($kelasFilter) {
            $matakuliahs = MataKuliah::where('kelas', $kelasFilter)
                ->orderBy('semester')
                ->orderBy('kode_mk')
                ->paginate(10)
                ->withQueryString();
        }

        // MODE 1: OVERVIEW + SEARCH RESULT
        if (!$kelasFilter && $hasSearch) {
            $query = MataKuliah::query();

            if ($request->filled('filter_kelas')) {
                $query->where('kelas', $request->filter_kelas);
            }

            if ($request->filled('filter_semester')) {
                $query->where('semester', $request->filter_semester);
            }

            if ($request->filled('q')) {
                $q = $request->q;
                $query->where(function ($w) use ($q) {
                    $w->where('kode_mk', 'like', "%{$q}%")
                      ->orWhere('nama_mk', 'like', "%{$q}%");
                });
            }

            $matakuliahs = $query
                ->orderBy('kelas')
                ->orderBy('semester')
                ->orderBy('kode_mk')
                ->paginate(12)
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
            ->keyBy('kelas');

        // daftar kelas master
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.matakuliah.index', [
            'matakuliahs'  => $matakuliahs,
            'kelasStats'   => $kelasStats,
            'kelasFilter'  => $kelasFilter,
            'daftarKelas'  => $daftarKelas,
            'hasSearch'    => $hasSearch,
        ]);
    }

    /** ==================== FORM CREATE ==================== **/
    public function create(Request $request)
    {
        $kelasDefault = $request->query('kelas');
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        // ✅ TAMBAHAN: data dosen untuk dropdown
        $dosens = Dosen::orderBy('nama_dosen')->get();

        return view('admins.matakuliah.create', compact('kelasDefault', 'daftarKelas', 'dosens'));
    }

    /** ==================== SIMPAN BARU ==================== **/
    public function store(Request $request)
    {
        $opsiKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $validated = $request->validate(
            [
                'kode_mk'   => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode_mk'],
                'nama_mk'   => ['required', 'string', 'max:150'],
                'sks'       => ['required', 'integer', 'min:1'],
                'semester'  => ['required', 'integer', 'min:1'],
                'kelas'     => ['required', 'string', Rule::in($opsiKelas)],

                // ✅ pilihan dosen dari dropdown
                'id_dosen'  => ['required', 'integer', 'exists:dosen,id_dosen'],
            ],
            [],
            [
                'kode_mk'   => 'Kode Mata Kuliah',
                'nama_mk'   => 'Nama Mata Kuliah',
                'sks'       => 'Jumlah SKS',
                'semester'  => 'Semester',
                'kelas'     => 'Kelas',
                'id_dosen'  => 'Dosen Pengampu',
            ]
        );

        MataKuliah::create([
            'kode_mk'   => strtoupper($validated['kode_mk']),
            'nama_mk'   => $validated['nama_mk'],
            'sks'       => $validated['sks'],
            'semester'  => $validated['semester'],
            'kelas'     => $validated['kelas'],
            'id_dosen'  => $validated['id_dosen'], // ✅ simpan id dosen
        ]);

        return redirect()->route('admins.matakuliah.index', ['kelas' => $validated['kelas']])
            ->with('success', 'Mata kuliah per kelas berhasil ditambahkan.');
    }

    /** ==================== FORM EDIT ==================== **/
    public function edit(MataKuliah $matakuliah)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        // ✅ TAMBAHAN: data dosen untuk dropdown
        $dosens = Dosen::orderBy('nama_dosen')->get();

        return view('admins.matakuliah.edit', compact('matakuliah', 'daftarKelas', 'dosens'));
    }

    /** ==================== UPDATE DATA ==================== **/
    public function update(Request $request, MataKuliah $matakuliah)
    {
        $opsiKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $validated = $request->validate(
            [
                'nama_mk'   => ['required', 'string', 'max:150'],
                'sks'       => ['required', 'integer', 'min:1'],
                'semester'  => ['required', 'integer', 'min:1'],
                'kelas'     => ['required', 'string', Rule::in($opsiKelas)],

                // ✅ pilihan dosen dari dropdown
                'id_dosen'  => ['required', 'integer', 'exists:dosen,id_dosen'],
            ],
            [],
            [
                'nama_mk'   => 'Nama Mata Kuliah',
                'sks'       => 'Jumlah SKS',
                'semester'  => 'Semester',
                'kelas'     => 'Kelas',
                'id_dosen'  => 'Dosen Pengampu',
            ]
        );

        $matakuliah->update([
            'nama_mk'   => $validated['nama_mk'],
            'sks'       => $validated['sks'],
            'semester'  => $validated['semester'],
            'kelas'     => $validated['kelas'],
            'id_dosen'  => $validated['id_dosen'], // ✅ update id dosen
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
