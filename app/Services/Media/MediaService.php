<?php

namespace App\Services\Media;

use App\DTOs\MediaMetadata;
use App\DTOs\PlatformPresence;
use App\Enums\ActivityType;
use App\Exceptions\UnsupportedPlatformException;
use App\Repositories\ActivityLogRepository;
use App\Repositories\PlatformRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaService
{
    public function __construct(
        protected MediaAnalyzerFactory $analyzers,
        protected PlatformRepository $platforms,
        protected ActivityLogRepository $activities,
        protected Request $request,
    ) {}

    public function analyze(string $url): MediaMetadata
    {
        $presence = $this->resolvePresence($url);

        $analyzer = $this->analyzers->make($presence->analyzer, $url);

        $metadata = $analyzer->analyze($url);

        $this->activities->create(
            type: ActivityType::MEDIA_ANALYZED,
            description: 'Analyzed media: '.Str::limit($metadata->title, 120),
            metadata: ['platform' => $presence->slug, 'media_type' => $metadata->mediaType->value],
            userId: $this->request->user()?->getAuthIdentifier(),
            ip: $this->request->ip(),
            userAgent: $this->request->userAgent(),
        );

        return $metadata;
    }

    public function resolvePresence(string $url): PlatformPresence
    {
        $host = host_of($url);

        if ($host === null) {
            throw new UnsupportedPlatformException('The provided URL is not valid.');
        }

        $platform = $this->platforms->findByDomain($host);

        // Regardless of what is seeded, never treat arbitrary domains as a
        // supported video platform. Only well-known hosts + direct media
        // files are inspected.
        if (! $this->isKnownPlatformHost($host)) {
            if ($this->looksLikeDirectMedia($url)) {
                return new PlatformPresence(
                    recognized: false,
                    analyzer: 'direct',
                    reason: 'Direct media URL.',
                );
            }

            throw new UnsupportedPlatformException('We do not support this platform yet.');
        }

        // Known platforms are inspected with the real yt-dlp engine.
        if ($platform === null) {
            return new PlatformPresence(
                recognized: true,
                analyzer: 'ytdlp',
                reason: 'Known platform, real metadata.',
            );
        }

        return new PlatformPresence(
            recognized: true,
            slug: $platform->slug,
            domain: $platform->domain,
            analyzer: 'ytdlp',
            reason: 'Known platform, real metadata.',
        );
    }

    /**
     * Hosts we explicitly support for media inspection.
     *
     * @return array<int, string>
     */
    protected function knownPlatformHosts(): array
    {
        return [
            'youtube.com', 'youtu.be', 'youtube-nocookie.com',
            'vimeo.com', 'player.vimeo.com',
            'open.spotify.com', 'spotify.com',
            'soundcloud.com',
            'tiktok.com', 'vm.tiktok.com',
            'instagram.com',
            'dailymotion.com',
            'x.com', 'twitter.com',
            'pexels.com',
            'pixabay.com',
        ];
    }

    protected function isKnownPlatformHost(string $host): bool
    {
        foreach ($this->knownPlatformHosts() as $known) {
            if ($host === $known || str_ends_with($host, '.'.$known)) {
                return true;
            }
        }

        return false;
    }

    protected function looksLikeDirectMedia(string $url): bool
    {
        $extension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));

        return in_array(
            $extension,
            ['mp4', 'webm', 'mp3', 'm4a', 'jpg', 'jpeg', 'png', 'webp'],
            true
        );
    }
}
