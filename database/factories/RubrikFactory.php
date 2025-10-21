<?php

namespace Database\Factories;

use App\Models\Rubrik;
use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rubrik>
 */
class RubrikFactory extends Factory
{
    protected $model = Rubrik::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_mk' => MataKuliah::inRandomOrder()->first()->kode_mk,
            'nama_rubrik' => $this->faker->unique()->word(),
            'bobot' => $this->faker->numberBetween(10, 40),
            'urutan' => $this->faker->unique()->numberBetween(1, 10),
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}
