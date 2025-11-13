<?php

namespace App\Http\Controllers;

use App\Models\TpkKelompok;
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

        return view('tpk.index', compact('data_tpk'));
    }

    /**
     * Form input data mahasiswa PBL.
     */
    public function create()
    {
        return view('tpk.create');
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

        return redirect()
            ->route('tpkk.index')
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
                ->route('tpkk.index')
                ->with('error', 'Data mahasiswa PBL belum tersedia untuk dihitung.');
        }

        /**
            * 1. Matriks perbandingan berpasangan (pairwise comparison) untuk kriteria
         */
        $pairwiseMatrix = [
            [1,      2,      1 / 7],  // Keaktifan
            [1 / 2,  1,      1 / 7],  // Nilai Kelompok
            [7,      7,      1],      // Nilai Dosen
        ];

        // 2. Hitung bobot kriteria dengan AHP
        $weights = $this->calculateWeightsFromPairwise($pairwiseMatrix);
        // $weights[0] = bobot Keaktifan
        // $weights[1] = bobot Nilai Kelompok
        // $weights[2] = bobot Nilai Dosen

        /**
         * 3. Matriks keputusan untuk SAW
         */
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

        // 4. Semua kriteria bertipe benefit (semakin besar semakin baik)
        $criteriaTypes = ['benefit', 'benefit', 'benefit'];

        // 5. Normalisasi matriks keputusan dengan SAW
        $normalizedMatrix = $this->normalizeDecisionMatrix($decisionMatrix, $criteriaTypes);

        // 6. Hitung skor akhir SAW
        $scores = $this->calculateScores($normalizedMatrix, $weights);

        // 7. Gabungkan skor dengan data mahasiswa dan urutkan
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

        // Urutkan dari score terbesar ke terkecil
        usort($ranking, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $best_student = $ranking[0];

        // Tampilkan ke view hasil
        return view('tpk.hasil', [
            'ranking'      => $ranking,
            'best_student' => $best_student,
            'weights'      => [
                'keaktifan'      => $weights[0],
                'nilai_kelompok' => $weights[1],
                'nilai_dosen'    => $weights[2],
            ],
        ]);
    }

    /**
     * Hitung bobot AHP dari matriks pairwise (normalisasi kolom + rata-rata baris).
     */
    private function calculateWeightsFromPairwise(array $pairwiseMatrix): array
    {
        $columnSums = [];
        $n = count($pairwiseMatrix);

        // Hitung jumlah tiap kolom
        for ($j = 0; $j < $n; $j++) {
            $sum = 0;
            for ($i = 0; $i < $n; $i++) {
                $sum += $pairwiseMatrix[$i][$j];
            }
            $columnSums[$j] = $sum;
        }

        // Normalisasi matriks
        $normalizedMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            $row = [];
            for ($j = 0; $j < $n; $j++) {
                $row[] = $pairwiseMatrix[$i][$j] / $columnSums[$j];
            }
            $normalizedMatrix[] = $row;
        }

        // Bobot = rata-rata tiap baris
        $weights = [];
        foreach ($normalizedMatrix as $row) {
            $weights[] = array_sum($row) / $n;
        }

        return $weights;
    }

    /**
     * Normalisasi matriks keputusan untuk SAW.
     *
     * $criteriaTypes: ['benefit'|'cost', ...]
     */
    private function normalizeDecisionMatrix(array $decisionMatrix, array $criteriaTypes): array
    {
        $normalizedMatrix = [];
        $rows = count($decisionMatrix);
        $cols = count($decisionMatrix[0]);

        // Cari max/min tiap kolom
        $maxValues = array_fill(0, $cols, PHP_FLOAT_MIN);
        $minValues = array_fill(0, $cols, PHP_FLOAT_MAX);

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                $value = $decisionMatrix[$i][$j];
                if ($value > $maxValues[$j]) $maxValues[$j] = $value;
                if ($value < $minValues[$j]) $minValues[$j] = $value;
            }
        }

        // Normalisasi
        for ($i = 0; $i < $rows; $i++) {
            $row = [];
            for ($j = 0; $j < $cols; $j++) {
                $value   = $decisionMatrix[$i][$j];
                $isCost  = isset($criteriaTypes[$j]) && $criteriaTypes[$j] === 'cost';

                if ($isCost) {
                    // Kriteria cost: min / value
                    $row[] = $value != 0 ? $minValues[$j] / $value : 0;
                } else {
                    // Kriteria benefit: value / max
                    $row[] = $maxValues[$j] != 0 ? $value / $maxValues[$j] : 0;
                }
            }
            $normalizedMatrix[] = $row;
        }

        return $normalizedMatrix;
    }

    /**
     * Hitung skor SAW dari matriks normal dan bobot kriteria.
     */
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
