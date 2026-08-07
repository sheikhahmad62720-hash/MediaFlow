<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardStatsResource;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected AnalyticsService $analytics,
    ) {}

    public function stats(): DashboardStatsResource
    {
        return new DashboardStatsResource($this->analytics->dashboardStats());
    }

    public function storage(): JsonResponse
    {
        return response()->json($this->analytics->storageUsage());
    }
}
