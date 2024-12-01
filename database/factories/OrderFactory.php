<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'participant_id' => fake()->numberBetween(1, 10),
            'product_id' => fake()->numberBetween(1, 10),
            'quantity' => fake()->numberBetween(1, 10),
            'total' => fake()->numberBetween(1, 100),
            'state' => fake()->randomElement(['pending', 'completed']),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
            'request_id' => fake()->uuid(),
            'phone_number' => fake()->phoneNumber(),
        ];
    }
}