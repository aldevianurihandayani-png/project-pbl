<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelompok>
 */
class KelompokFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [


            'judul' => $this->faker->sentence,
            'nama' => $this->faker->word,
            'kelas' => $this->faker->randomElement(['TI-3A', 'TI-3B', 'TI-3C']),
            'anggota' => $this->faker->name . ', ' . $this->faker->name,
            'dosen_pembimbing' => $this->faker->name,
          


            'nama' => 'Kelompok ' . $this->faker->unique()->randomNumber(2),
            'kelas' => $this->faker->randomElement(['TI-3A', 'TI-3B', 'TI-3C']),
            'dosen_pembimbing' => $this->faker->name(),
            'judul' => $this->faker->sentence(4),

        ];
    }
}
