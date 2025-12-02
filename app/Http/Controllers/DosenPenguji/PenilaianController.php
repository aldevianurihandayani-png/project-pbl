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
    /* ===== Helpers: deteksi nama tabel/kolom dinamis (ASLI, dipertahankan) ===== */

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

    /**
     * Menampilkan halaman gradebook penilaian. (ASLI, dipertahankan)
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
            $mTable   = $this->studentTable();       // mahasiswas | mahasiswa
            $gTable   = $this->groupTable();         // kelompoks  | kelompok
            $gmTable  = $this->groupMemberTable();   // kelompok_anggota | kelompok_anggotas
            $hasKelas = $mTable ? Schema::hasColumn($mTable, 'kelas') : false;

            if ($mTable && $gTable && $gmTable) {
                // via tabel kelompok -> anggota -> mahasiswa
                $mhsQ = DB::table("$gTable as k")
                    ->join("$gmTable as ka", 'ka.kelompok_id', '=', 'k.id')
                    ->join("$mTable as m", 'm.nim', '=', 'ka.nim');

                // Filter MK: coba beberapa kemungkinan nama kolom di tabel kelompok
                if (Schema::hasColumn($gTable, 'kode_mk')) {
                    $mhsQ->where('k.kode_mk', $kodeMk);
                } elseif (Schema::hasColumn($gTable, 'mata_kuliah_kode')) {
                    $mhsQ->where('k.mata_kuliah_kode', $kodeMk);
                } elseif (Schema::hasColumn($gTable, 'kode_matakuliah')) {
                    $mhsQ->where('k.kode_matakuliah', $kodeMk);
                } elseif (Schema::hasColumn($gTable, 'mata_kuliah_id') && Schema::hasTable('mata_kuliah')) {
                    // Join ke tabel mata_kuliah bila menyimpan FK id
                    $mhsQ->join('mata_kuliah as mkTbl', 'mkTbl.id', '=', 'k.mata_kuliah_id')
                         ->where('mkTbl.kode_mk', $kodeMk);
                }

                // Filter kelas jika kolom ada dan parameter diisi
                if ($hasKelas && filled($kelas)) {
                    $mhsQ->where('m.kelas', $kelas);
                }

                // Select aman: selalu kirim 'kelas' (NULL jika tidak ada)
                $select = ['m.nim as nim', 'm.nama as nama'];
                if ($hasKelas) {
                    $select[] = 'm.kelas as kelas';
                } else {
                    $select[] = DB::raw('NULL as kelas');
                }

                $mahasiswa = $mhsQ->distinct()
                    ->orderBy('m.nama')
                    ->paginate(15, $select)
                    ->withQueryString();
            } else {
                // Fallback awal: ambil dari tabel mahasiswa langsung
                $mTable = $mTable ?? 'mahasiswas'; // fallback nama
                $q = DB::table($mTable)->select('nim','nama');

                if ($hasKelas) {
                    $q->addSelect('kelas');
                    if (filled($kelas)) $q->where('kelas', $kelas);
                } else {
                    $q->addSelect(DB::raw('NULL as kelas'));
                }

                $mahasiswa = $q->orderBy('nama')->paginate(15)->withQueryString();
            }

            // ===== 2b) Fallback agar tidak kosong:
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
                        $mTable   = $this->studentTable() ?? 'mahasiswas';
                        $hasKelas = Schema::hasColumn($mTable, 'kelas');

                        $q = DB::table($mTable)
                            ->whereIn('nim', $nimSet)
                            ->select('nim','nama');

                        if ($hasKelas) {
                            $q->addSelect('kelas');
                        } else {
                            $q->addSelect(DB::raw('NULL as kelas'));
                        }

                        $mahasiswa = $q->orderBy('nama')->paginate(15)->withQueryString();
                    }
                }

                // jika masih kosong → tampilkan semua mahasiswa (opsional filter kelas)
                $stillEmpty = $mahasiswa instanceof \Illuminate\Contracts\Pagination\Paginator
                    ? ($mahasiswa->total() === 0)
                    : ($mahasiswa->count() === 0);

                if ($stillEmpty) {
                    $mTable   = $this->studentTable() ?? 'mahasiswas';
                    $hasKelas = Schema::hasColumn($mTable, 'kelas');

                    $q = DB::table($mTable)->select('nim','nama');
                    if ($hasKelas) {
                        $q->addSelect('kelas');
                        if (filled($kelas)) $q->where('kelas', $kelas);
                    } else {
                        $q->addSelect(DB::raw('NULL as kelas'));
                    }

                    $mahasiswa = $q->orderBy('nama')->paginate(15)->withQueryString();
                }
            }

            // ===== 3) Ambil nilai per (nim × rubrik)
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
                $m->penilaian = collect($nilaiMap->get($m->nim, []));
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
     * Menyimpan beberapa nilai sekaligus (bulk). (ASLI, dipertahankan)
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

    /** ========== EXPORT CSV (ASLI, dipertahankan) ========== */
    public function export(Request $request)
    {
        $kodeMk = $request->query('matakuliah');   // wajib agar mapping rubrik tepat
        $kelas  = $request->query('kelas');

        if (empty($kodeMk)) {
            return back()->with('error','Pilih mata kuliah dulu untuk export.');
        }

        // ambil rubrik-rubrik untuk MK
        $rubrikTable = (new Rubrik)->getTable();
        $rubriksQ = Rubrik::query();
        if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
            $rubriksQ->where('kode_mk', $kodeMk);
        } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
            $rubriksQ->where('mata_kuliah_kode', $kodeMk);
        }
        $rubriks = $rubriksQ->orderBy('urutan')->orderBy('id')->get(['id','nama_rubrik','bobot']);

        if ($rubriks->isEmpty()) {
            return back()->with('error','Belum ada rubrik untuk MK ini.');
        }

        // ambil mahasiswa sesuai filter (dinamis: mahasiswas|mahasiswa, kelas opsional)
        $mTable   = Schema::hasTable('mahasiswas') ? 'mahasiswas' : (Schema::hasTable('mahasiswa') ? 'mahasiswa' : null);
        $hasKelas = $mTable && Schema::hasColumn($mTable, 'kelas');

        $mhsQ = DB::table($mTable ?? 'mahasiswas')->select('nim','nama');
        $mhsQ->addSelect($hasKelas ? 'kelas' : DB::raw('NULL as kelas'));
        if ($hasKelas && filled($kelas)) $mhsQ->where('kelas',$kelas);
        $mhs = $mhsQ->orderBy('nama')->get();

        // ambil nilai (nim × rubrik)
        $nilaiSrc = Schema::hasTable('rubrik_penilaian') ? 'rubrik_penilaian' : (Schema::hasTable('penilaian') ? 'penilaian' : null);
        $nilaiMap = collect();
        if ($nilaiSrc && $mhs->count()) {
            $nilaiMap = DB::table($nilaiSrc)
                ->select(['mahasiswa_nim as nim','rubrik_id','nilai'])
                ->whereIn('mahasiswa_nim', $mhs->pluck('nim'))
                ->whereIn('rubrik_id', $rubriks->pluck('id'))
                ->get()
                ->groupBy('nim'); // [nim] => rows
        }

        // Bangun CSV: header = Nim, Nama, Kelas, lalu tiap rubrik jadi kolom
        $headers = ['NIM','Nama','Kelas'];
        foreach ($rubriks as $r) {
            $headers[] = "R{$r->id} - {$r->nama_rubrik} ({$r->bobot}%)";
        }

        $rows = [];
        foreach ($mhs as $m) {
            $row = [$m->nim, $m->nama, $m->kelas];
            $nilaiByNim = collect($nilaiMap->get($m->nim, []))->keyBy('rubrik_id');
            foreach ($rubriks as $r) {
                $row[] = optional($nilaiByNim->get($r->id))->nilai ?? '';
            }
            $rows[] = $row;
        }

        // streaming response CSV
        $filename = 'penilaian_'.$kodeMk.($kelas ? '_'.$kelas : '').'_'.date('Ymd_His').'.csv';
        $callback = function() use ($headers,$rows) {
            $out = fopen('php://output', 'w');
            // agar excel tidak kacau di locale koma, pakai delimiter koma standar
            fputcsv($out, $headers);
            foreach ($rows as $r) fputcsv($out, $r);
            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    /** ========== IMPORT CSV (ASLI, dipertahankan) ========== */
    public function import(Request $request)
    {
        // Izinkan query string mk/kelas ikut terbawa
        $kodeMk = $request->query('matakuliah');
        $kelas  = $request->query('kelas');

        $request->validate([
            'file' => ['required','file','mimes:csv,txt','max:2048'],
        ]);

        if (empty($kodeMk)) {
            return back()->with('error','Pilih mata kuliah dulu sebelum import.')->withInput();
        }

        // load rubrik MK yg sah untuk validasi
        $rubrikTable = (new Rubrik)->getTable();
        $rubriksQ = Rubrik::query();
        if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
            $rubriksQ->where('kode_mk', $kodeMk);
        } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
            $rubriksQ->where('mata_kuliah_kode', $kodeMk);
        }
        $rubriks = $rubriksQ->orderBy('urutan')->orderBy('id')->get(['id','nama_rubrik','bobot']);
        if ($rubriks->isEmpty()) {
            return back()->with('error','Belum ada rubrik untuk MK ini.');
        }
        $rubrikIds = $rubriks->pluck('id')->all();

        // Baca CSV (header seperti file export)
        $path = $request->file('file')->getRealPath();
        $fh = fopen($path, 'r');
        if (!$fh) return back()->with('error','Gagal membaca file.');

        $header = fgetcsv($fh); // baris header
        if (!$header) {
            fclose($fh);
            return back()->with('error','File kosong atau format tidak valid.');
        }

        // Petakan kolom rubrik dari header "R{id} - ..."
        $rubColIndex = []; // rubrik_id => index kolom
        foreach ($header as $i => $col) {
            if (preg_match('/^R(\d+)\b/i', $col, $m)) {
                $rid = (int)$m[1];
                if (in_array($rid, $rubrikIds, true)) $rubColIndex[$rid] = $i;
            }
        }
        if (empty($rubColIndex)) {
            fclose($fh);
            return back()->with('error','Tidak menemukan kolom rubrik pada header CSV.');
        }

        DB::beginTransaction();
        try {
            // baca baris data
            while (($row = fgetcsv($fh)) !== false) {
                $nim   = trim($row[0] ?? '');
                // $nama = $row[1] (ignored)
                if ($nim === '') continue;

                foreach ($rubColIndex as $rid => $idx) {
                    $nilaiRaw = $row[$idx] ?? '';
                    if ($nilaiRaw === '' || $nilaiRaw === null) continue;

                    $nilai = is_numeric($nilaiRaw) ? (float)$nilaiRaw : null;
                    if ($nilai === null || $nilai < 0 || $nilai > 100) continue;

                    DB::table('penilaian')->updateOrInsert(
                        ['mahasiswa_nim' => $nim, 'rubrik_id' => $rid],
                        ['nilai' => $nilai, 'updated_at' => now(), 'created_at' => now()]
                    );
                }
            }
            fclose($fh);
            DB::commit();

            // redirect kembali ke halaman dengan query yang sama
            return redirect()
                ->route('dosenpenguji.penilaian', ['matakuliah' => $kodeMk, 'kelas' => $kelas])
                ->with('success','Import nilai berhasil.');
        } catch (\Throwable $e) {
            if (is_resource($fh)) fclose($fh);
            DB::rollBack();
            return back()->with('error','Import gagal: '.$e->getMessage());
        }
    }

    /**
     * Menghapus satu data nilai (via AJAX). (ASLI, dipertahankan)
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

    /* ===================================================================
     |  ====== BLOK TAMBAHAN: FITUR PENILAIAN MODEL `Penilaian` ======
     |  (sesuai snippet yang kamu minta — hanya DITAMBAHKAN)
     * =================================================================== */

    // daftar penilaian berdasarkan filter matakuliah_id & kelas_id
    public function indexBaru(Request $r)
    {
        // filter dari dropdown di UI: matakuliah_id, kelas_id
        $mk    = $r->get('matakuliah_id');
        $kelas = $r->get('kelas_id');

        $items = Penilaian::with('mahasiswa')
                    ->where('dosen_id', Auth::id())
                    ->when($mk,    fn($q)=>$q->where('matakuliah_id',$mk))
                    ->when($kelas, fn($q)=>$q->where('kelas_id',$kelas))
                    ->get();

        // kalau belum ada, tampilkan teks “Komponen penilaian belum ada”
        return view('dosenpenguji.penilaian.index', [
            'items' => $items,
            'mk'    => $mk,
            'kelas' => $kelas
        ]);
    }

    // tambah baris penilaian kosong utk satu mahasiswa
    public function storeBaru(Request $r)
    {
        $data = $r->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswas,id',
            'matakuliah_id' => 'required|exists:matakuliahs,id',
            'kelas_id'      => 'nullable|exists:kelases,id',
        ]);

        Penilaian::firstOrCreate(
            $data + ['dosen_id'=>Auth::id()],
            ['komponen'=>[], 'nilai_akhir'=>0]
        );

        return back()->with('success','Baris penilaian dibuat.');
    }

    // update komponen & nilai akhir (Simpan Semua)
    public function updateBaru(Request $r, Penilaian $penilaian)
    {
        $payload = $r->validate([
            'komponen' => 'array',
            'komponen.*.nama'  => 'required|string',
            'komponen.*.bobot' => 'required|numeric|min:0',
            'komponen.*.skor'  => 'required|numeric|min:0',
        ]);

        $na = collect($payload['komponen'] ?? [])
                ->sum(fn($k)=>$k['bobot']*$k['skor']/100);

        $penilaian->update([
            'komponen'   => $payload['komponen'] ?? [],
            'nilai_akhir'=> $na,
        ]);

        return back()->with('success','Penilaian disimpan.');
    }

    public function destroyBaru(Penilaian $penilaian)
    {
        $penilaian->delete();
        return back()->with('success','Baris penilaian dihapus.');
    }

    // ====== EXPORT (Excel / CSV / PDF) ======
    public function exportExcelBaru(Request $r)
    {
        return Excel::download(
            new PenilaianExport($r->matakuliah_id, $r->kelas_id),
            'penilaian.xlsx'
        );
    }

    public function exportCsvBaru(Request $r)
    {
        return Excel::download(
            new PenilaianExport($r->matakuliah_id, $r->kelas_id),
            'penilaian.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    public function exportPdfBaru(Request $r)
    {
        $data = Penilaian::with('mahasiswa')
                ->where('matakuliah_id',$r->matakuliah_id)
                ->when($r->kelas_id, fn($q)=>$q->where('kelas_id',$r->kelas_id))
                ->where('dosen_id',Auth::id())
                ->get();

        $pdf = Pdf::loadView('dosenpenguji.penilaian.pdf', ['items'=>$data]);
        return $pdf->download('penilaian.pdf');
    }

    // ====== TEMPLATE & IMPORT ======
    public function downloadTemplateBaru()
    {
        // export kosong sebagai template
        return Excel::download(new PenilaianExport(null,null), 'template_penilaian.xlsx');
    }

    public function importExcelBaru(Request $r)
    {
        $r->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'matakuliah_id' => 'required|exists:matakuliahs,id',
            'kelas_id' => 'nullable|exists:kelases,id',
        ]);
        Excel::import(new PenilaianImport($r->matakuliah_id, $r->kelas_id), $r->file('file'));
        return back()->with('success','Import penilaian berhasil.');
    }
}
