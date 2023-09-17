<?php

namespace Database\Factories;

use App\Models\EventDay;
use App\Models\Sponsor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stand>
 */
class StandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_day_id' => EventDay::factory(),
            'sponsor_id' => Sponsor::factory(),
        ];
    }
}
