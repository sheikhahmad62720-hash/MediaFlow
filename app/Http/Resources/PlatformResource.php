<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlatformResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'domain' => $this->domain,
            'color' => $this->color,
            'icon' => $this->icon,
            'category' => $this->category->value ?? null,
            'category_label' => $this->category->label() ?? null,
            'description' => $this->description,
            'formats' => $this->formats,
            'is_active' => $this->is_active,
            'visit_count' => $this->visit_count,
            'download_count' => $this->download_count,
        ];
    }
}
