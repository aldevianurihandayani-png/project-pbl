<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

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
        $userIds = User::pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'title' => $this->faker->sentence(4),
            'message' => $this->faker->text(150),
            'is_read' => $this->faker->boolean(70), // 70% kemungkinan sudah dibaca
        ];
    }
}