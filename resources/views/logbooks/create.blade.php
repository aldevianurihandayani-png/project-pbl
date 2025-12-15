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
    public function index()
    {
        $peringkatKelompok = Peringkat::where('jenis', 'kelompok')
            ->orderBy('peringkat')
            ->paginate(10, ['*'], 'pk_page');

        $peringkatMahasiswa = Peringkat::where('jenis', 'mahasiswa')
            ->orderBy('peringkat')
            ->paginate(10, ['*'], 'pm_page');

        $peringkatMahasiswa->getCollection()->transform(function ($p) {
            $p->nama_display = $p->nama_tpk ?? '-';
            return $p;
        });

        return view('koordinator.peringkat.index', compact('peringkatKelompok', 'peringkatMahasiswa'));
    }

    public function createMahasiswa()
    {
        return view('koordinator.peringkat.create_mahasiswa');
    }

    public function createKelompok()
    {
        return view('koordinator.peringkat.create_kelompok');
    }

    public function storeMahasiswa(Request $request)
    {
        $data = $request->validate([
            'nama'           => 'required|string|max:255',
            'keaktifan'      => 'required|numeric',
            'nilai_kelompok' => 'required|numeric',
            'nilai_dosen'    => 'required|numeric',
        ]);

        TpkMahasiswa::create($data);
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
        $this->calculateKelompok();

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Nilai kelompok ditambahkan & peringkat kelompok diperbarui.');
    }

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

    // GET /koordinator/peringkat/{type}/{id}/edit
    public function edit(string $type, int $id)
    {
        $item = $type === 'kelompok'
            ? TpkKelompok::findOrFail($id)
            : TpkMahasiswa::findOrFail($id);

        return view('koordinator.peringkat.edit', compact('item', 'type'));
    }

    // PUT /koordinator/peringkat/{type}/{id}
    public function update(Request $request, string $type, int $id)
    {
        if ($type === 'kelompok') {
            $item = TpkKelompok::findOrFail($id);

            $data = $request->validate([
                'nama'       => 'required|string|max:255',
                'review_uts' => 'required|numeric',
                'review_uas' => 'required|numeric',
            ]);

            $item->update($data);
            $this->calculateKelompok();
        } else {
            $item = TpkMahasiswa::findOrFail($id);

            $data = $request->validate([
                'nama'           => 'required|string|max:255',
                'keaktifan'      => 'required|numeric',
                'nilai_kelompok' => 'required|numeric',
                'nilai_dosen'    => 'required|numeric',
            ]);

            $item->update($data);
            $this->calculateMahasiswa();
        }

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data berhasil diupdate & peringkat diperbarui.');
    }

    // POST /koordinator/peringkat/tpk/destroy
    public function destroyTpk(Request $request)
    {
        $data = $request->validate([
            'tpk_type' => 'required|in:mahasiswa,kelompok',
            'tpk_id'   => 'required|integer',
        ]);

        if ($data['tpk_type'] === 'mahasiswa') {
            TpkMahasiswa::findOrFail($data['tpk_id'])->delete();
            $this->calculateMahasiswa();
        } else {
            TpkKelompok::findOrFail($data['tpk_id'])->delete();
            $this->calculateKelompok();
        }

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data berhasil dihapus permanen & peringkat diperbarui.');
    }

    /* =========================
     * HITUNG KELOMPOK -> SIMPAN peringkats
     * ========================= */
    private function calculateKelompok(): void
    {
        $data = TpkKelompok::all();
        if ($data->isEmpty()) {
            // kalau kosong, bersihin hasil juga biar ga nyisa
            Peringkat::where('jenis', 'kelompok')->delete();
            return;
        }

        $pairwiseMatrix = [
            [1,   1 / 2],
            [2,   1],
        ];
        $weights = $this->calculateWeightsFromPairwise($pairwiseMatrix);

        $decisionMatrix = [];
        $items = [];
        foreach ($data as $row) {
            $items[] = ['id' => $row->id, 'nama' => $row->nama];
            $decisionMatrix[] = [(float)$row->review_uts, (float)$row->review_uas];
        }

        $normalized = $this->normalizeDecisionMatrix($decisionMatrix, ['benefit', 'benefit']);
        $scores = $this->calculateScores($normalized, $weights);

        $ranking = [];
        foreach ($scores as $idx => $score) {
            $ranking[] = [
                'tpk_id' => $items[$idx]['id'],
                'nama'   => $items[$idx]['nama'],
                'score'  => $score,
            ];
        }
        usort($ranking, fn($a, $b) => $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking) {
            // HARD RESET biar ga nyisa
            Peringkat::where('jenis', 'kelompok')->delete();

            foreach ($ranking as $i => $r) {
                Peringkat::create([
                    'jenis'        => 'kelompok',
                    'nama_tpk'     => $r['nama'],
                    'mahasiswa_id' => null,          // FIX FK: harus nullable
                    'mata_kuliah'  => 'PBL',
                    'nilai_total'  => round($r['score'], 4),
                    'peringkat'    => $i + 1,
                    'semester'     => null,
                    'tahun_ajaran' => null,
                    'tpk_type'     => 'kelompok',
                    'tpk_id'       => $r['tpk_id'],
                ]);
            }
        });
    }

    /* =========================
     * HITUNG MAHASISWA -> SIMPAN peringkats
     * ========================= */
    private function calculateMahasiswa(): void
    {
        $data = TpkMahasiswa::all();
        if ($data->isEmpty()) {
            Peringkat::where('jenis', 'mahasiswa')->delete();
            return;
        }

        $pairwiseMatrix = [
            [1,      2,      1 / 7],
            [1 / 2,  1,      1 / 7],
            [7,      7,      1],
        ];
        $weights = $this->calculateWeightsFromPairwise($pairwiseMatrix);

        $decisionMatrix = [];
        $items = [];
        foreach ($data as $row) {
            $items[] = ['id' => $row->id, 'nama' => $row->nama];
            $decisionMatrix[] = [
                (float)$row->keaktifan,
                (float)$row->nilai_kelompok,
                (float)$row->nilai_dosen,
            ];
        }

        $normalized = $this->normalizeDecisionMatrix($decisionMatrix, ['benefit', 'benefit', 'benefit']);
        $scores = $this->calculateScores($normalized, $weights);

        $ranking = [];
        foreach ($scores as $idx => $score) {
            $ranking[] = [
                'tpk_id' => $items[$idx]['id'],
                'nama'   => $items[$idx]['nama'],
                'score'  => $score,
            ];
        }
        usort($ranking, fn($a, $b) => $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking) {
            Peringkat::where('jenis', 'mahasiswa')->delete();

            foreach ($ranking as $i => $r) {
                Peringkat::create([
                    'jenis'        => 'mahasiswa',
                    'nama_tpk'     => $r['nama'],
                    'mahasiswa_id' => null,         // sementara null (karena TPK mahasiswa ga punya nim)
                    'mata_kuliah'  => 'PBL',
                    'nilai_total'  => round($r['score'], 4),
                    'peringkat'    => $i + 1,
                    'semester'     => null,
                    'tahun_ajaran' => null,
                    'tpk_type'     => 'mahasiswa',
                    'tpk_id'       => $r['tpk_id'],
                ]);
            }
        });
    }

    /* =========================
     * HELPER AHP + SAW
     * ========================= */
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
            for ($j = 0; $j < $n; $j++) $row[] = $pairwiseMatrix[$i][$j] / $columnSums[$j];
            $normalizedMatrix[] = $row;
        }

        $weights = [];
        foreach ($normalizedMatrix as $row) $weights[] = array_sum($row) / $n;
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
                $value = $decisionMatrix[$i][$j];
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
