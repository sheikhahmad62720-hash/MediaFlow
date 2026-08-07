<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'value' => 'json',
            'is_public' => 'boolean',
        ];
    }

    public static function fetch(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, mixed $value, string $type = 'json', bool $public = false, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'value' => $value,
                'type' => $type,
                'is_public' => $public,
            ]
        );
    }

    public static function public(): array
    {
        return cache()->remember(
            'settings:public',
            3600,
            fn () => static::where('is_public', true)
                ->pluck('value', 'key')
                ->toArray()
        );
    }
}
