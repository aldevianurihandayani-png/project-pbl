<?php

namespace App\Http\Controllers\DosenPenguji;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Rubrik;                  // master rubrik
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PenilaianController extends Controller
{
    /**
     * Menampilkan halaman gradebook penilaian.
     */
    public function index(Request $request)
    {
        // Dropdown MK selalu terisi
        $matakuliah = MataKuliah::orderBy('nama_mk')->get(['kode_mk','nama_mk']);

        $kodeMk = $request->query('matakuliah');   // ex: "INS"
        $kelas  = $request->query('kelas');        // ex: "A"

        // default untuk view
        $rubrics    = collect();
        $mahasiswa  = collect();
        $totalBobot = 0;

        if (!empty($kodeMk)) {
            // ===== 1) Ambil rubrik utk MK terpilih (deteksi nama kolom secara aman)
            $rubrikTable = (new Rubrik)->getTable();
            $rubricsQ = Rubrik::query();

            if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
                $rubricsQ->where('kode_mk', $kodeMk);
            } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
                $rubricsQ->where('mata_kuliah_kode', $kodeMk);
            }

            $rubrics = $rubricsQ->orderBy('urutan')->orderBy('id')
                ->get(['id','nama_rubrik','bobot','urutan']);
            $totalBobot = (int) $rubrics->sum('bobot');

            // ===== 2) Ambil daftar mahasiswa yang terdaftar di MK tsb
            // Prefer via tabel kelompok -> kelompok_anggota -> mahasiswa
            if (Schema::hasTable('kelompok') && Schema::hasTable('kelompok_anggota')) {
                $mhsQ = DB::table('kelompok as k')
                    ->join('kelompok_anggota as ka', 'ka.kelompok_id', '=', 'k.id')
                    ->join('mahasiswa as m', 'm.nim', '=', 'ka.nim')
                    ->where('k.kode_mk', $kodeMk);

                // filter kelas kalau tersedia kolomnya
                if (!empty($kelas) && Schema::hasColumn('mahasiswa', 'kelas')) {
                    $mhsQ->where('m.kelas', $kelas);
                }

                $mhsQ->distinct()->orderBy('m.nama');

                // gunakan pagination agar kompatibel dengan blade kamu
                $mahasiswa = $mhsQ->paginate(15, ['m.nim','m.nama','m.kelas'])->withQueryString();
            } else {
                // Fallback awal: kalau struktur kelompok belum ada, ambil dari tabel mahasiswa
                $mhsQ = Mahasiswa::select('nim','nama','kelas')
                    ->when($kelas, fn($q) => $q->where('kelas', $kelas))
                    ->orderBy('nama');

                $mahasiswa = $mhsQ->paginate(15)->withQueryString();
            }

            // ===== 2b) Fallback agar tidak kosong:
            // jika query di atas tetap menghasilkan 0 baris,
            // coba tampilkan mahasiswa yang minimal sudah punya nilai utk rubrik MK ini,
            // bila tetap kosong juga -> tampilkan semua mahasiswa (opsional filter kelas).
            $isEmpty = $mahasiswa instanceof \Illuminate\Contracts\Pagination\Paginator
                ? ($mahasiswa->total() === 0)
                : ($mahasiswa->count() === 0);

            if ($isEmpty) {
                // cari nim yang sudah punya nilai utk rubrik MK terpilih
                $sourceTable = Schema::hasTable('rubrik_penilaian')
                    ? 'rubrik_penilaian'
                    : (Schema::hasTable('penilaian') ? 'penilaian' : null);

                if ($sourceTable && $rubrics->isNotEmpty()) {
                    $nimSet = DB::table($sourceTable)
                        ->whereIn('rubrik_id', $rubrics->pluck('id'))
                        ->distinct()
                        ->pluck('mahasiswa_nim');

                    if ($nimSet->isNotEmpty()) {
                        $mahasiswa = Mahasiswa::whereIn('nim', $nimSet)
                            ->select('nim','nama','kelas')
                            ->orderBy('nama')
                            ->paginate(15)
                            ->withQueryString();
                    }
                }

                // jika masih kosong → tampilkan semua mahasiswa (opsional filter kelas)
                $stillEmpty = $mahasiswa instanceof \Illuminate\Contracts\Pagination\Paginator
                    ? ($mahasiswa->total() === 0)
                    : ($mahasiswa->count() === 0);

                if ($stillEmpty) {
                    $mahasiswa = Mahasiswa::select('nim','nama','kelas')
                        ->when($kelas, fn($q) => $q->where('kelas', $kelas))
                        ->orderBy('nama')
                        ->paginate(15)
                        ->withQueryString();
                }
            }

            // ===== 3) Ambil nilai per (nim × rubrik) dari storage nilai
            // Gunakan VIEW/TABLE: rubrik_penilaian bila ada, kalau tidak ada pakai table penilaian
            $nilaiMap = collect();
            if ($rubrics->isNotEmpty() && $mahasiswa->count()) {
                $nilaiSource = Schema::hasTable('rubrik_penilaian')
                    ? 'rubrik_penilaian'
                    : (Schema::hasTable('penilaian') ? 'penilaian' : null);

                if ($nilaiSource) {
                    $nilaiMap = DB::table($nilaiSource)
                        ->select(['mahasiswa_nim as nim','rubrik_id','nilai'])
                        ->whereIn('mahasiswa_nim', $mahasiswa->pluck('nim'))
                        ->whereIn('rubrik_id', $rubrics->pluck('id'))
                        ->get()
                        ->groupBy('nim'); // => [nim] => koleksi baris
                }
            }

            // ===== 4) Sisipkan koleksi penilaian ke tiap objek mahasiswa
            $mahasiswa->getCollection()->transform(function ($m) use ($nilaiMap) {
                $m->penilaian = collect($nilaiMap->get($m->nim, [])); // agar Blade bisa ->firstWhere('rubric_id', …)
                return $m;
            });
        }

        return view('dosenpenguji.penilaian', [
            'matakuliah' => $matakuliah,
            'rubrics'    => $rubrics,
            'mahasiswa'  => $mahasiswa,
            'totalBobot' => $totalBobot,
            'mk'         => $kodeMk,
        ]);
    }

    /**
     * Menyimpan beberapa nilai sekaligus (bulk).
     */
    public function bulkSave(Request $request)
    {
        $validated = $request->validate([
            'bobot'       => ['required', 'array'],
            'bobot.*'     => ['required', 'numeric', 'min:0'],
            'nilai'       => ['nullable', 'array'],
            'nilai.*.*'   => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        // Validasi total bobot
        if (array_sum($validated['bobot']) != 100) {
            return back()->withErrors(['bobot' => 'Total bobot harus 100.'])->withInput();
        }

        DB::beginTransaction();
        try {
            // 1) Update bobot rubrik
            foreach ($validated['bobot'] as $rubricId => $bobot) {
                Rubrik::find($rubricId)?->update(['bobot' => $bobot]);
            }

            // 2) Update / Insert nilai mahasiswa
            if (isset($validated['nilai'])) {
                foreach ($validated['nilai'] as $nim => $grades) {
                    foreach ($grades as $rubricId => $nilai) {
                        if ($nilai !== null && $nilai !== '') {
                            DB::table('penilaian')->updateOrInsert(
                                ['mahasiswa_nim' => $nim, 'rubrik_id' => $rubricId],
                                ['nilai' => $nilai, 'updated_at' => now(), 'created_at' => now()]
                            );
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan nilai: '.$e->getMessage());
        }
    }

    public function import(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur ini sedang dalam pengembangan.');
    }

    public function export(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur ini sedang dalam pengembangan.');
    }

    /**
     * Menghapus satu data nilai (via AJAX).
     */
    public function deleteGrade(Request $request, $nim, $rubric_id)
    {
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        try {
            DB::table('penilaian')
                ->where('mahasiswa_nim', $nim)
                ->where('rubrik_id', $rubric_id)
                ->delete();

            return response()->json(['message' => 'Nilai berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus nilai.'], 500);
        }
    }
}
