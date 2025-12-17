<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Peringkat;
use App\Models\TpkMahasiswa;
use App\Models\TpkKelompok;
use App\Models\BobotPeringkat;
use App\Models\Mahasiswa;

class PeringkatController extends Controller
{
    /* =========================================================
     * INDEX
     * ========================================================= */
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

    /* =========================================================
     * CREATE FORM (Mahasiswa)
     * ========================================================= */
    public function createMahasiswa(Request $request)
    {
        $kelasAktif = $request->get('kelas'); // contoh: "Kelas A"

        $kelasList = Mahasiswa::query()
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $mahasiswaList = collect();
        if (!empty($kelasAktif)) {
            $mahasiswaList = Mahasiswa::query()
                ->where('kelas', $kelasAktif)
                ->orderBy('nama')
                ->get(['nim', 'nama', 'kelas']);
        }

        return view('koordinator.peringkat.create_mahasiswa', [
            'kelasList'     => $kelasList,
            'kelasAktif'    => $kelasAktif,
            'mahasiswaList' => $mahasiswaList,
        ]);
    }

    public function createKelompok()
    {
        return view('koordinator.peringkat.create_kelompok');
    }

    /* =========================================================
     * STORE (Mahasiswa)
     * ========================================================= */
    public function storeMahasiswa(Request $request)
    {
        $data = $request->validate([
            'kelas'          => 'required|string|max:50',
            'mahasiswa_nim'  => 'required|string|max:30',
            'keaktifan'      => 'required|numeric|min:0|max:100',
            'nilai_kelompok' => 'required|numeric|min:0|max:100',
            'nilai_dosen'    => 'required|numeric|min:0|max:100',
        ]);

        $mhs = Mahasiswa::query()
            ->where('nim', $data['mahasiswa_nim'])
            ->where('kelas', $data['kelas'])
            ->first();

        if (!$mhs) {
            return back()
                ->withErrors(['mahasiswa_nim' => 'Mahasiswa tidak ditemukan / tidak sesuai kelas yang dipilih.'])
                ->withInput();
        }

        // OPTIONAL: cegah input dobel untuk mahasiswa yang sama di kelas yang sama
        $exists = TpkMahasiswa::query()
            ->where('mahasiswa_nim', $mhs->nim)
            ->where('kelas', $mhs->kelas)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['mahasiswa_nim' => 'Nilai TPK untuk mahasiswa ini di kelas tersebut sudah ada. Silakan edit.'])
                ->withInput();
        }

        TpkMahasiswa::create([
            'kelas'          => $mhs->kelas,
            'mahasiswa_nim'  => $mhs->nim,
            'nama'           => $mhs->nama,
            'keaktifan'      => $data['keaktifan'],
            'nilai_kelompok' => $data['nilai_kelompok'],
            'nilai_dosen'    => $data['nilai_dosen'],
        ]);

        // hitung ulang khusus kelas itu (lebih masuk akal)
        $this->calculateMahasiswa($mhs->kelas);

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Nilai mahasiswa ditambahkan & peringkat mahasiswa diperbarui.');
    }

    /* =========================================================
     * STORE (Kelompok)
     * ========================================================= */
    public function storeKelompok(Request $request)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:255',
            'review_uts' => 'required|numeric|min:0|max:100',
            'review_uas' => 'required|numeric|min:0|max:100',
        ]);

        TpkKelompok::create($data);

        $this->calculateKelompok();

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Nilai kelompok ditambahkan & peringkat kelompok diperbarui.');
    }

    /* =========================================================
     * HITUNG ULANG (manual trigger)
     * ========================================================= */
    public function calculate(Request $request)
    {
        $type = $request->get('type', 'mahasiswa');

        if ($type === 'kelompok') {
            $this->calculateKelompok();
            return redirect()->route('koordinator.peringkat.index')
                ->with('success', 'Peringkat kelompok berhasil dihitung ulang.');
        }

        // kalau mau hitung per kelas, bisa kasih param ?kelas=
        $kelas = $request->get('kelas');
        $this->calculateMahasiswa($kelas);

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Peringkat mahasiswa berhasil dihitung ulang.');
    }

    /* =========================================================
     * EDIT / UPDATE
     * ========================================================= */
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
                'review_uts' => 'required|numeric|min:0|max:100',
                'review_uas' => 'required|numeric|min:0|max:100',
            ]);

            $item->update($data);
            $this->calculateKelompok();
        } else {
            $item = TpkMahasiswa::findOrFail($id);

            $data = $request->validate([
                'keaktifan'      => 'required|numeric|min:0|max:100',
                'nilai_kelompok' => 'required|numeric|min:0|max:100',
                'nilai_dosen'    => 'required|numeric|min:0|max:100',
            ]);

            $item->update($data);

            // hitung ulang khusus kelas item tsb
            $this->calculateMahasiswa($item->kelas);
        }

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data berhasil diupdate & peringkat diperbarui.');
    }

    /* =========================================================
     * DELETE
     * ========================================================= */
    public function destroyTpk(Request $request)
    {
        $data = $request->validate([
            'tpk_type' => 'required|in:mahasiswa,kelompok',
            'tpk_id'   => 'required|integer',
        ]);

        if ($data['tpk_type'] === 'mahasiswa') {
            $item = TpkMahasiswa::findOrFail($data['tpk_id']);
            $kelas = $item->kelas;
            $item->delete();
            $this->calculateMahasiswa($kelas);
        } else {
            TpkKelompok::findOrFail($data['tpk_id'])->delete();
            $this->calculateKelompok();
        }

        return redirect()->route('koordinator.peringkat.index')
            ->with('success', 'Data berhasil dihapus permanen & peringkat diperbarui.');
    }

    /* =========================================================
     * ATUR BOBOT
     * ========================================================= */
    public function bobot()
    {
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
            'mhs_keaktifan'      => 'required|integer|min:0|max:100',
            'mhs_nilai_kelompok' => 'required|integer|min:0|max:100',
            'mhs_nilai_dosen'    => 'required|integer|min:0|max:100',
            'klp_review_uts'     => 'required|integer|min:0|max:100',
            'klp_review_uas'     => 'required|integer|min:0|max:100',
        ]);

        $sumMhs = $data['mhs_keaktifan'] + $data['mhs_nilai_kelompok'] + $data['mhs_nilai_dosen'];
        $sumKlp = $data['klp_review_uts'] + $data['klp_review_uas'];

        if ($sumMhs > 100) return back()->withErrors(['bobot_mhs' => 'Total bobot Mahasiswa tidak boleh lebih dari 100%.'])->withInput();
        if ($sumKlp > 100) return back()->withErrors(['bobot_klp' => 'Total bobot Kelompok tidak boleh lebih dari 100%.'])->withInput();

        BobotPeringkat::updateOrCreate(
            ['jenis' => 'mahasiswa'],
            [
                'mhs_keaktifan'      => $data['mhs_keaktifan'],
                'mhs_nilai_kelompok' => $data['mhs_nilai_kelompok'],
                'mhs_nilai_dosen'    => $data['mhs_nilai_dosen'],
            ]
        );

        BobotPeringkat::updateOrCreate(
            ['jenis' => 'kelompok'],
            [
                'klp_review_uts' => $data['klp_review_uts'],
                'klp_review_uas' => $data['klp_review_uas'],
            ]
        );

        return redirect()->route('koordinator.peringkat.index')->with('success', 'Bobot berhasil disimpan.');
    }

    /* =========================================================
     * CORE SAW: KELOMPOK
     * ========================================================= */
    private function calculateKelompok(): void
    {
        $rows = TpkKelompok::query()->get();
        if ($rows->isEmpty()) return;

        $b = BobotPeringkat::firstOrCreate(
            ['jenis' => 'kelompok'],
            ['klp_review_uts' => 50, 'klp_review_uas' => 50]
        );

        $w = $this->normalizeWeights([(int)$b->klp_review_uts, (int)$b->klp_review_uas]);

        $decision = [];
        $items = [];
        foreach ($rows as $r) {
            $items[] = ['id' => $r->id, 'nama' => $r->nama];
            $decision[] = [(float)$r->review_uts, (float)$r->review_uas];
        }

        $normalized = $this->normalizeDecisionMatrix($decision, ['benefit', 'benefit']);
        $scores = $this->calculateScores($normalized, $w);

        $ranking = [];
        foreach ($scores as $i => $score) {
            $ranking[] = ['tpk_id' => $items[$i]['id'], 'nama' => $items[$i]['nama'], 'score' => $score];
        }
        usort($ranking, fn($a,$b)=> $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking) {
            Peringkat::where('jenis', 'kelompok')->delete();

            foreach ($ranking as $i => $r) {
                (new Peringkat())->forceFill([
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
                ])->save();
            }
        });
    }

    /* =========================================================
     * CORE SAW: MAHASISWA (optional per kelas)
     * ========================================================= */
    private function calculateMahasiswa(?string $kelas = null): void
    {
        $query = TpkMahasiswa::query();
        if (!empty($kelas)) {
            $query->where('kelas', $kelas);
        }

        $rows = $query->get();
        if ($rows->isEmpty()) return;

        $b = BobotPeringkat::firstOrCreate(
            ['jenis' => 'mahasiswa'],
            ['mhs_keaktifan' => 30, 'mhs_nilai_kelompok' => 30, 'mhs_nilai_dosen' => 40]
        );

        $w = $this->normalizeWeights([(int)$b->mhs_keaktifan, (int)$b->mhs_nilai_kelompok, (int)$b->mhs_nilai_dosen]);

        $decision = [];
        $items = [];
        foreach ($rows as $r) {
            $items[] = [
                'id'  => $r->id,
                'nim' => $r->mahasiswa_nim,
                'nama'=> $r->nama,
                'kelas'=> $r->kelas,
            ];
            $decision[] = [(float)$r->keaktifan, (float)$r->nilai_kelompok, (float)$r->nilai_dosen];
        }

        $normalized = $this->normalizeDecisionMatrix($decision, ['benefit','benefit','benefit']);
        $scores = $this->calculateScores($normalized, $w);

        $ranking = [];
        foreach ($scores as $i => $score) {
            $ranking[] = [
                'tpk_id' => $items[$i]['id'],
                'nim'    => $items[$i]['nim'],
                'nama'   => $items[$i]['nama'],
                'kelas'  => $items[$i]['kelas'],
                'score'  => $score,
            ];
        }
        usort($ranking, fn($a,$b)=> $b['score'] <=> $a['score']);

        DB::transaction(function () use ($ranking, $kelas) {
            $del = Peringkat::where('jenis', 'mahasiswa');
            // kalau mau ranking per kelas, wajib ada kolom kelas di peringkats
            if (!empty($kelas) && \Schema::hasColumn('peringkats', 'kelas')) {
                $del->where('kelas', $kelas);
            }
            $del->delete();

            foreach ($ranking as $i => $r) {
                (new Peringkat())->forceFill([
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
                    // simpan kelas kalau kolom ada
                    'kelas'        => \Schema::hasColumn('peringkats', 'kelas') ? $r['kelas'] : null,
                ])->save();
            }
        });
    }

    /* =========================================================
     * HELPERS SAW
     * ========================================================= */
    private function normalizeWeights(array $weightsPercent): array
    {
        $sum = array_sum($weightsPercent);
        $n = count($weightsPercent);
        if ($sum <= 0) return array_fill(0, $n, 1 / $n);
        return array_map(fn($w) => $w / $sum, $weightsPercent);
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
