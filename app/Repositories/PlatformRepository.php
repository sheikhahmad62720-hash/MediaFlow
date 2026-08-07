<?php

namespace App\Repositories;

use App\DTOs\PlatformPresence;
use App\Models\SupportedPlatform;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PlatformRepository
{
    public function find(string $slug): ?SupportedPlatform
    {
        return SupportedPlatform::where('slug', $slug)->first();
    }

    public function findByDomain(string $host): ?SupportedPlatform
    {
        /** @var SupportedPlatform|null $platform */
        $platform = SupportedPlatform::query()
            ->where('is_active', true)
            ->get()
            ->first(fn (SupportedPlatform $p) => $p->matchesDomain($host));

        return $platform;
    }

    /**
     * @return Collection<int, SupportedPlatform>
     */
    public function allActive(): Collection
    {
        return SupportedPlatform::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function presence(string $url): PlatformPresence
    {
        $host = host_of($url);

        if ($host === null) {
            return new PlatformPresence(
                recognized: false,
                reason: 'The URL does not contain a valid host.',
            );
        }

        $platform = $this->findByDomain($host);

        if ($platform === null) {
            return new PlatformPresence(
                recognized: false,
                reason: 'We do not support this platform yet.',
            );
        }

        return new PlatformPresence(
            recognized: true,
            slug: $platform->slug,
            domain: $platform->domain,
            analyzer: $platform->analyzer,
        );
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return SupportedPlatform::query()
            ->when(! empty($filters['active']) && $filters['active'] !== 'all', function (Builder $q) use ($filters): void {
                $q->where('is_active', $filters['active'] === 'true');
            })
            ->when(! empty($filters['search']), function (Builder $query, string $term): void {
                $query->where(function (Builder $q) use ($term): void {
                    $q->where('name', 'like', '%'.$term.'%')
                        ->orWhere('domain', 'like', '%'.$term.'%');
                });
            })
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function incrementVisit(string $slug): void
    {
        SupportedPlatform::where('slug', $slug)->increment('visit_count');
    }

    public function incrementDownload(string $slug): void
    {
        SupportedPlatform::where('slug', $slug)->increment('download_count');
    }
}
