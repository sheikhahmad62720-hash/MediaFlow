<?php

namespace Database\Seeders;

use App\Models\SupportedPlatform;
use Illuminate\Database\Seeder;

class SupportedPlatformsSeeder extends Seeder
{
    /**
     * Seed the canonical list of platforms we officially list as "supported".
     *
     * Note: "demo" analyzers provide simulated metadata for demonstration only.
     * Production integrations must use each platform's official API. We perform
     * no scraping or DRM bypass.
     */
    public function run(): void
    {
        $platforms = [
            [
                'name' => 'YouTube',
                'slug' => 'youtube',
                'domain' => 'youtube.com',
                'color' => '#FF0000',
                'icon' => 'youtube',
                'category' => 'video',
                'analyzer' => 'demo',
                'description' => 'Google video hosting service.',
                'formats' => ['mp4', 'webm', 'mp3'],
            ],
            [
                'name' => 'Vimeo',
                'slug' => 'vimeo',
                'domain' => 'vimeo.com',
                'color' => '#1DA1F2',
                'icon' => 'vimeo',
                'category' => 'video',
                'analyzer' => 'demo',
                'description' => 'Professional video hosting platform.',
                'formats' => ['mp4', 'webm', 'mp3'],
            ],
            [
                'name' => 'SoundCloud',
                'slug' => 'soundcloud',
                'domain' => 'soundcloud.com',
                'color' => '#FF5500',
                'icon' => 'soundcloud',
                'category' => 'music',
                'analyzer' => 'demo',
                'description' => 'Audio streaming and music sharing platform.',
                'formats' => ['mp3', 'm4a'],
            ],
            [
                'name' => 'Spotify',
                'slug' => 'spotify',
                'domain' => 'open.spotify.com',
                'color' => '#1DB954',
                'icon' => 'spotify',
                'category' => 'music',
                'analyzer' => 'demo',
                'description' => 'Music streaming service. Downloads require a subscription and are delivered via the official client.',
                'formats' => ['mp3'],
            ],
            [
                'name' => 'TikTok',
                'slug' => 'tiktok',
                'domain' => 'tiktok.com',
                'color' => '#010101',
                'icon' => 'tiktok',
                'category' => 'social',
                'analyzer' => 'demo',
                'description' => 'Short-form video platform. Respect the creator and platform ToS.',
                'formats' => ['mp4'],
            ],
            [
                'name' => 'Instagram',
                'slug' => 'instagram',
                'domain' => 'instagram.com',
                'color' => '#C13583',
                'icon' => 'instagram',
                'category' => 'social',
                'analyzer' => 'demo',
                'description' => 'Photo and video sharing platform. Downloads require explicit permission.',
                'formats' => ['mp4', 'jpg'],
            ],
            [
                'name' => 'Dailymotion',
                'slug' => 'dailymotion',
                'domain' => 'dailymotion.com',
                'color' => '#0066CC',
                'icon' => 'dailymotion',
                'category' => 'video',
                'analyzer' => 'demo',
                'description' => 'Video sharing platform with public and paid content.',
                'formats' => ['mp4', 'webm', 'mp3'],
            ],
            [
                'name' => 'Twitter',
                'slug' => 'twitter',
                'domain' => 'twitter.com',
                'color' => '#000000',
                'icon' => 'twitter',
                'category' => 'social',
                'analyzer' => 'demo',
                'description' => 'Microblogging and social media platform.',
                'formats' => ['mp4'],
            ],
            [
                'name' => 'Pexels',
                'slug' => 'pexels',
                'domain' => 'pexels.com',
                'color' => '#00B894',
                'icon' => 'pexels',
                'category' => 'image',
                'analyzer' => 'demo',
                'description' => 'Free stock photo and video library.',
                'formats' => ['jpg', 'png', 'mp4'],
            ],
            [
                'name' => 'Pixabay',
                'slug' => 'pixabay',
                'domain' => 'pixabay.com',
                'color' => '#32C5F0',
                'icon' => 'pixabay',
                'category' => 'image',
                'analyzer' => 'demo',
                'description' => 'Free stock media library.',
                'formats' => ['jpg', 'png', 'mp4'],
            ],
            [
                'name' => 'Direct',
                'slug' => 'direct',
                'domain' => 'example.com',
                'color' => '#6b7280',
                'icon' => 'link',
                'category' => 'direct',
                'analyzer' => 'direct',
                'description' => 'Any directly accessible, publicly downloadable media file.',
                'formats' => ['mp4', 'webm', 'mp3', 'm4a', 'jpg', 'png', 'webp'],
                'is_active' => false,
            ],
        ];

        foreach ($platforms as $index => $platform) {
            SupportedPlatform::updateOrCreate(
                ['slug' => $platform['slug']],
                array_merge($platform, ['sort_order' => $index])
            );
        }
    }
}
