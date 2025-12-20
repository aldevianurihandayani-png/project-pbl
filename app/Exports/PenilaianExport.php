<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\MataKuliah;
use App\Models\Rubrik;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenilaianExport implements FromCollection, WithHeadings
{
    protected ?string $kodeMk;
    protected ?string $kelas;

    protected $rubrics;

    public function __construct(?string $kodeMk = null, ?string $kelas = null)
    {
        $this->kodeMk = $kodeMk;
        $this->kelas  = $kelas;

        // ambil rubrik sekali di constructor
        $this->rubrics = collect();

        if ($this->kodeMk) {
            $rubrikTable = (new Rubrik)->getTable();
            $q = Rubrik::query();

            if (Schema::hasColumn($rubrikTable, 'kode_mk')) {
                $q->where('kode_mk', $this->kodeMk);
            } elseif (Schema::hasColumn($rubrikTable, 'mata_kuliah_kode')) {
                $q->where('mata_kuliah_kode', $this->kodeMk);
            }

            $this->rubrics = $q->orderBy('urutan')->orderBy('id')->get([
                'id','nama_rubrik','bobot'
            ]);
        }
    }

    /**
     * Ambil data utama
     */
    public function collection()
    {
        if (!$this->kodeMk) {
            return collect();
        }

        // tabel mahasiswa dinamis
        $mTable = Schema::hasTable('mahasiswas') ? 'mahasiswas' : 'mahasiswa';
        $hasKelas = Schema::hasColumn($mTable, 'kelas');

        // ambil mahasiswa
        $mhsQ = DB::table($mTable)->select('nim','nama');
        if ($hasKelas) {
            $mhsQ->addSelect('kelas');
            if ($this->kelas) {
                $mhsQ->where('kelas', $this->kelas);
            }
        } else {
            $mhsQ->addSelect(DB::raw('NULL as kelas'));
        }

        $mahasiswa = $mhsQ->orderBy('nama')->get();

        if ($mahasiswa->isEmpty() || $this->rubrics->isEmpty()) {
            return collect();
        }

        // ambil nilai dari penilaian_items
        $nilaiMap = DB::table('penilaian_items')
            ->select('mahasiswa_nim as nim', 'rubrik_id', 'nilai')
            ->whereIn('mahasiswa_nim', $mahasiswa->pluck('nim'))
            ->whereIn('rubrik_id', $this->rubrics->pluck('id'))
            ->get()
            ->groupBy('nim');

        // build rows
        return $mahasiswa->map(function ($m) use ($nilaiMap) {
            $row = [
                $m->nim,
                $m->nama,
                $m->kelas ?? '',
            ];

            $final = 0;

            foreach ($this->rubrics as $r) {
                $val = optional(
                    collect($nilaiMap->get($m->nim, []))
                        ->firstWhere('rubrik_id', $r->id)
                )->nilai;

                $row[] = $val ?? '';

                if (is_numeric($val)) {
                    $final += ((float)$val) * ((float)$r->bobot / 100);
                }
            }

            $row[] = round($final, 2);

            return $row;
        });
    }

    /**
     * Header Excel
     */
    public function headings(): array
    {
        if (!$this->kodeMk) {
            return [];
        }

        $headings = [
            'NIM',
            'Nama Mahasiswa',
            'Kelas',
        ];

        foreach ($this->rubrics as $r) {
            $headings[] = $r->nama_rubrik . ' (' . (int)$r->bobot . '%)';
        }

        $headings[] = 'Nilai Akhir';

        return $headings;
    }
}
