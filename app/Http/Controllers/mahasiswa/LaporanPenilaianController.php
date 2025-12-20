<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\Penilaian;   // table: penilaians
use App\Models\Rubrik;      // table: rubrik
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Mahasiswa;   // table: mahasiswas

class LaporanPenilaianController extends Controller
{
    /**
     * Data laporan penilaian mahasiswa (read-only).
     * PRIORITAS 1: penilaians (kalau tabel ini dipakai & terisi)
     * PRIORITAS 2: penilaian_items (sesuai DB kamu sekarang: mahasiswa_nim, rubrik_id, nilai)
     * FALLBACK   : laporan_penilaian (jika masih dipakai)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // =========================
        // 1) Ambil NIM mahasiswa login (robust) + row mahasiswa
        // =========================
        $nim = $user->nim ?? null;

        if (!$nim && method_exists($user, 'mahasiswa') && $user->mahasiswa) {
            $nim = $user->mahasiswa->nim ?? null;
        }

        $mhsRow = null;
        if (class_exists(Mahasiswa::class)) {
            $mhsTable = (new Mahasiswa)->getTable();

            if ($nim) {
                $mhsRow = Mahasiswa::where('nim', (string)$nim)->first();
            }

            if (!$mhsRow) {
                $mhsQuery = Mahasiswa::query();

                if (Schema::hasColumn($mhsTable, 'user_id')) {
                    $mhsQuery->where('user_id', $user->id);
                } elseif (Schema::hasColumn($mhsTable, 'email')) {
                    $mhsQuery->where('email', $user->email);
                }

                $mhsRow = $mhsQuery->first();
                if ($mhsRow) {
                    $nim = $mhsRow->nim ?? $nim;
                }
            }
        }

        if (!$nim) {
            return response()->json([
                'nim' => null,
                'message' => 'NIM mahasiswa tidak ditemukan di akun login.',
                'filters' => [
                    'matakuliah' => [],
                    'kelas' => [],
                    'selected' => ['matakuliah' => null, 'kelas' => null],
                ],
                'rubrik' => [],
                'rows' => [],
                'nilaiAkhir' => 0,
            ]);
        }

        $nim = (string)$nim;

        // =========================
        // 2) Filter MK & Kelas (tetap ada)
        // =========================
        $mkKode  = $request->query('matakuliah'); // contoh: 001
        $kelasId = $request->query('kelas');      // contoh: 5 (id kelas)

        $matakuliah = class_exists(MataKuliah::class)
            ? MataKuliah::orderBy('nama_mk')->get(['kode_mk', 'nama_mk'])
            : collect();

        // dropdown kelas
        $kelasList = collect();
        if (class_exists(Kelas::class)) {
            try {
                $kelasList = Kelas::orderBy('nama_kelas')->get(['id', 'nama_kelas']);
            } catch (\Throwable $e) {
                $kelasList = collect();
            }
        }

        // fallback kalau tabel kelas tidak dipakai: ambil distinct dari mahasiswas.kelas (string)
        if ($kelasList->isEmpty() && class_exists(Mahasiswa::class)) {
            $mhsTable = (new Mahasiswa)->getTable();
            if (Schema::hasColumn($mhsTable, 'kelas')) {
                $kelasList = Mahasiswa::select('kelas as nama_kelas')
                    ->whereNotNull('kelas')
                    ->where('kelas', '!=', '')
                    ->distinct()
                    ->orderBy('kelas')
                    ->get()
                    ->values()
                    ->map(function ($r, $idx) {
                        return (object)['id' => $idx + 1, 'nama_kelas' => $r->nama_kelas];
                    });
            }
        }

        // kelasId -> nama_kelas (untuk validasi kalau mahasiswa.kelas string)
        $kelasName = null;
        if ($kelasId && $kelasList->count()) {
            $found = $kelasList->firstWhere('id', (int)$kelasId) ?? $kelasList->firstWhere('id', $kelasId);
            $kelasName = $found->nama_kelas ?? null;
        }

        // =========================
        // 2b) Validasi kelas mahasiswa (biar filter kelas konsisten)
        // - kalau mahasiswas punya kelas_id -> compare ke kelasId
        // - kalau mahasiswas punya kelas (string) -> compare ke kelasName
        // =========================
        if ($kelasId && $mhsRow && class_exists(Mahasiswa::class)) {
            $mhsTable = (new Mahasiswa)->getTable();

            if (Schema::hasColumn($mhsTable, 'kelas_id')) {
                if ((string)($mhsRow->kelas_id ?? '') !== (string)$kelasId) {
                    return response()->json([
                        'nim' => $nim,
                        'message' => 'Mahasiswa tidak berada pada kelas filter yang dipilih.',
                        'filters' => [
                            'matakuliah' => $matakuliah,
                            'kelas' => $kelasList,
                            'selected' => ['matakuliah' => $mkKode, 'kelas' => $kelasId],
                        ],
                        'rubrik' => [],
                        'rows' => [],
                        'nilaiAkhir' => 0,
                    ]);
                }
            } elseif (Schema::hasColumn($mhsTable, 'kelas') && $kelasName) {
                if ((string)($mhsRow->kelas ?? '') !== (string)$kelasName) {
                    return response()->json([
                        'nim' => $nim,
                        'message' => 'Mahasiswa tidak berada pada kelas filter yang dipilih.',
                        'filters' => [
                            'matakuliah' => $matakuliah,
                            'kelas' => $kelasList,
                            'selected' => ['matakuliah' => $mkKode, 'kelas' => $kelasId],
                        ],
                        'rubrik' => [],
                        'rows' => [],
                        'nilaiAkhir' => 0,
                    ]);
                }
            }
        }

        // =========================
        // 3) Ambil rubrik (komponen + bobot)
        // =========================
        $rubrikQuery = Rubrik::query()->orderBy('urutan')->orderBy('id');
        if ($mkKode && Schema::hasColumn((new Rubrik)->getTable(), 'kode_mk')) {
            $rubrikQuery->where('kode_mk', $mkKode);
        }
        $rubrik = $rubrikQuery->get();

        // =========================
        // 4) PRIORITAS 1: ambil dari penilaians (kalau terisi)
        // =========================
        $penilaianTable = (new Penilaian)->getTable();

        if (Schema::hasTable($penilaianTable)) {
            $penQ = Penilaian::query()->latest();

            // cocokkan mahasiswa
            $penQ->where(function ($q) use ($nim, $mhsRow, $penilaianTable) {
                if (Schema::hasColumn($penilaianTable, 'nim')) {
                    $q->orWhere('nim', $nim);
                }

                if (Schema::hasColumn($penilaianTable, 'mahasiswa_id')) {
                    // kemungkinan: disimpan nim
                    $q->orWhere('mahasiswa_id', $nim);
                    if (is_numeric($nim)) $q->orWhere('mahasiswa_id', (int)$nim);

                    // kemungkinan: disimpan id mahasiswa
                    if ($mhsRow && isset($mhsRow->id)) {
                        $q->orWhere('mahasiswa_id', (int)$mhsRow->id);
                    }
                }
            });

            // filter MK
            if ($mkKode) {
                if (Schema::hasColumn($penilaianTable, 'matakuliah_kode')) {
                    $penQ->where('matakuliah_kode', $mkKode);
                } elseif (Schema::hasColumn($penilaianTable, 'kode_mk')) {
                    $penQ->where('kode_mk', $mkKode);
                }
            }

            // filter kelas_id (nullable)
            if ($kelasId && Schema::hasColumn($penilaianTable, 'kelas_id')) {
                $penQ->where(function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId)
                      ->orWhereNull('kelas_id');
                });
            }

            $penilaian = $penQ->first();

            if ($penilaian) {
                $nilaiPerRubrik = [];

                // dari komponen json
                if (Schema::hasColumn($penilaianTable, 'komponen') && !empty($penilaian->komponen)) {
                    $decoded = json_decode($penilaian->komponen, true);
                    if (is_array($decoded)) $nilaiPerRubrik = $decoded;
                }

                // fallback dari penilaian_items (kalau tabelnya punya penilaian_id)
                if (empty($nilaiPerRubrik) && Schema::hasTable('penilaian_items')) {
                    // cek kolom penilaian_id memang ada atau tidak
                    $piColsOk = Schema::hasColumn('penilaian_items', 'penilaian_id');

                    if ($piColsOk) {
                        $items = DB::table('penilaian_items')
                            ->where('penilaian_id', $penilaian->id)
                            ->get();

                        foreach ($items as $it) {
                            $rubrikId = $it->rubrik_id ?? $it->kode_rubrik ?? $it->rubric_id ?? null;
                            $nilai    = $it->nilai ?? $it->skor ?? $it->value ?? null;
                            if ($rubrikId !== null) {
                                $nilaiPerRubrik[(string)$rubrikId] = $nilai;
                            }
                        }
                    }
                }

                $rows = collect();
                foreach ($rubrik as $r) {
                    $skor = $nilaiPerRubrik[(string)$r->id] ?? $nilaiPerRubrik[$r->id] ?? null;

                    $rows->push((object)[
                        'nim' => $nim,
                        'rubrik_id' => $r->id,
                        'skor' => ($skor === null || $skor === '') ? null : (float)$skor,
                    ]);
                }

                $nilaiAkhir = 0;
                if (Schema::hasColumn($penilaianTable, 'nilai_akhir') && $penilaian->nilai_akhir !== null) {
                    $nilaiAkhir = round((float)$penilaian->nilai_akhir, 2);
                } else {
                    $tmp = 0;
                    foreach ($rows as $rr) {
                        $rModel = $rubrik->firstWhere('id', $rr->rubrik_id);
                        $bobot  = (float)($rModel->bobot ?? 0);
                        $skorN  = (float)($rr->skor ?? 0);
                        $tmp += ($bobot / 100) * $skorN;
                    }
                    $nilaiAkhir = round($tmp, 2);
                }

                return response()->json([
                    'nim' => $nim,
                    'source' => 'penilaians',
                    'filters' => [
                        'matakuliah' => $matakuliah,
                        'kelas' => $kelasList,
                        'selected' => ['matakuliah' => $mkKode, 'kelas' => $kelasId],
                    ],
                    'rubrik' => $rubrik,
                    'rows' => $rows,
                    'nilaiAkhir' => $nilaiAkhir,
                ]);
            }
        }

        // =========================
        // 4b) PRIORITAS 2: ambil dari penilaian_items (INI YANG TERISI DI DB KAMU)
        // kolom: mahasiswa_nim, rubrik_id, nilai
        // =========================
        if (Schema::hasTable('penilaian_items')
            && Schema::hasColumn('penilaian_items', 'mahasiswa_nim')
            && Schema::hasColumn('penilaian_items', 'rubrik_id')
        ) {
            $itemsQ = DB::table('penilaian_items')
                ->where('mahasiswa_nim', $nim);

            // kalau filter MK dipakai, join rubrik supaya rubrik.kode_mk sesuai
            if ($mkKode && Schema::hasColumn((new Rubrik)->getTable(), 'kode_mk')) {
                $itemsQ->join((new Rubrik)->getTable(), 'penilaian_items.rubrik_id', '=', (new Rubrik)->getTable() . '.id')
                    ->where((new Rubrik)->getTable() . '.kode_mk', $mkKode)
                    ->select([
                        'penilaian_items.mahasiswa_nim as nim',
                        'penilaian_items.rubrik_id as rubrik_id',
                        'penilaian_items.nilai as skor',
                    ]);
            } else {
                $itemsQ->select([
                    'penilaian_items.mahasiswa_nim as nim',
                    'penilaian_items.rubrik_id as rubrik_id',
                    'penilaian_items.nilai as skor',
                ]);
            }

            $items = $itemsQ->get();

            // map nilai per rubrik_id
            $nilaiMap = [];
            foreach ($items as $it) {
                $nilaiMap[(string)$it->rubrik_id] = $it->skor;
            }

            // susun rows berdasarkan rubrik yang sedang ditampilkan
            $rows = collect();
            $nilaiAkhir = 0;

            foreach ($rubrik as $r) {
                $skor = $nilaiMap[(string)$r->id] ?? null;

                $rows->push((object)[
                    'nim' => $nim,
                    'rubrik_id' => $r->id,
                    'skor' => ($skor === null || $skor === '') ? null : (float)$skor,
                ]);

                if ($skor !== null && $skor !== '') {
                    $nilaiAkhir += ((float)$r->bobot / 100) * (float)$skor;
                }
            }

            // kalau memang tidak ada satupun skor, tetap rows ada tapi skor null semua -> UI bisa tampil '-'
            return response()->json([
                'nim' => $nim,
                'source' => 'penilaian_items',
                'filters' => [
                    'matakuliah' => $matakuliah,
                    'kelas' => $kelasList,
                    'selected' => ['matakuliah' => $mkKode, 'kelas' => $kelasId],
                ],
                'rubrik' => $rubrik,
                'rows' => $rows,
                'nilaiAkhir' => round($nilaiAkhir, 2),
            ]);
        }

        // =========================
        // 5) FALLBACK: laporan_penilaian
        // =========================
        if (Schema::hasTable('laporan_penilaian')) {
            $lpTable  = 'laporan_penilaian';
            $rubTable = (new Rubrik)->getTable();
            $mhsTable = class_exists(Mahasiswa::class) ? (new Mahasiswa)->getTable() : 'mahasiswas';

            $nilaiRowsQuery = DB::table($lpTable)
                ->join($rubTable, "{$lpTable}.kode_rubrik", "=", "{$rubTable}.id")
                ->join($mhsTable, "{$lpTable}.nim", "=", "{$mhsTable}.nim")
                ->where("{$lpTable}.nim", $nim)
                ->select([
                    "{$lpTable}.id_laporan",
                    "{$lpTable}.periode",
                    "{$lpTable}.nim",
                    "{$lpTable}.kode_mk",
                    "{$lpTable}.kode_rubrik",
                    "{$lpTable}.nilai as skor",
                    "{$rubTable}.nama_rubrik as komponen",
                    "{$rubTable}.bobot",
                    "{$mhsTable}.nama as nama_mahasiswa",
                ]);

            if ($mkKode) {
                $nilaiRowsQuery->where("{$lpTable}.kode_mk", $mkKode);
            }

            if (Schema::hasColumn($mhsTable, 'kelas') && $kelasName) {
                $nilaiRowsQuery->where("{$mhsTable}.kelas", $kelasName);
            }

            $rows = $nilaiRowsQuery
                ->orderBy("{$rubTable}.urutan")
                ->orderBy("{$rubTable}.id")
                ->get();

            $tmp = 0;
            foreach ($rows as $r) {
                $tmp += ((float)($r->bobot ?? 0) / 100) * (float)($r->skor ?? 0);
            }

            return response()->json([
                'nim' => $nim,
                'source' => 'laporan_penilaian',
                'filters' => [
                    'matakuliah' => $matakuliah,
                    'kelas' => $kelasList,
                    'selected' => ['matakuliah' => $mkKode, 'kelas' => $kelasId],
                ],
                'rubrik' => $rubrik,
                'rows' => $rows,
                'nilaiAkhir' => round($tmp, 2),
            ]);
        }

        // =========================
        // 6) Tidak ada sumber
        // =========================
        return response()->json([
            'nim' => $nim,
            'message' => 'Data penilaian tidak ditemukan (penilaians / penilaian_items / laporan_penilaian).',
            'filters' => [
                'matakuliah' => $matakuliah,
                'kelas' => $kelasList,
                'selected' => ['matakuliah' => $mkKode, 'kelas' => $kelasId],
            ],
            'rubrik' => $rubrik,
            'rows' => [],
            'nilaiAkhir' => 0,
        ]);
    }
}
