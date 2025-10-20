<?php

namespace Database\Factories;


use App\Models\AnggotaKelompok;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnggotaKelompok>
 */

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  class AnggotaKelompokFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nim'  => 'TI' . fake()->numberBetween(220000,229999),
            'nama' => fake()->name(),
        ];
    }
}


