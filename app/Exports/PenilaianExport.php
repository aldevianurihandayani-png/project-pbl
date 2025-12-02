<?php

namespace App\Exports;

use App\Models\Penilaian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PenilaianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $matakuliahKode;
    protected $kelasId;

    public function __construct(?string $matakuliahKode = null, ?int $kelasId = null)
    {
        $this->matakuliahKode = $matakuliahKode;
        $this->kelasId = $kelasId;
    }

    /**
     * Ambil data penilaian dari database dengan relasi mahasiswa
     */
    public function collection()
    {
        $query = Penilaian::with('mahasiswa')
            ->when($this->matakuliahKode, fn($q) => $q->where('matakuliah_kode', $this->matakuliahKode))
            ->when($this->kelasId, fn($q) => $q->where('kelas_id', $this->kelasId));

        return $query->get();
    }

    /**
     * Format setiap baris data yang akan diekspor ke Excel
     */
    public function map($p): array
    {
        $komp = collect($p->komponen ?? []);

        return [
            $p->mahasiswa->npm ?? '',
            $p->mahasiswa->nama ?? '',
            $p->matakuliah_kode ?? '',
            $p->kelas_id ?? '',
            $p->nilai_akhir ?? 0,
            // Ambil maksimal 3 komponen biar stabil
            $komp[0]['nama'] ?? '',
            $komp[0]['bobot'] ?? '',
            $komp[0]['skor'] ?? '',
            $komp[1]['nama'] ?? '',
            $komp[1]['bobot'] ?? '',
            $komp[1]['skor'] ?? '',
            $komp[2]['nama'] ?? '',
            $komp[2]['bobot'] ?? '',
            $komp[2]['skor'] ?? '',
        ];
    }

    /**
     * Header kolom di file Excel
     */
    public function headings(): array
    {
        return [
            'NPM',
            'Nama Mahasiswa',
            'Kode Matakuliah',
            'Kelas',
            'Nilai Akhir',
            'K1_Nama', 'K1_Bobot', 'K1_Skor',
            'K2_Nama', 'K2_Bobot', 'K2_Skor',
            'K3_Nama', 'K3_Bobot', 'K3_Skor',
        ];
    }
}
