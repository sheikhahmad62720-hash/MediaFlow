<?php

namespace App\Listeners;

use App\Enums\ActivityType;
use App\Events\DownloadCompleted;
use App\Events\DownloadFailed;
use App\Events\DownloadStarted;
use App\Repositories\ActivityLogRepository;
use Illuminate\Support\Str;

class RecordDownloadActivity
{
    public function __construct(
        protected ActivityLogRepository $activities,
    ) {}

    public function handle(DownloadStarted $event): void
    {
        $this->activities->create(
            type: ActivityType::DOWNLOAD_STARTED,
            description: 'Started processing download: '.Str::limit($event->download->title, 120),
            metadata: ['download_id' => $event->download->id],
            userId: $event->download->user_id,
        );
    }

    public function handleCompleted(DownloadCompleted $event): void
    {
        $this->activities->create(
            type: ActivityType::DOWNLOAD_COMPLETED,
            description: 'Download completed: '.Str::limit($event->download->title, 120),
            metadata: ['download_id' => $event->download->id, 'file_size' => $event->download->file_size],
            userId: $event->download->user_id,
        );
    }

    public function handleFailed(DownloadFailed $event): void
    {
        $this->activities->create(
            type: ActivityType::DOWNLOAD_FAILED,
            description: 'Download failed: '.Str::limit($event->exception?->getMessage() ?? 'Unknown error', 120),
            metadata: ['download_id' => $event->download->id],
            userId: $event->download->user_id,
        );
    }
}
