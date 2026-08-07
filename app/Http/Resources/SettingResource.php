<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'group' => $this->group,
            'value' => $this->value,
            'type' => $this->type,
            'label' => $this->label,
            'is_public' => $this->is_public,
            'updated_at' => $this->updated_at,
        ];
    }
}
