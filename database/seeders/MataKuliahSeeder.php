<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        // 1. Buat atau update data dosen
        $oky = User::updateOrCreate(
            ['email' => 'oky.rahmanto@politala.ac.id'],
            [
                'name' => 'Oky Rahmanto, S.Kom., M.T',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        $nina = User::updateOrCreate(
            ['email' => 'ninamia@politala.ac.id'],
            [
                'name' => 'Nina Mia Aristi, M.Kom',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        $agustia = User::updateOrCreate(
            ['email' => 'agustia.noor@politala.ac.id'],
            [
                'name' => 'Ir. Agustia Noor, M.Kom',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        $afian = User::updateOrCreate(
            ['email' => 'afian.syafaadi@politala.ac.id'],
            [
                'name' => 'Afian Syafaadi Rizki, M.Kom',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        // Hapus data mata kuliah lama untuk menghindari duplikasi
        DB::table('mata_kuliah')->delete();

        // 2. Buat data mata kuliah dengan dosen yang spesifik
        $matakuliah = [
            [
                'kode_mk' => 'MK-001',
                'nama_mk' => 'Integrasi Sistem',
                'sks' => 3,
                'semester' => 5,
                'id_dosen' => $oky->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'MK-002',
                'nama_mk' => 'Teknik Pengambilan Keputusan',
                'sks' => 3,
                'semester' => 5,
                'id_dosen' => $nina->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'MK-003',
                'nama_mk' => 'Pemrograman Web Lanjut',
                'sks' => 4,
                'semester' => 3,
                'id_dosen' => $agustia->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'MK-004',
                'nama_mk' => 'IT Project',
                'sks' => 6,
                'semester' => 6,
                'id_dosen' => $afian->id, // Hanya satu dosen yang bisa ditugaskan
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('mata_kuliah')->insert($matakuliah);
    
