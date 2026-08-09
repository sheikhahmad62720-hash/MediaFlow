<?php

namespace App\Services\Analytics;

use App\Models\ContactMessage;
use App\Models\Download;
use App\Models\SupportedPlatform;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function dashboardStats(): array
    {
        $downloads = $this->downloadStats();
        $platforms = $this->topPlatforms(8);
        $storage = $this->storageUsage();
        $recent = Download::with('platform')
            ->latest()
            ->limit(8)
            ->get();

        $messages = $this->contactMessages();

        return [
            'downloads' => $downloads,
            'top_platforms' => $platforms,
            'storage' => $storage,
            'recent' => $recent,
            'messages' => $messages,
        ];
    }

    public function contactMessages(int $limit = 5): array
    {
        return [
            'unread_count' => ContactMessage::where('is_read', false)->count(),
            'total' => ContactMessage::count(),
            'recent' => ContactMessage::query()
                ->latest()
                ->limit($limit)
                ->get(),
        ];
    }

    public function downloadStats(): array
    {
        $base = Download::query();

        return [
            'total' => (clone $base)->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
            'queued' => (clone $base)->where('status', 'queued')->count(),
            'processing' => (clone $base)->where('status', 'processing')->count(),
            'failed' => (clone $base)->where('status', 'failed')->count(),
            'today' => (clone $base)->whereDate('created_at', today())->count(),
            'this_week' => (clone $base)->whereBetween('created_at', [now()->subWeek(), now()])->count(),
        ];
    }

    public function topPlatforms(int $limit = 8): array
    {
        return SupportedPlatform::query()
            ->withCount('downloads')
            ->orderBy('downloads_count', 'desc')
            ->orderBy('download_count', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn (SupportedPlatform $platform) => [
                'id' => $platform->id,
                'name' => $platform->name,
                'slug' => $platform->slug,
                'color' => $platform->color,
                'icon' => $platform->icon,
                'downloads' => $platform->download_count + $platform->downloads_count,
                'visits' => $platform->visit_count,
            ])
            ->toArray();
    }

    public function storageUsage(): array
    {
        $disk = config('media.download_disk');

        $total = Download::whereNotNull('file_path')->sum('file_size');

        $byPlatform = Download::query()
            ->whereNotNull('file_path')
            ->where('status', 'completed')
            ->join('supported_platforms', 'downloads.platform_id', '=', 'supported_platforms.id')
            ->select('supported_platforms.name', 'supported_platforms.slug', 'supported_platforms.color', DB::raw('SUM(downloads.file_size) as total'))
            ->groupBy('supported_platforms.id')
            ->get();

        return [
            'disk' => $disk,
            'used_bytes' => (int) $total,
            'used_human' => human_file_size($total),
            'by_platform' => $byPlatform->toArray(),
        ];
    }

    public function downloadsPerDay(int $days = 7): array
    {
        return Download::query()
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();
    }
}
