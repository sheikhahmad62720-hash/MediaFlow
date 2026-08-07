<?php

namespace App\Repositories;

use App\Enums\ActivityType;
use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogRepository
{
    public function create(ActivityType $type, string $description, array $metadata = [], ?int $userId = null, ?string $ip = null, ?string $userAgent = null): ActivityLog
    {
        return ActivityLog::create([
            'id' => (string) \Str::orderedUuid(),
            'user_id' => $userId,
            'type' => $type->value,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return ActivityLog::query()
            ->with('user')
            ->when(! empty($filters['type']), fn (Builder $q) => $q->where('type', $filters['type']))
            ->when(! empty($filters['search']), function (Builder $q) use ($filters): void {
                $q->where('description', 'like', '%'.$filters['search'].'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function stats(int $days = 7): array
    {
        $cutoff = now()->subDays($days);

        $total = ActivityLog::count();

        $perType = ActivityLog::query()
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        $perDay = ActivityLog::query()
            ->where('created_at', '>=', $cutoff)
            ->selectRaw('DATE(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        return [
            'total' => $total,
            'per_type' => $perType,
            'per_day' => $perDay,
        ];
    }
}
