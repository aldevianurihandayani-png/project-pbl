<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;

use App\Models\MataKuliah;
use App\Models\Rubrik;
use App\Models\Penilaian;
use App\Models\Mahasiswa;

use App\Exports\PenilaianExport;
use App\Imports\PenilaianImport;

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

        $kodeMk = $request->query('matakuliah'); // sesuai view kamu
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

            // nilai dari tabel rubrik_penilaian / penilaian (sesuai struktur kamu)
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'mahasiswa_id'    => 'required|integer',
            'matakuliah_kode' => 'required|string',
            'kelas'           => 'nullable|string',  // ✅ pakai kelas string
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

    public function bulkSave(Request $request)
    {
        $rows = $request->validate([
            'data' => 'required|array',
        ])['data'];

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                Penilaian::updateOrCreate(
                    [
                        'mahasiswa_id'    => $row['mahasiswa_id'],
                        'matakuliah_kode' => $row['matakuliah_kode'],
                        'kelas'           => $row['kelas'] ?? null, // ✅ kelas string
                    ],
                    [
                        'nilai_akhir' => $row['nilai_akhir'] ?? 0,
                        'komponen'    => $row['komponen'] ?? [],
                    ]
                );
            }
        });

        return response()->json(['message' => 'Semua data tersimpan']);
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

    /**
     * ✅ Export Excel FIX:
     * ambil filter dari halaman penilaian: ?matakuliah=...&kelas=...
     * dan lempar ke PenilaianExport($matakuliahKode, $kelas)
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

    public function exportPdf(Request $request)
    {
        $matakuliahKode = $request->query('matakuliah');
        $kelas          = $request->query('kelas');

        $data = Penilaian::with('mahasiswa')
            ->when($matakuliahKode, fn ($q) => $q->where('matakuliah_kode', $matakuliahKode))
            ->when($kelas, fn ($q) => $q->whereIn('kelas', $this->kelasVariants($kelas)))
            ->get();

        $pdf = Pdf::loadView('dosenpenguji.penilaian-pdf', [
            'penilaian' => $data,
            'matakuliahKode' => $matakuliahKode,
            'kelas' => $kelas,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('penilaian.pdf');
    }

    public function downloadTemplateBaru()
    {
        $path = storage_path('app/templates/template-penilaian.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template tidak ditemukan. Simpan di storage/app/templates/template-penilaian.xlsx');
        }

        return Response::download($path, 'template-penilaian.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new PenilaianImport, $request->file('file'));

        return back()->with('success', 'Import berhasil');
    }
}
