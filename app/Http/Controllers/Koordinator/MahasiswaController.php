<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    /**
     * =========================================================
     * LIST DATA MAHASISWA (READ ONLY – KOORDINATOR)
     * Disesuaikan dari Admin\MahasiswaController@index
     * =========================================================
     */
    public function index(Request $request)
    {
        $kelasFilter = $request->query('kelas');

        // ===== Filter overview (SAMA seperti ADMIN) =====
        $filterKelas     = $request->query('filter_kelas');
        $filterAngkatan  = $request->query('filter_angkatan');
        $q               = $request->query('q');

        $hasSearch = $request->filled('q')
            || $request->filled('filter_kelas')
            || $request->filled('filter_angkatan');
        // ===============================================

        // ===== Statistik per kelas (SAMA seperti ADMIN) =====
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

        // ===== Master kelas (READ ONLY) =====
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        $mahasiswas   = null;
        $isPaginated  = false;

        if ($kelasFilter) {
            /**
             * MODE DETAIL PER KELAS
             * (tanpa pagination, sama seperti admin)
             */
            $mahasiswas = Mahasiswa::where('kelas', $kelasFilter)
                ->orderBy('nama')
                ->get();

            $isPaginated = false;
        } else {
            /**
             * MODE SEARCH / OVERVIEW
             * (pagination 10, sama seperti admin)
             */
            if ($hasSearch) {
                $query = Mahasiswa::query();

                if (!empty($filterKelas)) {
                    $query->where('kelas', $filterKelas);
                }

                if (!empty($filterAngkatan)) {
                    $query->where('angkatan', $filterAngkatan);
                }

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

                $isPaginated = true;
            }
        }

        return view('koordinator.mahasiswa.index', [
            'kelasStats'  => $kelasStats,
            'kelasFilter' => $kelasFilter,
            'mahasiswas'  => $mahasiswas,
            'daftarKelas' => $daftarKelas,
            'hasSearch'   => $hasSearch,
            'isPaginated' => $isPaginated,
        ]);
    }

    /**
     * =========================================================
     * DETAIL MAHASISWA (READ ONLY)
     * =========================================================
     */
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load([
            'kelompok', // relasi aman
        ]);

        return view('koordinator.mahasiswa.show', [
            'mahasiswa' => $mahasiswa,
        ]);
    }
}
