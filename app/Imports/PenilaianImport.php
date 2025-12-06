<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenilaianImport implements ToModel, ToCollection, WithHeadingRow
{
    protected int $matakuliahId;
    protected ?int $kelasId;

    public function __construct(int $matakuliahId, ?int $kelasId = null)
    {
        $this->matakuliahId = $matakuliahId;
        $this->kelasId = $kelasId;
    }

    /**
     * Method utama (baris per baris) — langsung menyimpan ke database
     */
    public function model(array $row)
    {
        // cari mahasiswa berdasarkan npm (atau sesuaikan kolom identifikasi)
        $mhs = Mahasiswa::where('npm', $row['npm'] ?? '')->first();
        if (!$mhs) return null;

        // membangun array komponen dari kolom template (k1, k2, k3)
        $komponen = [];
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($row["k{$i}_nama"])) {
                $komponen[] = [
                    'nama'  => $row["k{$i}_nama"],
                    'bobot' => (float)($row["k{$i}_bobot"] ?? 0),
                    'skor'  => (float)($row["k{$i}_skor"] ?? 0),
                ];
            }
        }

        // hitung nilai akhir = Σ(bobot × skor / 100)
        $nilaiAkhir = collect($komponen)->sum(fn($k) => ($k['bobot'] ?? 0) * ($k['skor'] ?? 0) / 100);

        // update atau buat baru jika belum ada
        return Penilaian::updateOrCreate(
            [
                'mahasiswa_id'  => $mhs->id,
                'matakuliah_id' => $this->matakuliahId,
                'kelas_id'      => $this->kelasId,
                'dosen_id'      => Auth::id(),
            ],
            [
                'komponen'   => $komponen,
                'nilai_akhir'=> $nilaiAkhir,
            ]
        );
    }

    /**
     * Method tambahan (opsional) — membaca semua baris sekaligus
     * Bisa dipakai untuk logging, validasi, atau analisis sebelum insert.
     */
    public function collection(Collection $collection)
    {
        // Contoh: menghitung jumlah baris import
        // \Log::info('Jumlah baris diimport: ' . $collection->count());
    }
}
