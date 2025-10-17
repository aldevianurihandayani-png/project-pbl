<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    /**
     * The current factory instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    public function __construct($count = null, $states = null, $has = null, $for = null, $afterMaking = null, $afterCreating = null, $connection = null, $faker = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $faker);
        $this->faker = \Faker\Factory::create('id_ID');
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'subject' => $this->faker->text(50), // Menggunakan text() untuk kalimat yang lebih alami
            'message' => $this->faker->text(200), // Menggunakan text() untuk paragraf yang lebih alami
        ];
    }
}
