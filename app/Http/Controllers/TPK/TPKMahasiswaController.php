<?php

namespace App\Http\Controllers\TPK;

use App\Http\Controllers\Controller;
use App\Models\TpkMahasiswa;
use Illuminate\Http\Request;

class TPKMahasiswaController extends Controller
{
    /**
     * Halaman daftar data mahasiswa PBL.
     */
    public function index()
    {
        $data_tpk = TpkMahasiswa::all();

        // VIEW-NYA SESUAI FOLDERMU: resources/views/tpk/tpkmahasiswa/index.blade.php
        return view('tpk.tpkmahasiswa.index', compact('data_tpk'));
    }

    /**
     * Form input data mahasiswa PBL.
     */
    public function create()
    {
        // resources/views/tpk/tpkmahasiswa/create.blade.php
        return view('tpk.tpkmahasiswa.create');
    }

    /**
     * Simpan data mahasiswa PBL.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'           => 'required|string|max:255',
            'keaktifan'      => 'required|numeric',
            'nilai_kelompok' => 'required|numeric',
            'nilai_dosen'    => 'required|numeric',
        ]);

        TpkMahasiswa::create($validatedData);

        // NAMA ROUTE-NYA: tpk.mahasiswa.index
        return redirect()
            ->route('tpk.mahasiswa.index')
            ->with('success', 'Data mahasiswa PBL berhasil ditambahkan!');
    }

    /**
     * Hitung bobot AHP + ranking SAW untuk mahasiswa terbaik PBL.
     */
    public function calculate()
    {
        $data_tpk = TpkMahasiswa::all();

        if ($data_tpk->isEmpty()) {
            return redirect()
                ->route('tpk.mahasiswa.index')
                ->with('error', 'Data mahasiswa PBL belum tersedia untuk dihitung.');
        }

        // 1. Matriks perbandingan AHP (dari gambar)
        $pairwiseMatrix = [
            [1,      2,      1 / 7],  // Keaktifan
            [1 / 2,  1,      1 / 7],  // Nilai Kelompok
            [7,      7,      1],      // Nilai Dosen
        ];

        // 2. Hitung bobot kriteria
        $weights = $this->calculateWeightsFromPairwise($pairwiseMatrix);

        // 3. Matriks keputusan SAW
        $decisionMatrix  = [];
        $mahasiswaNames  = [];

        foreach ($data_tpk as $row) {
            $mahasiswaNames[] = $row->nama;

            $decisionMatrix[] = [
                (float) $row->keaktifan,
                (float) $row->nilai_kelompok,
                (float) $row->nilai_dosen,
            ];
        }

        // 4. Semua kriteria benefit
        $criteriaTypes = ['benefit', 'benefit', 'benefit'];

        // 5. Normalisasi SAW
        $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix, $criteriaTypes);

        // 6. Hitung skor SAW
        $scores = $this->calculateScores($normalizedMatrix, $weights);

        // 7. Gabung & urutkan ranking
        $ranking = [];
        foreach ($scores as $index => $score) {
            $ranking[] = [
                'nama'           => $mahasiswaNames[$index],
                'keaktifan'      => $decisionMatrix[$index][0],
                'nilai_kelompok' => $decisionMatrix[$index][1],
                'nilai_dosen'    => $decisionMatrix[$index][2],
                'score'          => round($score, 4),
            ];
        }

        usort($ranking, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $best_student = $ranking[0];

        // VIEW HASIL: resources/views/tpk/tpkmahasiswa/hasil.blade.php
        return view('tpk.tpkmahasiswa.hasil', [
            'ranking'      => $ranking,
            'best_student' => $best_student,
            'weights'      => [
                'keaktifan'      => $weights[0],
                'nilai_kelompok' => $weights[1],
                'nilai_dosen'    => $weights[2],
            ],
        ]);
    }

    // ====== FUNGSI BANTU AHP & SAW TIDAK DIUBAH ======

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
