<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Peringkat;
use App\Models\TpkMahasiswa;
use App\Models\TpkKelompok;

class PeringkatController extends Controller
{
    /**
     * LIST: tampilkan peringkat mahasiswa & kelompok (pagination masing-masing)
     */
    public function index()
    {
        $peringkatKelompok = Peringkat::where('jenis', 'kelompok')
            ->orderBy('peringkat')
            ->paginate(10, ['*'], 'pk_page');

        $peringkatMahasiswa = Peringkat::where('jenis', 'mahasiswa')
            ->orderBy('peringkat')
            ->paginate(10, ['*'], 'pm_page');

        // helper nama display
        $peringkatMahasiswa->getCollection()->transform(function ($p) {
            $p->nama_display = $p->nama_tpk ?? '-';
            return $p;
        });

        return view('koordinator.peringkat.index', compact('peringkatKelompok', 'peringkatMahasiswa'));
    }

    /* =========================================================
     * CREATE FORM (nilai mentah TPK)
     * ========================================================= */
    public function createMahasiswa()
    {
        return view('koordinator.peringkat.create_mahasiswa');
    }

    public function createKelompok()
    {
        return view('koordinator.peringkat.create_kelompok');
    }

    /* =========================================================
     * STORE (nilai mentah TPK) + AUTO HITUNG ULANG
     * ========================================================= */
    public function storeMahasiswa(Request $request)
    {
        $data = $request->validate([
            'nama'           => 'required|string|max:255',
            'keaktifan'      => 'required|numeric',
            'nilai_kelompok' => 'required|numeric',
            'nilai_dosen'    => 'required|numeric',
        ]);

        TpkMahasiswa::create($data);

        // auto hitung ulang peringkat mahasiswa
        $this->calculateMahasiswa();

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Nilai mahasiswa ditambahkan & peringkat mahasiswa diperbarui.');
    }

