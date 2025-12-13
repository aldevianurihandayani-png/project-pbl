<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas');

        // ===================== ✅ TAMBAHAN: ambil filter overview =====================
        $filterKelas     = $request->query('filter_kelas');      // dari dropdown overview
        $filterAngkatan  = $request->query('filter_angkatan');   // ✅ FIX: dari dropdown angkatan
        $q               = $request->query('q');                 // dari input cari (nama/nim)

        $hasSearch = $request->filled('q') || $request->filled('filter_kelas') || $request->filled('filter_angkatan'); // ✅ FIX
        // ============================================================================

        // statistik per kelas (berdasarkan data mahasiswa)
        $kelasStats = Mahasiswa::select(
                'kelas',
                DB::raw('COUNT(*) as total'),
                DB::raw('MIN(angkatan) as min_angkatan'),
                DB::raw('MAX(angkatan) as max_angkatan')
            )
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get()
            ->keyBy('kelas');

        // 🔹 daftar kelas master dari tabel `kelas`
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        // data mahasiswa kalau user pilih 1 kelas
        $mahasiswas = null;

        if ($kelasFilter) {
            // ===================== MODE DETAIL PER KELAS =====================
            $mahasiswas = Mahasiswa::where('kelas', $kelasFilter)
                ->orderBy('nama')
                ->paginate(10)
                ->withQueryString();
        } else {
            // ===================== ✅ TAMBAHAN: MODE SEARCH DI OVERVIEW =====================
            if ($hasSearch) {
                $query = Mahasiswa::query();

                // filter kelas (overview)
                if (!empty($filterKelas)) {
                    $query->where('kelas', $filterKelas);
                }

                // ✅ FIX: filter angkatan (kolom yang benar di tabel mahasiswas)
                if (!empty($filterAngkatan)) {
                    $query->where('angkatan', $filterAngkatan);
                }

                // cari nama / nim
                if (!empty($q)) {
                    $query->where(function ($sub) use ($q) {
                        $sub->where('nama', 'like', "%{$q}%")
                            ->orWhere('nim', 'like', "%{$q}%");
                    });
                }

                $mahasiswas = $query
                    ->orderBy('kelas')
                    ->orderBy('nama')
                    ->paginate(10)
                    ->withQueryString();
            }
            // =======================================================================
        }

        return view('admins.mahasiswa.index', [
            'kelasStats'   => $kelasStats,
            'kelasFilter'  => $kelasFilter,
            'mahasiswas'   => $mahasiswas,
            'daftarKelas'  => $daftarKelas,

            // ===================== ✅ TAMBAHAN: flag supaya blade bisa nampilin hasil search =====================
            'hasSearch'    => $hasSearch,
            // ================================================================================================
        ]);
    }

    public function create(Request $request)
    {
        $kelas = $request->query('kelas');

        // gunakan get() agar object ->nama_kelas bisa dipakai di view
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.mahasiswa.create', compact('kelas', 'daftarKelas'));
    }

    public function store(Request $request)
    {
        // validasi harus pakai array string
        $daftarKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $data = $request->validate([
            'nim'      => 'required|string|max:50|unique:mahasiswas,nim',
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'angkatan' => 'nullable|digits:4',
            'no_hp'    => 'nullable|string|max:50',
            'kelas'    => ['required', Rule::in($daftarKelas)],
        ]);

        Mahasiswa::create($data);

        return redirect()
            ->route('admins.mahasiswa.index', ['kelas' => $data['kelas']])
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('admins.mahasiswa.edit', compact('mahasiswa', 'daftarKelas'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')
            ->pluck('nama_kelas')
            ->toArray();

        $data = $request->validate([
            'nim' => [
                'required',
                'string',
                'max:50',
                Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->nim, 'nim'),
            ],
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255',
            'angkatan' => 'nullable|digits:4',
            'no_hp'    => 'nullable|string|max:50',
            'kelas'    => ['required', Rule::in($daftarKelas)],
        ]);

        $mahasiswa->update($data);

        return redirect()
            ->route('admins.mahasiswa.index', ['kelas' => $data['kelas']])
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $kelas = $mahasiswa->kelas;
        $mahasiswa->delete();

        return redirect()
            ->route('admins.mahasiswa.index', ['kelas' => $kelas])
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
