<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Batch;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StageRecord>
 */
class StageRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'batch_id' => Batch::factory(),
            'stage' => $this->faker->randomElement(['Sheared','Cleaned','Packaged']),
            'notes' => $this->faker->sentence(),
        ];
    }
}
