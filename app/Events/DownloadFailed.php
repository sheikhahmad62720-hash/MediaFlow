<?php

namespace App\Events;

use App\Models\Download;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class DownloadFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Download $download,
        public ?Throwable $exception = null,
    ) {}
}
