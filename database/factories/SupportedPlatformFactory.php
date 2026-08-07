<?php

namespace Database\Factories;

use App\Models\SupportedPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupportedPlatform>
 */
class SupportedPlatformFactory extends Factory
{
    protected $model = SupportedPlatform::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'slug' => $this->faker->unique()->slug(),
            'domain' => $this->faker->unique()->domainName(),
            'color' => $this->faker->safeHexColor(),
            'icon' => $this->faker->randomElement(['video', 'music-note', 'globe-alt', 'photo']),
            'category' => $this->faker->randomElement(['video', 'music', 'social', 'image', 'direct']),
            'analyzer' => $this->faker->randomElement(['direct', 'demo']),
            'description' => $this->faker->sentence(10),
            'formats' => ['mp4', 'mp3'],
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'visit_count' => $this->faker->numberBetween(0, 10000),
            'download_count' => $this->faker->numberBetween(0, 5000),
        ];
    }
}
