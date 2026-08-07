<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Collection;

class SettingRepository
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Setting::fetch($key, $default);
    }

    public function set(string $key, mixed $value, bool $public = false, string $group = 'general'): Setting
    {
        return Setting::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $value,
                'type' => is_array($value) || is_bool($value) ? 'json' : 'string',
                'is_public' => $public,
            ]
        );
    }

    /**
     * @return Collection<int, Setting>
     */
    public function all(): Collection
    {
        return Setting::orderBy('group')->orderBy('key')->get();
    }

    /**
     * @return array<string, mixed>
     */
    public function public(): array
    {
        return cache()->remember('settings:public', 3600, fn () => Setting::where('is_public', true)->pluck('value', 'key')->toArray()
        );
    }

    public function upsertMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value['value'] ?? $value['label'] ?? null, $value['public'] ?? false, $value['group'] ?? 'general');
        }
    }
}
