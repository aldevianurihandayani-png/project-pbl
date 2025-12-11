<?php

namespace App\Http\Controllers\TPK;

use App\Http\Controllers\Controller;
use App\Models\TpkKelompok;
use Illuminate\Http\Request;

class TPKKelompokController extends Controller
{
    /**
     * Halaman daftar data kelompok PBL.
     */
    public function index()
    {
        $data_tpk = TpkKelompok::all();

        // resources/views/tpk/tpkkelompok/index.blade.php
        return view('tpk.tpkkelompok.index', compact('data_tpk'));
    }

    /**
     * Form input data kelompok PBL.
     */
    public function create()
    {
        // resources/views/tpk/tpkkelompok/create.blade.php
        return view('tpk.tpkkelompok.create');
    }

    /**
     * Simpan data kelompok PBL.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'       => 'required|string|max:255',
            'review_uts' => 'required|numeric',
            'review_uas' => 'required|numeric',
        ]);

        TpkKelompok::create($validatedData);

        // route name contoh: tpk.kelompok.index
        return redirect()
            ->route('tpk.kelompok.index')
            ->with('success', 'Data kelompok PBL berhasil ditambahkan!');
    }

    /**
     * Hitung bobot AHP + ranking SAW untuk kelompok terbaik PBL.
     */
    public function calculate()
    {
        $data_tpk = TpkKelompok::all();

        if ($data_tpk->isEmpty()) {
            return redirect()
                ->route('tpk.kelompok.index')
                ->with('error', 'Data kelompok PBL belum tersedia untuk dihitung.');
        }

        // 1. Matriks perbandingan AHP (kriteria: review_uts & review_uas)
        // Contoh: UAS 2x lebih penting dari UTS
        $pairwiseMatrix = [
            [1,   1 / 2],  // review_uts
            [2,   1],      // review_uas
        ];

        // 2. Hitung bobot kriteria
        $weights = $this->calculateWeightsFromPairwise($pairwiseMatrix);

        // 3. Matriks keputusan SAW
        $decisionMatrix  = [];
        $kelompokNames   = [];

        foreach ($data_tpk as $row) {
            $kelompokNames[] = $row->nama;

            $decisionMatrix[] = [
                (float) $row->review_uts,
                (float) $row->review_uas,
            ];
        }

        // 4. Semua kriteria benefit
        $criteriaTypes = ['benefit', 'benefit'];

        // 5. Normalisasi SAW
        $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix, $criteriaTypes);

        // 6. Hitung skor SAW
        $scores = $this->calculateScores($normalizedMatrix, $weights);

        // 7. Gabung & urutkan ranking
        $ranking = [];
        foreach ($scores as $index => $score) {
            $ranking[] = [
                'nama'       => $kelompokNames[$index],
                'review_uts' => $decisionMatrix[$index][0],
                'review_uas' => $decisionMatrix[$index][1],
                'score'      => round($score, 4),
            ];
        }

        usort($ranking, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $best_group = $ranking[0];

        // VIEW HASIL: resources/views/tpk/tpkkelompok/hasil.blade.php
        return view('tpk.tpkkelompok.hasil', [
            'ranking'     => $ranking,
            'best_group'  => $best_group,
            'weights'     => [
                'review_uts' => $weights[0],
                'review_uas' => $weights[1],
            ],
        ]);
    }

    // ====== FUNGSI BANTU AHP & SAW (SAMA SEPERTI SEBELUMNYA) ======

    private function calculateWeightsFromPairwise(array $pairwiseMatrix): array
    {
        $columnSums = [];
        $n = count($pairwiseMatrix);

        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $pairwiseMatrix[$i][$j];
            }
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
        $normalizedMatrix = [];
        $rows = count($decisionMatrix);
        $cols = count($decisionMatrix[0]);

        $maxValues = array_fill(0, $cols, PHP_FLOAT_MIN);
        $minValues = array_fill(0, $cols, PHP_FLOAT_MAX);

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                $value = $decisionMatrix[$i][$j];
                if ($value > $maxValues[$j]) $maxValues[$j] = $value;
                if ($value < $minValues[$j]) $minValues[$j] = $value;
            }
        }

        for ($i = 0; $i < $rows; $i++) {
            $row = [];
            for ($j = 0; $j < $cols; $j++) {
                $value   = $decisionMatrix[$i][$j];
                $isCost  = isset($criteriaTypes[$j]) && $criteriaTypes[$j] === 'cost';

                if ($isCost) {
                    $row[] = $value != 0 ? $minValues[$j] / $value : 0;
                } else {
                    $row[] = $maxValues[$j] != 0 ? $value / $maxValues[$j] : 0;
                }
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
            foreach ($row as $index => $value) {
                $sum += $value * $weights[$index];
            }
            $scores[] = $sum;
        }

        return $scores;
    }
}
