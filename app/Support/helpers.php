<?php

use Illuminate\Support\Str;

if (! function_exists('is_valid_url')) {
    function is_valid_url(string $value, int $maxLength = 2048): bool
    {
        if (strlen($value) > $maxLength) {
            return false;
        }

        if (! Str::startsWith($value, ['http://', 'https://'])) {
            return false;
        }

        $host = parse_url($value, PHP_URL_HOST);

        return $host !== null && $host !== '';
    }
}

if (! function_exists('host_of')) {
    function host_of(string $url): ?string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return $host !== null ? strtolower($host) : null;
    }
}

if (! function_exists('human_file_size')) {
    function human_file_size(int|float $bytes): string
    {
        return match (true) {
            $bytes < 1024 => $bytes.' B',
            $bytes < 1048576 => round($bytes / 1024, 2).' KB',
            $bytes < 1073741824 => round($bytes / 1048576, 2).' MB',
            default => round($bytes / 1073741824, 2).' GB',
        };
    }
}

if (! function_exists('format_duration')) {
    function format_duration(int $seconds): string
    {
        if ($seconds < 3600) {
            return sprintf('%d:%02d', intdiv($seconds, 60), $seconds % 60);
        }

        return sprintf(
            '%d:%02d:%02d',
            intdiv($seconds, 3600),
            intdiv($seconds % 3600, 60),
            $seconds % 60
        );
    }
}

if (! function_exists('unique_filename')) {
    function unique_filename(string $prefix, string $extension): string
    {
        return sprintf('%s_%s.%s', $prefix, Str::random(12), $extension);
    }
}
