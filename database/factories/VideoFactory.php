<?php

namespace Database\Factories;

use App\Models\Enums\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(10),
            'year_launched' => rand(1985, 2022),
            'opened' => rand(0, 1),
            'rating' => $this->faker->randomElement([Rating::Free, Rating::Ten, Rating::Twelve, Rating::Fourteen, Rating::Sixteen, Rating::Eighteen]),
            'duration' => rand(1, 30),
            /*'thumb_file' => null,
            'banner_file' => null,
            'trailer_file' => null,
            'video_file' => null,*/
            //'published' => rand(0, 1)
        ];
    }
}
