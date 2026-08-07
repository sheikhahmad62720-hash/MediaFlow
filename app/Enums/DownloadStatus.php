<?php

namespace App\Enums;

enum DownloadStatus: string
{
    case QUEUED = 'queued';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    /**
     * Statuses that are considered terminal.
     */
    public function isTerminal(): bool
    {
        return in_array($this, [self::COMPLETED, self::FAILED], true);
    }
}
