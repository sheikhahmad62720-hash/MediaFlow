<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function __construct(
        protected SettingRepository $settings,
    ) {}

    public function public(): array
    {
        return Cache::remember('settings:public', 3600, fn () => $this->settings->public());
    }

    public function all(): array
    {
        return $this->settings->all()->mapWithKeys(fn ($s) => [$s->key => $s->value])->toArray();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings->get($key, $default);
    }

    public function set(string $key, mixed $value, bool $public = false, string $group = 'general'): void
    {
        $this->settings->set($key, $value, $public, $group);
        Cache::forget('settings:public');
    }

    public function flush(): void
    {
        Cache::forget('settings:public');
    }
}
