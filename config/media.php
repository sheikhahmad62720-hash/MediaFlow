<?php

use App\Enums\MediaType;

return [
    /*
    |--------------------------------------------------------------------------
    | Demo Mode
    |--------------------------------------------------------------------------
    | When enabled, the bundled demo analyzer can be used to demonstrate the
    | full analyze/download flow for popular platforms without touching
    | third-party content. Disable it in production.
    */
    'demo_mode' => (bool) env('MEDIA_DEMO_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | yt-dlp Integration
    |--------------------------------------------------------------------------
    | yt-dlp is the free, open-source engine used to extract real metadata and
    | download media from supported platforms. The binary is invoked as a
    | sub-process; it must be installed and reachable from the web server user.
    */
    'yt_dlp' => [
        'binary' => env('MEDIA_YTDLP_BINARY', base_path('tools/yt-dlp.exe')),
        'timeout' => (int) env('MEDIA_YTDLP_TIMEOUT', 300),
        'socket_timeout' => (int) env('MEDIA_YTDLP_SOCKET_TIMEOUT', 20),
        'ffmpeg' => env('MEDIA_YTDLP_FFMPEG', base_path('tools/ffmpeg/ffmpeg.exe')),
    ],

    'max_url_length' => (int) env('MEDIA_MAX_URL_LENGTH', 2048),

    /*
    | Filesystem disk where downloaded media is stored. Kept private so
    | files are only served through signed URLs.
    */
    'download_disk' => env('MEDIA_DOWNLOAD_DISK', 'local'),

    'rate_limit' => [
        'analyze' => (int) env('MEDIA_ANALYZE_RATE_LIMIT', 30),
        'download' => (int) env('MEDIA_DOWNLOAD_RATE_LIMIT', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Downloads
    |--------------------------------------------------------------------------
    | When true (recommended), downloads are dispatched to the "downloads"
    | queue and require a queue worker:
    |   php artisan queue:work --queue=downloads --timeout=0 --tries=3
    | The frontend polls the (UUID-addressable) download endpoint until the
    | file is ready, so long jobs (4K/8K) never block an HTTP request.
    |
    | When false, downloads run synchronously inside the request — handy for
    | quick local testing but limited by PHP's max_execution_time.
    */
    'queue_downloads' => (bool) env('MEDIA_QUEUE_DOWNLOADS', true),

    'queue' => [
        'timeout' => (int) env('MEDIA_QUEUE_TIMEOUT', 0),
        'tries' => (int) env('MEDIA_QUEUE_TRIES', 3),
        'memory' => (int) env('MEDIA_QUEUE_MEMORY', 512),
    ],

    /*
    | Formats available per media type.
    */
    'formats' => [
        MediaType::VIDEO->value => ['mp4', 'webm'],
        MediaType::AUDIO->value => ['mp3', 'm4a'],
        MediaType::IMAGE->value => ['jpg', 'png', 'webp'],
    ],
];
