<?php

namespace App\DTOs;

use App\Enums\MediaType;

/**
 * Value object representing metadata discovered for a media source.
 */
final readonly class MediaMetadata
{
    /**
     * @param  int|null  $duration  Duration in seconds.
     * @param  string|null  $resolution  e.g. "1080p", "4K".
     * @param  array<int, DownloadOption>  $formats
     */
    public function __construct(
        public string $title,
        public ?string $thumbnailUrl = null,
        public ?int $duration = null,
        public ?string $resolution = null,
        public MediaType $mediaType = MediaType::VIDEO,
        public ?string $platform = null,
        public array $formats = [],
        public array $raw = [],
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'thumbnail_url' => $this->thumbnailUrl,
            'duration' => $this->duration,
            'resolution' => $this->resolution,
            'media_type' => $this->mediaType->value,
            'platform' => $this->platform,
            'formats' => array_map(
                static fn (DownloadOption $option) => $option->toArray(),
                $this->formats
            ),
            'raw' => $this->raw,
        ];
    }
}
