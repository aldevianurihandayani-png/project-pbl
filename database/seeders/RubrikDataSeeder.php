<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rubrik; // Import the Rubrik model

class RubrikDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rubrikData = [
            // INS
            [
                'kode_mk' => 'INS',
                'nama_rubrik' => 'Kualitas Kode',
                'bobot' => 30,
                'urutan' => 1,
                'deskripsi' => 'Penulisan kode yang bersih, terstruktur, dan mudah dibaca.'
            ],
            [
                'kode_mk' => 'INS',
                'nama_rubrik' => 'Fungsionalitas Aplikasi',
                'bobot' => 40,
                'urutan' => 2,
                'deskripsi' => 'Semua fitur aplikasi berjalan sesuai spesifikasi.'
            ],
            [
                'kode_mk' => 'INS',
                'nama_rubrik' => 'Dokumentasi',
                'bobot' => 30,
                'urutan' => 3,
                'deskripsi' => 'Kelengkapan dan kejelasan dokumentasi proyek.'
            ],

            // ITP
            [
                'kode_mk' => 'ITP',
                'nama_rubrik' => 'Desain Antarmuka',
                'bobot' => 35,
                'urutan' => 1,
                'deskripsi' => 'Estetika dan user experience antarmuka aplikasi.'
            ],
            [
                'kode_mk' => 'ITP',
                'nama_rubrik' => 'Performa Aplikasi',
                'bobot' => 35,
                'urutan' => 2,
                'deskripsi' => 'Kecepatan dan efisiensi aplikasi dalam menjalankan tugas.'
            ],
            [
                'kode_mk' => 'ITP',
                'nama_rubrik' => 'Keamanan Data',
                'bobot' => 30,
                'urutan' => 3,
                'deskripsi' => 'Implementasi fitur keamanan untuk melindungi data pengguna.'
            ],

            // PWL
            [
                'kode_mk' => 'PWL',
                'nama_rubrik' => 'Responsivitas Desain',
                'bobot' => 30,
                'urutan' => 1,
                'deskripsi' => 'Kemampuan aplikasi beradaptasi dengan berbagai ukuran layar.'
            ],
            [
                'kode_mk' => 'PWL',
                'nama_rubrik' => 'Integrasi API',
                'bobot' => 40,
                'urutan' => 2,
                'deskripsi' => 'Keberhasilan integrasi dengan layanan eksternal melalui API.'
            ],
            [
                'kode_mk' => 'PWL',
                'nama_rubrik' => 'Pengelolaan State',
                'bobot' => 30,
                'urutan' => 3,
                'deskripsi' => 'Efektivitas pengelolaan state dalam aplikasi web.'
            ],

            // TPK
            [
                'kode_mk' => 'TPK',
                'nama_rubrik' => 'Algoritma Efisien',
                'bobot' => 40,
                'urutan' => 1,
                'deskripsi' => 'Penggunaan algoritma yang efisien untuk menyelesaikan masalah.'
            ],
            [
                'kode_mk' => 'TPK',
                'nama_rubrik' => 'Struktur Data',
                'bobot' => 30,
                'urutan' => 2,
                'deskripsi' => 'Pemilihan dan implementasi struktur data yang tepat.'
            ],
            [
                'kode_mk' => 'TPK',
                'nama_rubrik' => 'Kompleksitas Waktu & Ruang',
                'bobot' => 30,
                'urutan' => 3,
                'deskripsi' => 'Analisis dan optimasi kompleksitas waktu dan ruang algoritma.'
            ],
        ];

        foreach ($rubrikData as $data) {
            Rubrik::create($data);
        }
    }
}