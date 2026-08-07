<?php

namespace App\Services\Media;

use App\Contracts\MediaAnalyzer;
use App\DTOs\DownloadOption;
use App\DTOs\MediaMetadata;
use App\Enums\MediaFormat;
use App\Enums\MediaType;
use App\Exceptions\MediaAnalysisException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DirectUrlAnalyzer implements MediaAnalyzer
{
    public function supports(string $url): bool
    {
        return Str::startsWith($url, ['http://', 'https://']);
    }

    public function analyze(string $url): MediaMetadata
    {
        $filename = basename(parse_url($url, PHP_URL_PATH) ?: 'media');
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $format = MediaFormat::tryFrom($extension);

        if ($format === null) {
            // We cannot verify the content type without an unsafe probe; probe it instead.
            $probed = $this->probeContentType($url);

            $format = match ($probed) {
                'video/mp4' => MediaFormat::MP4,
                'video/webm' => MediaFormat::WEBM,
                'audio/mpeg' => MediaFormat::MP3,
                'audio/mp4' => MediaFormat::M4A,
                'image/jpeg' => MediaFormat::JPG,
                'image/png' => MediaFormat::PNG,
                'image/webp' => MediaFormat::WEBP,
                default => throw new MediaAnalysisException('Unable to determine the media type of the provided URL.'),
            };
        }

        $size = $this->probeSize($url);
        $mimeType = $format->mimeType();
        $mediaType = $this->deriveType($format);

        return new MediaMetadata(
            title: $this->deriveTitle($filename),
            thumbnailUrl: null,
            duration: null,
            resolution: $this->deriveResolution($format),
            mediaType: $mediaType,
            platform: 'direct',
            formats: [
                new DownloadOption(
                    format: $format,
                    fileSize: $size,
                    mimeType: $mimeType,
                ),
            ],
            raw: [
                'source' => $url,
                'mime_type' => $mimeType,
                'content_length' => $size,
            ],
        );
    }

    protected function probeContentType(string $url): string
    {
        try {
            $response = Http::head($url);

            if (! $response->successful()) {
                return 'application/octet-stream';
            }

            return $response->header('Content-Type') ?? 'application/octet-stream';
        } catch (Throwable) {
            return 'application/octet-stream';
        }
    }

    protected function probeSize(string $url): ?int
    {
        try {
            $response = Http::head($url);

            if (! $response->successful()) {
                return null;
            }

            $length = $response->header('Content-Length');

            return $length !== null ? (int) $length : null;
        } catch (Throwable) {
            return null;
        }
    }

    protected function deriveType(MediaFormat $format): MediaType
    {
        if ($format->isVideo()) {
            return MediaType::VIDEO;
        }

        if ($format->isAudio()) {
            return MediaType::AUDIO;
        }

        return MediaType::IMAGE;
    }

    protected function deriveResolution(MediaFormat $format): ?string
    {
        return $format->isVideo() ? 'unknown' : null;
    }

    protected function deriveTitle(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);

        if ($name === '') {
            $name = 'media-'.Str::random(6);
        }

        return str_replace(['_', '-'], ' ', $name);
    }
}
