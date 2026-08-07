<?php

namespace App\Jobs;

use App\Models\Download;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class PurgeExpiredDownloadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $olderThanDays = 7,
    ) {}

    public function handle(): void
    {
        $cutoff = Carbon::now()->subDays($this->olderThanDays);

        Download::where('created_at', '<=', $cutoff)
            ->whereIn('status', ['completed', 'failed'])
            ->each(function (Download $download): void {
                if ($download->file_path !== null) {
                    $disk = config('media.download_disk');

                    if (app('filesystem')->disk($disk)->exists($download->file_path)) {
                        app('filesystem')->disk($disk)->delete($download->file_path);
                    }
                }

                $download->forceDelete();
            });
    }
}
