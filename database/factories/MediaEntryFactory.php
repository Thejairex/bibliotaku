<?php

namespace Database\Factories;

use App\Models\MediaEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaEntry>
 */
class MediaEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence(),
            'type' => \App\Enums\MediaType::Anime,
            'status' => \App\Enums\MediaStatus::PlanToWatch,
        ];
    }
}
