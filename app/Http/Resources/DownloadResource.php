<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'source_url' => $this->source_url,
            'thumbnail_url' => $this->thumbnail_url,
            'duration' => $this->duration,
            'duration_human' => $this->duration ? format_duration($this->duration) : null,
            'resolution' => $this->resolution,
            'file_size' => $this->file_size,
            'file_size_human' => $this->human_readable_size,
            'media_type' => $this->media_type?->value,
            'format' => $this->format?->value,
            'quality' => $this->quality,
            'status' => $this->status->value,
            'platform' => new PlatformResource($this->whenLoaded('platform')),
            'file_url' => $this->download_url,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
