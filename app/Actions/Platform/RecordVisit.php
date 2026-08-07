<?php

namespace App\Actions\Platform;

use App\Services\Platform\PlatformService;

class RecordVisit
{
    public function __construct(
        protected PlatformService $platforms,
    ) {}

    public function handle(string $url): void
    {
        $this->platforms->recordVisit($url);
    }
}
