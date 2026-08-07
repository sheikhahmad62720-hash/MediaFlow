<?php

namespace App\Enums;

enum ActivityType: string
{
    case MEDIA_ANALYZED = 'media.analyzed';
    case DOWNLOAD_CREATED = 'download.created';
    case DOWNLOAD_STARTED = 'download.started';
    case DOWNLOAD_COMPLETED = 'download.completed';
    case DOWNLOAD_FAILED = 'download.failed';
    case PLATFORM_VISITED = 'platform.visited';

    public function label(): string
    {
        return match ($this) {
            self::MEDIA_ANALYZED => 'Media analyzed',
            self::DOWNLOAD_CREATED => 'Download created',
            self::DOWNLOAD_STARTED => 'Download started',
            self::DOWNLOAD_COMPLETED => 'Download completed',
            self::DOWNLOAD_FAILED => 'Download failed',
            self::PLATFORM_VISITED => 'Platform visited',
        };
    }
}
