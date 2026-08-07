<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource->title,
            'thumbnail_url' => $this->resource->thumbnailUrl,
            'duration' => $this->resource->duration,
            'resolution' => $this->resource->resolution,
            'media_type' => $this->resource->mediaType->value,
            'platform' => $this->resource->platform,
            'formats' => DownloadOptionResource::collection($this->resource->formats),
            'raw' => $this->resource->raw,
        ];
    }
}
