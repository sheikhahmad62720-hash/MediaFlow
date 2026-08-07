<?php

namespace App\DTOs;

use App\Enums\MediaFormat;

/**
 * @implements \ArrayAccess<string, mixed>
 */
final readonly class DownloadOption
{
    public function __construct(
        public MediaFormat $format,
        public ?string $quality = 'medium',
        public ?int $fileSize = null,
        public ?string $resolution = null,
        public ?string $mimeType = null,
        public ?string $downloadUrl = null,
    ) {}

    public function toArray(): array
    {
        return [
            'format' => $this->format->value,
            'quality' => $this->quality,
            'file_size' => $this->fileSize,
            'resolution' => $this->resolution,
            'mime_type' => $this->mimeType ?? $this->format->mimeType(),
            'download_url' => $this->downloadUrl,
        ];
    }
}
