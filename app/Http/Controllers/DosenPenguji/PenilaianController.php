<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\MataKuliah;
use App\Models\Rubrik;
use App\Models\Penilaian;   // (opsional) masih dipakai di store/update/destroy/deleteGrade
use App\Models\Mahasiswa;

use App\Exports\PenilaianExport;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PenilaianController extends Controller
{
    /* ===== Helpers: deteksi nama tabel/kolom dinamis ===== */

    private function studentTable(): ?string
    {
        if (Schema::hasTable('mahasiswas')) return 'mahasiswas';
        if (Schema::hasTable('mahasiswa'))  return 'mahasiswa';
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
        return array_values(array_unique([$k, 'Kelas ' . $k]));
    }

    /**
     * Halaman penilaian (gradebook)
     * MK boleh kosong, mahasiswa tetap tampil (filter by kelas).
     */
    public function index(Request $request)
    {
        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk', 'nama_mk']);

        $kodeMk = $request->query('matakuliah');
        $kelas  = $request->query('kelas');

        $rubrics    = collect();
        $totalBobot = 0;

        // 1) Ambil mahasiswa (selalu)
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

        $mahasiswa = $mhsQ->orderBy('nama')->paginate(15)->withQueryString();

        // 2) List kelas dropdown
        $kelasList = collect();
        if ($hasKelas) {
            $kelasList = DB::table($mTable)
                ->whereNotNull('kelas')->where('kelas', '!=', '')
                ->distinct()->orderBy('kelas')->pluck('kelas');
        }

        // 3) Rubrik + nilai hanya jika MK dipilih
        if (!empty($kodeMk)) {

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

            // ✅ FIX: ambil nilai dari penilaian_items
            $nilaiMap = collect();

            if ($rubrics->isNotEmpty() && $mahasiswa->count() && Schema::hasTable('penilaian_items')) {
                $nimList = $mahasiswa->getCollection()->pluck('nim');

                $nilaiMap = DB::table('penilaian_items')
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

            $mahasiswa->getCollection()->transform(function ($m) use ($nilaiMap) {
                $m->penilaian = collect($nilaiMap->get($m->nim, []));
                return $m;
            });

        } else {
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

    /**
     * ✅ bulkSave sesuai blade:
     * input: nilai[nim][rubrik_id]
     * simpan: penilaian_items upsert (mahasiswa_nim + rubrik_id)
     */
    public function bulkSave(Request $request)
    {
        $nilai = $request->input('nilai', []);
        if (!is_array($nilai)) $nilai = [];

        if (!Schema::hasTable('penilaian_items')) {
            return back()->withErrors(['msg' => 'Tabel penilaian_items tidak ditemukan.'])->withInput();
        }

        $now  = now();
        $rows = [];

        foreach ($nilai as $nim => $rubrikArr) {
            if (!is_array($rubrikArr)) continue;

            foreach ($rubrikArr as $rubrikId => $val) {
                $val = ($val === '' || $val === null) ? null : $val;

                if ($val !== null && !is_numeric($val)) continue;

                $rows[] = [
                    'mahasiswa_nim' => (string) $nim,
                    'rubrik_id'     => (int) $rubrikId,
                    'nilai'         => $val,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
        }

        if (empty($rows)) {
            return back()->with('success', 'Tidak ada perubahan untuk disimpan.');
        }

        DB::table('penilaian_items')->upsert(
            $rows,
            ['mahasiswa_nim', 'rubrik_id'],
            ['nilai', 'updated_at']
        );

        return back()->with('success', 'Semua nilai berhasil disimpan.');
    }

    /**
     * ✅ Export Excel (sudah pakai PenilaianExport versi baru)
     */
    public function exportExcel(Request $request)
    {
        $matakuliahKode = $request->query('matakuliah');
        $kelas          = $request->query('kelas');

        return Excel::download(
            new PenilaianExport($matakuliahKode, $kelas),
            'penilaian.xlsx'
        );
    }

    /**
     * ✅ Export PDF versi baru (rubrik + nilai dari penilaian_items)
     */
    public function exportPdf(Request $request)
    {
        $kodeMk = $request->query('matakuliah');
        $kelas  = $request->query('kelas');

        if (empty($kodeMk)) {
            return back()->withErrors(['msg' => 'Pilih mata kuliah dulu sebelum export PDF.']);
        }

        // rubrik MK
        $rubrikTable = (new Rubrik)->getTable();
        $rubricsQ = Rubrik::query();

        if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
            $rubricsQ->where('kode_mk', $kodeMk);
        } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
            $rubricsQ->where('mata_kuliah_kode', $kodeMk);
        }

        $rubrics = $rubricsQ->orderBy('urutan')->orderBy('id')
            ->get(['id','nama_rubrik','bobot','urutan']);

        // mahasiswa
        $mTable   = $this->studentTable() ?? 'mahasiswas';
        $hasKelas = Schema::hasColumn($mTable, 'kelas');

        $mhsQ = DB::table($mTable)->select('nim','nama');
        if ($hasKelas) {
            $mhsQ->addSelect('kelas');
            if (!empty($kelas)) {
                $mhsQ->whereIn('kelas', $this->kelasVariants($kelas));
            }
        } else {
            $mhsQ->addSelect(DB::raw('NULL as kelas'));
        }

        $mahasiswa = $mhsQ->orderBy('nama')->get();

        // nilai map penilaian_items
        $nilaiMap = collect();
        if ($rubrics->isNotEmpty() && $mahasiswa->count() && Schema::hasTable('penilaian_items')) {
            $nimList = $mahasiswa->pluck('nim');

            $nilaiMap = DB::table('penilaian_items')
                ->select(['mahasiswa_nim as nim','rubrik_id','nilai'])
                ->whereIn('mahasiswa_nim', $nimList)
                ->whereIn('rubrik_id', $rubrics->pluck('id'))
                ->get()
                ->groupBy('nim');
        }

        $mahasiswa = $mahasiswa->map(function ($m) use ($nilaiMap) {
            $m->penilaian = collect($nilaiMap->get($m->nim, []));
            return $m;
        });

        $mkNama = optional(MataKuliah::where('kode_mk', $kodeMk)->first())->nama_mk;

        $pdf = Pdf::loadView('dosenpenguji.penilaian-pdf', [
            'kodeMk'    => $kodeMk,
            'mkNama'    => $mkNama,
            'kelas'     => $kelas,
            'rubrics'   => $rubrics,
            'mahasiswa' => $mahasiswa,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('penilaian.pdf');
    }

    /* ==========================================================
       (Opsional) endpoint lama Penilaian JSON:
       Boleh kamu hapus kalau tidak dipakai lagi.
       ========================================================== */

    public function store(Request $request)
    {
        $data = $request->validate([
            'mahasiswa_id'    => 'required|integer',
            'matakuliah_kode' => 'required|string',
            'kelas'           => 'nullable|string',
            'nilai_akhir'     => 'nullable|numeric',
            'komponen'        => 'nullable|array',
        ]);

        $p = Penilaian::create([
            'mahasiswa_id'    => $data['mahasiswa_id'],
            'matakuliah_kode' => $data['matakuliah_kode'],
            'kelas'           => $data['kelas'] ?? null,
            'nilai_akhir'     => $data['nilai_akhir'] ?? 0,
            'komponen'        => $data['komponen'] ?? [],
        ]);

        return response()->json(['message' => 'OK', 'data' => $p], 201);
    }

    public function update(Request $request, Penilaian $penilaian)
    {
        $data = $request->validate([
            'nilai_akhir' => 'nullable|numeric',
            'komponen'    => 'nullable|array',
        ]);

        $penilaian->update([
            'nilai_akhir' => $data['nilai_akhir'] ?? $penilaian->nilai_akhir,
            'komponen'    => $data['komponen'] ?? $penilaian->komponen,
        ]);

        return response()->json(['message' => 'OK', 'data' => $penilaian]);
    }

    public function destroy(Penilaian $penilaian)
    {
        $penilaian->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function deleteGrade(string $nim, int $rubric_id)
    {
        $mhs = Mahasiswa::where('nim', $nim)
            ->orWhere('npm', $nim)
            ->first();

        if (!$mhs) return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);

        $p = Penilaian::where('mahasiswa_id', $mhs->id)->first();
        if (!$p) return response()->json(['message' => 'Penilaian tidak ditemukan'], 404);

        $komponen = collect($p->komponen ?? [])
            ->reject(fn ($k) => (int)($k['rubric_id'] ?? 0) === (int)$rubric_id)
            ->values()
            ->all();

        $p->komponen = $komponen;
        $p->save();

        return response()->json(['message' => 'Komponen dihapus']);
    }
}
