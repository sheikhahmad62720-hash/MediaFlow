<?php

namespace App\Services\Media;

use App\Contracts\MediaAnalyzer;
use App\DTOs\DownloadOption;
use App\DTOs\MediaMetadata;
use App\Enums\MediaFormat;
use App\Enums\MediaType;
use Illuminate\Support\Str;

class DemoAnalyzer implements MediaAnalyzer
{
    public function supports(string $url): bool
    {
        return Str::startsWith($url, ['http://', 'https://']);
    }

    public function analyze(string $url): MediaMetadata
    {
        $host = host_of($url) ?? 'unknown';
        $isMusic = Str::contains($host, ['spotify', 'soundcloud']);

        $formats = $isMusic
            ? [
                new DownloadOption(MediaFormat::MP3, 'high', 8388608, null, MediaFormat::MP3->mimeType()),
                new DownloadOption(MediaFormat::M4A, 'medium', 5242880, null, MediaFormat::M4A->mimeType()),
            ]
            : [
                new DownloadOption(MediaFormat::MP4, 'high', 73400320, '1080p', MediaFormat::MP4->mimeType()),
                new DownloadOption(MediaFormat::WEBM, 'medium', 32908928, '720p', MediaFormat::WEBM->mimeType()),
                new DownloadOption(MediaFormat::MP3, 'audio', 8388608, null, MediaFormat::MP3->mimeType()),
            ];

        return new MediaMetadata(
            title: $this->deriveTitle($url, $host, $isMusic),
            thumbnailUrl: 'https://images.unsplash.com/photo-1518770660439-4633fdb822fe?auto=format&fit=crop&w=1080',
            duration: 247,
            resolution: $isMusic ? null : '1080p',
            mediaType: $isMusic ? MediaType::AUDIO : MediaType::VIDEO,
            platform: $host,
            formats: $formats,
            raw: [
                'note' => 'Simulated metadata generated in demo mode. Replace with an official provider integration.',
                'source' => $url,
            ],
        );
    }

    protected function deriveTitle(string $url, string $host, bool $isMusic): string
    {
        $tunes = [
            'youtube.com' => ['Lofi Hip Hop - Beats to Relax/Study To', 'How to Build a SaaS Product', 'Building a Modern API in Laravel 13'],
            'vimeo.com' => ['Into the Wild - Official Trailer', 'Behind the Brand: Innovation'],
            'soundcloud.com' => ['Midnight Drive', 'Morning Coffee', 'Neon Dreams'],
            'open.spotify.com' => ['Blinding Lights - The Weeknd', 'Stay - The Kid LAROI', 'Heat Waves - Glass Animals'],
            'tiktok.com' => ['Viral Dance Tutorial', 'My Day in 60 Seconds', 'Cooking Hack'],
            'instagram.com' => ['My Latest Post', 'Reel Highlights'],
            'dailymotion.com' => ['Top 10 Tech Tips', 'Explainer: How It Works'],
            'twitter.com' => ['Keynote Highlights', 'Conference Talk'],
            'pexels.com' => ['Mountain Landscape - Free Stock', 'City Skyline at Sunset'],
            'pixabay.com' => ['Forest Path - Royalty Free', 'Ocean Waves on Rocks'],
        ];

        $candidates = $tunes[$host] ?? ($isMusic ? $tunes['soundcloud.com'] : $tunes['youtube.com']);

        return $this->faker($url, $candidates);
    }

    protected function faker(string $url, array $candidates): string
    {
        $hash = abs(crc32($url)) % count($candidates);

        return $candidates[$hash];
    }
}
