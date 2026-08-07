<?php

namespace App\Enums;

enum MediaFormat: string
{
    case MP4 = 'mp4';
    case WEBM = 'webm';
    case MP3 = 'mp3';
    case M4A = 'm4a';
    case JPG = 'jpg';
    case PNG = 'png';
    case WEBP = 'webp';

    public function extension(): string
    {
        return $this->value;
    }

    public function isAudio(): bool
    {
        return in_array($this, [self::MP3, self::M4A], true);
    }

    public function isVideo(): bool
    {
        return in_array($this, [self::MP4, self::WEBM], true);
    }

    public function mimeType(): string
    {
        return match ($this) {
            self::MP4 => 'video/mp4',
            self::WEBM => 'video/webm',
            self::MP3 => 'audio/mpeg',
            self::M4A => 'audio/mp4',
            self::JPG => 'image/jpeg',
            self::PNG => 'image/png',
            self::WEBP => 'image/webp',
        };
    }
}
