<?php

namespace App\Actions\Activity;

use App\Enums\ActivityType;
use App\Repositories\ActivityLogRepository;
use Illuminate\Http\Request;

class LogActivity
{
    public function __construct(
        protected ActivityLogRepository $activities,
        protected Request $request,
    ) {}

    public function handle(ActivityType $type, string $description, array $metadata = []): void
    {
        $this->activities->create(
            type: $type,
            description: $description,
            metadata: $metadata,
            userId: $this->request->user()?->getAuthIdentifier(),
            ip: $this->request->ip(),
            userAgent: $this->request->userAgent(),
        );
    }
}
