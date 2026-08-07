<?php

namespace App\DTOs\Filters;

/**
 * Immutable filter object for download listings.
 */
final readonly class DownloadFilters
{
    public function __construct(
        public ?string $search = null,
        public ?string $status = null,
        public ?string $platform = null,
        public ?string $format = null,
        public ?string $type = null,
        public ?string $sort = 'created_at',
        public ?string $direction = 'desc',
        public int $perPage = 15,
    ) {}

    public static function from(array $data): self
    {
        return new self(
            search: request()->filled('search') ? request()->string('search') : null,
            status: request()->filled('status') ? request()->string('status') : null,
            platform: request()->filled('platform') ? request()->string('platform') : null,
            format: request()->filled('format') ? request()->string('format') : null,
            type: request()->filled('type') ? request()->string('type') : null,
            sort: request()->input('sort', 'created_at'),
            direction: in_array(request()->input('direction', 'desc'), ['asc', 'desc'], true) ? request()->input('direction') : 'desc',
            perPage: min((int) request()->input('per_page', 15), 100),
        );
    }
}
