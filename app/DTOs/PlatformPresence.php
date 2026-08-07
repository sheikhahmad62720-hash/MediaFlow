<?php

namespace App\DTOs;

/**
 * Result of attempting to resolve a source URL to a known platform.
 */
final readonly class PlatformPresence
{
    public function __construct(
        public bool $recognized,
        public ?string $slug = null,
        public ?string $domain = null,
        public ?string $analyzer = null,
        public ?string $reason = null,
    ) {}
}
