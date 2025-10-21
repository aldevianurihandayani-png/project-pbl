<?php

namespace Database\Factories;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class DosenFactory extends Factory
{
    protected $model = Dosen::class;

    public function definition(): array
    {
        return [
            'nama_dosen' => $this->faker->name,
            'jabatan' => $this->faker->jobTitle,
            'nip' => $this->faker->unique()->numerify('19##########'),
            'no_telp' => $this->faker->phoneNumber,
        ];
    }
}
