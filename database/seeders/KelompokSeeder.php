<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelompok;
use App\Models\AnggotaKelompok;



class KelompokSeeder extends Seeder
{
    public function run(): void
    {
        Kelompok::factory()
        ->count(5)
        ->create()
        ->each(function ($k) {
            AnggotaKelompok::factory()->count(3)->create(['kelompok_id' => $k->id]);
        });
            }
        }

