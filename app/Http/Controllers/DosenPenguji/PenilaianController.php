<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// ====== YANG SUDAH ADA (dipertahankan) ======
use App\Models\MataKuliah;
use App\Models\Rubrik; // master rubrik

// ====== TAMBAHAN (sesuai permintaanmu) ======
use App\Models\{Penilaian, Mahasiswa};
use Illuminate\Support\Facades\Auth;
use App\Exports\PenilaianExport;
use App\Imports\PenilaianImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PenilaianController extends Controller
{
    /* ===== Helpers: deteksi nama tabel/kolom dinamis ===== */

    /** Nama tabel mahasiswa: mahasiswas | mahasiswa */
    private function studentTable(): ?string
    {
        if (Schema::hasTable('mahasiswas')) return 'mahasiswas';
        if (Schema::hasTable('mahasiswa'))  return 'mahasiswa';
        return null;
    }

    /** Nama tabel kelompok: kelompoks | kelompok */
    private function groupTable(): ?string
    {
        if (Schema::hasTable('kelompoks')) return 'kelompoks';
        if (Schema::hasTable('kelompok'))  return 'kelompok';
        return null;
    }

    /** Nama tabel anggota kelompok: kelompok_anggota | kelompok_anggotas */
    private function groupMemberTable(): ?string
    {
        if (Schema::hasTable('kelompok_anggota'))  return 'kelompok_anggota';
        if (Schema::hasTable('kelompok_anggotas')) return 'kelompok_anggotas';
        return null;
    }

    /** ✅ Biar filter kelas cocok untuk "A" maupun "Kelas A" */
    private function kelasVariants(?string $kelas): array
    {
        if (!$kelas) return [];
        $k = trim($kelas);

        // "Kelas A" => ["Kelas A","A"]
        if (preg_match('/^Kelas\s+(.+)$/i', $k, $m)) {
            $only = trim($m[1]);
            return array_values(array_unique([$k, $only]));
        }

        // "A" => ["A","Kelas A"]
        return array_values(array_unique([$k, 'Kelas '.$k]));
    }

    /**
     * Menampilkan halaman gradebook penilaian
     * - Mahasiswa tetap tampil walau MK belum dipilih (filter by kelas)
     * - Rubrik & nilai hanya muncul kalau MK dipilih
     */
    public function index(Request $request)
    {
        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);

        $kodeMk = $request->query('matakuliah'); // boleh kosong
        $kelas  = $request->query('kelas');      // boleh kosong

        $rubrics    = collect();
        $totalBobot = 0;

        /*
        |----------------------------------------------------------------------
        | 1) AMBIL MAHASISWA — SELALU (BERDASARKAN KELAS)
        |----------------------------------------------------------------------
        */
        $mTable   = $this->studentTable() ?? 'mahasiswas';
        $hasKelas = Schema::hasColumn($mTable, 'kelas');

        $mhsQ = DB::table($mTable)->select('nim', 'nama');

        if ($hasKelas) {
            $mhsQ->addSelect('kelas');

            if (!empty($kelas)) {
                $mhsQ->whereIn('kelas', $this->kelasVariants($kelas));
            }
        } else {
            $mhsQ->addSelect(DB::raw('NULL as kelas'));
        }

        $mahasiswa = $mhsQ
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        /*
        |----------------------------------------------------------------------
        | 2) DAFTAR KELAS (UNTUK DROPDOWN)
        |----------------------------------------------------------------------
        */
        $kelasList = collect();
        if ($hasKelas) {
            $kelasList = DB::table($mTable)
                ->whereNotNull('kelas')
                ->where('kelas', '!=', '')
                ->distinct()
                ->orderBy('kelas')
                ->pluck('kelas');
        }

        /*
        |----------------------------------------------------------------------
        | 3) RUBRIK + NILAI (HANYA JIKA MK DIPILIH)
        |----------------------------------------------------------------------
        */
        if (!empty($kodeMk)) {

            // === RUBRIK ===
            $rubrikTable = (new Rubrik)->getTable();
            $rubricsQ = Rubrik::query();

            if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
                $rubricsQ->where('kode_mk', $kodeMk);
            } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
                $rubricsQ->where('mata_kuliah_kode', $kodeMk);
            }

            $rubrics = $rubricsQ
                ->orderBy('urutan')
                ->orderBy('id')
                ->get(['id', 'nama_rubrik', 'bobot', 'urutan']);

            $totalBobot = (int) $rubrics->sum('bobot');

            // === NILAI ===
            $nilaiMap = collect();

            if ($rubrics->isNotEmpty() && $mahasiswa->count()) {
                $nilaiSource = Schema::hasTable('rubrik_penilaian')
                    ? 'rubrik_penilaian'
                    : (Schema::hasTable('penilaian') ? 'penilaian' : null);

                if ($nilaiSource) {
                    $nimList = $mahasiswa->getCollection()->pluck('nim');

                    $nilaiMap = DB::table($nilaiSource)
                        ->select([
                            'mahasiswa_nim as nim',
                            'rubrik_id',
                            'nilai'
                        ])
                        ->whereIn('mahasiswa_nim', $nimList)
                        ->whereIn('rubrik_id', $rubrics->pluck('id'))
                        ->get()
                        ->groupBy('nim');
                }
            }

            // inject nilai ke mahasiswa
            $mahasiswa->getCollection()->transform(function ($m) use ($nilaiMap) {
                $m->penilaian = collect($nilaiMap->get($m->nim, []));
                return $m;
            });

        } else {
            // MK belum dipilih → nilai kosong tapi mahasiswa tetap tampil
            $mahasiswa->getCollection()->transform(function ($m) {
                $m->penilaian = collect();
                return $m;
            });
        }

        return view('dosenpenguji.penilaian', [
            'matakuliah' => $matakuliah,
            'rubrics'    => $rubrics,
            'mahasiswa'  => $mahasiswa,
            'totalBobot' => $totalBobot,
            'mk'         => $kodeMk,
            'kelasList'  => $kelasList,
            'kelas'      => $kelas,
        ]);
    }

    // method lain (bulkSave/export/import/dll) biarkan tetap seperti punyamu
}
