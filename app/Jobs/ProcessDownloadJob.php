<?php

namespace App\Jobs;

use App\Services\Download\DownloadService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ProcessDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout;
    public int $tries;

    public function __construct(
        public string $downloadId,
    ) {
        $this->timeout = (int) config('media.queue.timeout', 0);
        $this->tries = (int) config('media.queue.tries', 1);
    }

    public function handle(DownloadService $downloads): void
    {
        try {
            $downloads->perform($this->downloadId);
        } catch (Throwable $e) {
            report($e);

            throw $e;
        }
    }

    public function failed(Throwable $e): void
    {
        report($e);
    }
}
