<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'downloads' => $this->resource['downloads'],
            'top_platforms' => $this->resource['top_platforms'],
            'storage' => $this->resource['storage'],
            'recent' => DownloadResource::collection($this->resource['recent']),
        ];
    }
}
