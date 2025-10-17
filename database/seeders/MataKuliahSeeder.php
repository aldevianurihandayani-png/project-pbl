<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataKuliah;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataKuliah::upsert([
            ['kode_mk' => 'ITP', 'nama_mk' => 'IT Project', 'sks' => 4, 'semester' => 5, 'id_dosen' => 1],
            ['kode_mk' => 'TPK', 'nama_mk' => 'TPK', 'sks' => 3, 'semester' => 5, 'id_dosen' => 1],
            ['kode_mk' => 'PWL', 'nama_mk' => 'Pemweb Lanjut', 'sks' => 3, 'semester' => 3, 'id_dosen' => 1],
            ['kode_mk' => 'INS', 'nama_mk' => 'Integrasi Sistem', 'sks' => 3, 'semester' => 5, 'id_dosen' => 1],
        ], 
        uniqueBy: ['kode_mk'], 
        update: ['nama_mk', 'sks', 'semester', 'id_dosen']);
    }
}
