<?php

namespace Database\Factories;

use App\Enums\DownloadStatus;
use App\Enums\MediaFormat;
use App\Enums\MediaType;
use App\Models\Download;
use App\Models\SupportedPlatform;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Download>
 */
class DownloadFactory extends Factory
{
    protected $model = Download::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => User::factory(),
            'platform_id' => SupportedPlatform::factory(),
            'source_url' => $this->faker->url(),
            'title' => $this->faker->sentence(4),
            'thumbnail_url' => $this->faker->imageUrl(320, 180),
            'duration' => $this->faker->numberBetween(60, 600),
            'resolution' => $this->faker->randomElement(['720p', '1080p', '4K']),
            'file_size' => $this->faker->numberBetween(1048576, 2147483648),
            'media_type' => $this->faker->randomElement(MediaType::cases()),
            'format' => $this->faker->randomElement(MediaFormat::cases()),
            'quality' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(DownloadStatus::cases()),
            'metadata' => ['note' => 'seeded'],
            'processed_at' => now(),
        ];
    }
}