    public function storeKelompok(Request $request)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:255',
            'review_uts' => 'required|numeric',
            'review_uas' => 'required|numeric',
        ]);

        TpkKelompok::create($data);

        // auto hitung ulang peringkat kelompok
        $this->calculateKelompok();

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Nilai kelompok ditambahkan & peringkat kelompok diperbarui.');
    }

    /* =========================================================
     * HITUNG ULANG (manual trigger) - /koordinator/peringkat/calculate?type=...
     * ========================================================= */
    public function calculate(Request $request)
    {
        $type = $request->get('type', 'mahasiswa');

        if ($type === 'kelompok') {
            $this->calculateKelompok();
            return redirect()->route('koordinator.peringkat.index')
                ->with('success', 'Peringkat kelompok berhasil dihitung ulang.');
        }

        $this->calculateMahasiswa();
        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Peringkat mahasiswa berhasil dihitung ulang.');
    }

    /* =========================================================
     * EDIT / UPDATE (hanya edit hasil peringkat yang sudah jadi)
     * ========================================================= */
    public function edit(Peringkat $peringkat)
    {
        return view('koordinator.peringkat.edit', compact('peringkat'));
    }

    public function update(Request $request, Peringkat $peringkat)
    {
        $data = $request->validate([
            'nama_tpk'    => 'nullable|string|max:255',
            'nilai_total' => 'required|numeric|min:0',
        ]);

        // untuk kelompok: nama wajib
        if ($peringkat->jenis === 'kelompok' && empty($data['nama_tpk'])) {
            return back()->with('error', 'Nama kelompok wajib diisi.')->withInput();
        }

        $peringkat->update($data);

        // auto susun ulang ranking
        $this->recalculateRanking($peringkat->jenis);

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data berhasil diupdate & peringkat otomatis diperbarui.');
    }

    /* =========================================================
     * DELETE (soft) + UNDO (restore)
     * ========================================================= */
    public function destroy(Peringkat $peringkat)
    {
        $jenis = $peringkat->jenis;
        $id = $peringkat->id;

        $peringkat->delete();

        $this->recalculateRanking($jenis);

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data berhasil dihapus.')
            ->with('undo_id', $id);
    }

    public function restore($id)
    {
        $peringkat = Peringkat::withTrashed()->findOrFail($id);
        $jenis = $peringkat->jenis;

        $peringkat->restore();

        $this->recalculateRanking($jenis);

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data berhasil dikembalikan (undo).');
    }

    /* =========================================================
     * CORE: CALCULATE TPK -> SIMPAN KE peringkats
     * ========================================================= */

    private function calculateKelompok(): void
    {
        $data = TpkKelompok::all();
        if ($data->isEmpty()) return;

        // AHP (2 kriteria): uts & uas
        $pairwiseMatrix = [
            [1,   1 / 2],
            [2,   1],
        ];
        $weights = $this->calculateWeightsFromPairwise($pairwiseMatrix);

        $decisionMatrix = [];
        $names = [];
        foreach ($data as $row) {
            $names[] = $row->nama;
            $decisionMatrix[] = [
                (float) $row->review_uts,
                (float) $row->review_uas,
            ];
        }

        $criteriaTypes = ['benefit', 'benefit'];
        $normalized = $this->normalizeDecisionMatrix($decisionMatrix, $criteriaTypes);
        $scores = $this->calculateScores($normalized, $weights);

        $ranking = [];
        foreach ($scores as $idx => $score) {
            $ranking[] = ['nama' => $names[$idx], 'score' => $score];
        }
        usort($ranking, fn($a, $b) => $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking) {
            // hapus hasil lama
            Peringkat::where('jenis', 'kelompok')->delete();

            foreach ($ranking as $i => $r) {
                Peringkat::create([
                    'jenis'        => 'kelompok',
                    'nama_tpk'     => $r['nama'],
                    'mahasiswa_id' => null,
                    'mata_kuliah'  => 'PBL',
                    'nilai_total'  => round($r['score'], 4), // 0-1
                    'peringkat'    => $i + 1,
                    'semester'     => null,
                    'tahun_ajaran' => null,
                ]);
            }
        });
    }

    private function calculateMahasiswa(): void
    {
        $data = TpkMahasiswa::all();
        if ($data->isEmpty()) return;

        // AHP (3 kriteria): keaktifan, nilai_kelompok, nilai_dosen
        $pairwiseMatrix = [
            [1,      2,      1 / 7],
            [1 / 2,  1,      1 / 7],
            [7,      7,      1],
        ];
        $weights = $this->calculateWeightsFromPairwise($pairwiseMatrix);

        $decisionMatrix = [];
        $names = [];
        foreach ($data as $row) {
            $names[] = $row->nama;
            $decisionMatrix[] = [
                (float) $row->keaktifan,
                (float) $row->nilai_kelompok,
                (float) $row->nilai_dosen,
            ];
        }

        $criteriaTypes = ['benefit', 'benefit', 'benefit'];
        $normalized = $this->normalizeDecisionMatrix($decisionMatrix, $criteriaTypes);
        $scores = $this->calculateScores($normalized, $weights);

        $ranking = [];
        foreach ($scores as $idx => $score) {
            $ranking[] = ['nama' => $names[$idx], 'score' => $score];
        }
        usort($ranking, fn($a, $b) => $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking) {
            Peringkat::where('jenis', 'mahasiswa')->delete();

            foreach ($ranking as $i => $r) {
                Peringkat::create([
                    'jenis'        => 'mahasiswa',
                    'nama_tpk'     => $r['nama'],
                    'mahasiswa_id' => null, // hasil TPK belum mapping nim
                    'mata_kuliah'  => 'PBL',
                    'nilai_total'  => round($r['score'], 4), // 0-1
                    'peringkat'    => $i + 1,
                    'semester'     => null,
                    'tahun_ajaran' => null,
                ]);
            }
        });
    }

    /**
     * AUTO ranking ulang untuk 1 jenis (setelah edit/delete/restore)
     */
    private function recalculateRanking(string $jenis): void
    {
        DB::transaction(function () use ($jenis) {
            $rows = Peringkat::where('jenis', $jenis)
                ->orderByDesc('nilai_total')
                ->orderBy('id')
                ->get();

            foreach ($rows as $i => $row) {
                $row->update(['peringkat' => $i + 1]);
            }
        });
    }

    /* =========================================================
     * HELPER AHP + SAW
     * ========================================================= */
    private function calculateWeightsFromPairwise(array $pairwiseMatrix): array
    {
        $n = count($pairwiseMatrix);

        $columnSums = [];
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) $sum += $pairwiseMatrix[$i][$j];
            $columnSums[$j] = $sum;
        }

        $normalizedMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            $row = [];
            for ($j = 0; $j < $n; $j++) {
                $row[] = $pairwiseMatrix[$i][$j] / $columnSums[$j];
            }
            $normalizedMatrix[] = $row;
        }

        $weights = [];
        foreach ($normalizedMatrix as $row) {
            $weights[] = array_sum($row) / $n;
        }

        return $weights;
    }

    private function normalizeDecisionMatrix(array $decisionMatrix, array $criteriaTypes): array
    {
        $rows = count($decisionMatrix);
        $cols = count($decisionMatrix[0]);

        $maxValues = array_fill(0, $cols, PHP_FLOAT_MIN);
        $minValues = array_fill(0, $cols, PHP_FLOAT_MAX);

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                $value = $decisionMatrix[$i][$j];
                $maxValues[$j] = max($maxValues[$j], $value);
                $minValues[$j] = min($minValues[$j], $value);
            }
        }

        $normalizedMatrix = [];
        for ($i = 0; $i < $rows; $i++) {
            $row = [];
            for ($j = 0; $j < $cols; $j++) {
                $value  = $decisionMatrix[$i][$j];
                $isCost = isset($criteriaTypes[$j]) && $criteriaTypes[$j] === 'cost';

                $row[] = $isCost
                    ? ($value != 0 ? $minValues[$j] / $value : 0)
                    : ($maxValues[$j] != 0 ? $value / $maxValues[$j] : 0);
            }
            $normalizedMatrix[] = $row;
        }

        return $normalizedMatrix;
    }

    private function calculateScores(array $normalizedMatrix, array $weights): array
    {
        $scores = [];
        foreach ($normalizedMatrix as $row) {
            $sum = 0;
            foreach ($row as $idx => $value) $sum += $value * $weights[$idx];
            $scores[] = $sum;
        }
        return $scores;
    }
}
