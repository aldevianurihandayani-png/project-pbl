<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelompok;
use App\Models\AnggotaKelompok;

use App\Models\Mahasiswa;





class KelompokSeeder extends Seeder
{
    public function run(): void
    {

        Mahasiswa::factory()->count(15)->create();


        Kelompok::factory()
        ->count(5)
        ->create()
        ->each(function ($k) {

            $mahasiswas = Mahasiswa::inRandomOrder()->limit(3)->get();
            foreach ($mahasiswas as $mahasiswa) {
                AnggotaKelompok::factory()->create([
                    'kelompok_id' => $k->id,
                    'mahasiswa_nim' => $mahasiswa->nim,
                ]);
            }
        });
    }
}

            AnggotaKelompok::factory()->count(3)->create(['kelompok_id' => $k->id]);

