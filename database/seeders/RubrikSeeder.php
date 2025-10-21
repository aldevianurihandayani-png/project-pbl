<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rubrik;
use App\Models\MataKuliah;

class RubrikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all mata kuliah
        $matakuliahs = MataKuliah::all();

        foreach ($matakuliahs as $mk) {
            Rubrik::factory()->count(3)->create([
                'kode_mk' => $mk->kode_mk,
            ]);
        }
    }
}
