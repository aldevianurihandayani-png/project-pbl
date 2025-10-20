<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    public function definition(): array
    {
        return [
            'nim' => $this->faker->unique()->numerify('220101###'),
            'nama' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'no_telp' => $this->faker->phoneNumber,
            'angkatan' => $this->faker->numberBetween(2020, 2023),
            'kelas' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'id_dosen' => Dosen::factory(),
        ];
    }
}
