<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Peringkat;
use App\Models\TpkMahasiswa;
use App\Models\TpkKelompok;
use App\Models\BobotPeringkat;

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

    public function edit(string $type, int $id)
    {
        $item = $type === 'kelompok'
            ? TpkKelompok::findOrFail($id)
            : TpkMahasiswa::findOrFail($id);

        return view('koordinator.peringkat.edit', compact('item', 'type'));
    }

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

    /* ===================== ATUR BOBOT ===================== */

    public function bobot()
    {
        // ambil bobot terakhir, kalau belum ada buat default
        $mhs = BobotPeringkat::firstOrCreate(
            ['jenis' => 'mahasiswa'],
            ['mhs_keaktifan' => 30, 'mhs_nilai_kelompok' => 30, 'mhs_nilai_dosen' => 40]
        );

        $klp = BobotPeringkat::firstOrCreate(
            ['jenis' => 'kelompok'],
            ['klp_review_uts' => 50, 'klp_review_uas' => 50]
        );

        return view('koordinator.peringkat.bobot', compact('mhs', 'klp'));
    }

    public function storeBobot(Request $request)
    {
        $data = $request->validate([
            // mahasiswa
            'mhs_keaktifan'      => 'required|integer|min:0|max:100',
            'mhs_nilai_kelompok' => 'required|integer|min:0|max:100',
            'mhs_nilai_dosen'    => 'required|integer|min:0|max:100',
            // kelompok
            'klp_review_uts'     => 'required|integer|min:0|max:100',
            'klp_review_uas'     => 'required|integer|min:0|max:100',
        ]);

        $sumMhs = $data['mhs_keaktifan'] + $data['mhs_nilai_kelompok'] + $data['mhs_nilai_dosen'];
        $sumKlp = $data['klp_review_uts'] + $data['klp_review_uas'];

        // aturan kamu: GA BOLEH LEBIH DARI 100%
        if ($sumMhs > 100) {
            return back()->withErrors(['bobot_mhs' => 'Total bobot Mahasiswa tidak boleh lebih dari 100%.'])->withInput();
        }
        if ($sumKlp > 100) {
            return back()->withErrors(['bobot_klp' => 'Total bobot Kelompok tidak boleh lebih dari 100%.'])->withInput();
        }

        BobotPeringkat::updateOrCreate(
            ['jenis' => 'mahasiswa'],
            [
                'mhs_keaktifan' => $data['mhs_keaktifan'],
                'mhs_nilai_kelompok' => $data['mhs_nilai_kelompok'],
                'mhs_nilai_dosen' => $data['mhs_nilai_dosen'],
            ]
        );

        BobotPeringkat::updateOrCreate(
            ['jenis' => 'kelompok'],
            [
                'klp_review_uts' => $data['klp_review_uts'],
                'klp_review_uas' => $data['klp_review_uas'],
            ]
        );

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Bobot berhasil disimpan.');
    }

    /* ===================== CORE SAW ===================== */

    private function calculateKelompok(): void
    {
        $rows = TpkKelompok::query()->get();
        if ($rows->isEmpty()) return;

        $b = BobotPeringkat::firstOrCreate(
            ['jenis' => 'kelompok'],
            ['klp_review_uts' => 50, 'klp_review_uas' => 50]
        );

        // bobot persen -> dinormalisasi kalau total < 100
        $w = $this->normalizeWeights([
            (int)$b->klp_review_uts,
            (int)$b->klp_review_uas,
        ]);

        $decision = [];
        $items = [];
        foreach ($rows as $r) {
            $items[] = ['id' => $r->id, 'nama' => $r->nama];
            $decision[] = [(float)$r->review_uts, (float)$r->review_uas];
        }

        $normalized = $this->normalizeDecisionMatrix($decision, ['benefit', 'benefit']); // SAW
        $scores = $this->calculateScores($normalized, $w);

        $ranking = [];
        foreach ($scores as $i => $score) {
            $ranking[] = [
                'tpk_id' => $items[$i]['id'],
                'nama'   => $items[$i]['nama'],
                'score'  => $score,
            ];
        }
        usort($ranking, fn($a,$b)=> $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking) {
            Peringkat::where('jenis', 'kelompok')->delete();

            foreach ($ranking as $i => $r) {
                Peringkat::create([
                    'jenis'        => 'kelompok',
                    'nama_tpk'     => $r['nama'],
                    'mahasiswa_id' => null,
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

    private function calculateMahasiswa(): void
    {
        $rows = TpkMahasiswa::query()->get();
        if ($rows->isEmpty()) return;

        $b = BobotPeringkat::firstOrCreate(
            ['jenis' => 'mahasiswa'],
            ['mhs_keaktifan' => 30, 'mhs_nilai_kelompok' => 30, 'mhs_nilai_dosen' => 40]
        );

        $w = $this->normalizeWeights([
            (int)$b->mhs_keaktifan,
            (int)$b->mhs_nilai_kelompok,
            (int)$b->mhs_nilai_dosen,
        ]);

        $decision = [];
        $items = [];
        foreach ($rows as $r) {
            $items[] = ['id' => $r->id, 'nama' => $r->nama];
            $decision[] = [
                (float)$r->keaktifan,
                (float)$r->nilai_kelompok,
                (float)$r->nilai_dosen,
            ];
        }

        $normalized = $this->normalizeDecisionMatrix($decision, ['benefit','benefit','benefit']);
        $scores = $this->calculateScores($normalized, $w);

        $ranking = [];
        foreach ($scores as $i => $score) {
            $ranking[] = [
                'tpk_id' => $items[$i]['id'],
                'nama'   => $items[$i]['nama'],
                'score'  => $score,
            ];
        }
        usort($ranking, fn($a,$b)=> $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking) {
            Peringkat::where('jenis', 'mahasiswa')->delete();

            foreach ($ranking as $i => $r) {
                Peringkat::create([
                    'jenis'        => 'mahasiswa',
                    'nama_tpk'     => $r['nama'],
                    'mahasiswa_id' => null,
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

    /**
     * Kalau total bobot < 100, dinormalisasi ke total 1 (biar SAW bener).
     * Kalau semua 0, fallback ke rata.
     */
    private function normalizeWeights(array $weightsPercent): array
    {
        $sum = array_sum($weightsPercent);
        $n = count($weightsPercent);

        if ($sum <= 0) {
            return array_fill(0, $n, 1 / $n);
        }

        return array_map(fn($w) => $w / $sum, $weightsPercent); // jadi total = 1
    }

    private function normalizeDecisionMatrix(array $decisionMatrix, array $criteriaTypes): array
    {
        $rows = count($decisionMatrix);
        $cols = count($decisionMatrix[0]);

        $maxValues = array_fill(0, $cols, PHP_FLOAT_MIN);
        $minValues = array_fill(0, $cols, PHP_FLOAT_MAX);

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                $v = $decisionMatrix[$i][$j];
                $maxValues[$j] = max($maxValues[$j], $v);
                $minValues[$j] = min($minValues[$j], $v);
            }
        }

        $normalized = [];
        for ($i = 0; $i < $rows; $i++) {
            $row = [];
            for ($j = 0; $j < $cols; $j++) {
                $v = $decisionMatrix[$i][$j];
                $isCost = isset($criteriaTypes[$j]) && $criteriaTypes[$j] === 'cost';

                $row[] = $isCost
                    ? ($v != 0 ? $minValues[$j] / $v : 0)
                    : ($maxValues[$j] != 0 ? $v / $maxValues[$j] : 0);
            }
            $normalized[] = $row;
        }

        return $normalized;
    }

    private function calculateScores(array $normalizedMatrix, array $weights): array
    {
        $scores = [];
        foreach ($normalizedMatrix as $row) {
            $sum = 0;
            foreach ($row as $idx => $value) {
                $sum += $value * ($weights[$idx] ?? 0);
            }
            $scores[] = $sum;
        }
        return $scores;
    }
}
