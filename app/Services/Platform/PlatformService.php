<?php

namespace App\Services\Platform;

use App\DTOs\PlatformPresence;
use App\Models\SupportedPlatform;
use App\Repositories\PlatformRepository;

class PlatformService
{
    public function __construct(
        protected PlatformRepository $platforms,
    ) {}

    public function presence(string $url): PlatformPresence
    {
        return $this->platforms->presence($url);
    }

    public function findByDomain(string $host): ?SupportedPlatform
    {
        return $this->platforms->findByDomain($host);
    }

    public function recordVisit(string $url): PlatformPresence
    {
        $presence = $this->presence($url);

        if ($presence->recognized && $presence->slug !== null) {
            $this->platforms->incrementVisit($presence->slug);
        }

        return $presence;
    }
}
