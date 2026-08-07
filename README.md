<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## About MediaFlow

MediaFlow is a Laravel application that fetches and converts media from known platforms (YouTube, Vimeo, SoundCloud, Spotify, TikTok, Instagram, Dailymotion, Pexels, Pixabay, X/Twitter). It analyzes source URLs in real time using `yt-dlp`, lists available qualities (360p–4K + audio), and downloads the selected format using `yt-dlp` + `ffmpeg`.

Only supported platforms are accepted — arbitrary URLs are rejected with HTTP 422.

## Requirements

- PHP 8.4+ with `mbstring`, `pdo_sqlite`, `curl`, `fileinfo`
- Node.js 20+ / npm
- Composer
- `yt-dlp` and `ffmpeg` binaries (installed below)

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### yt-dlp + ffmpeg (required for real downloads)

MediaFlow does **not** commit these binaries (they are large). Install them under `tools/`:

```bash
# Option A — winget (Windows, recommended)
winget install -e --id yt-dlp.yt-dlp -h
winget install -e --id Gyan.FFmpeg -h
# Place yt-dlp.exe and ffmpeg.exe under tools/ (see config/media.php for paths)

# Option B — manual download
# yt-dlp: https://github.com/yt-dlp/yt-dlp#installation
# ffmpeg: https://www.gyan.dev/ffmpeg/builds/
```

`config/media.php` auto-resolves `yt_dlp.binary`, `yt_dlp.ffmpeg`, and `yt_dlp.socket_timeout` from environment variables; defaults point at `tools/yt-dlp.exe` and `tools/ffmpeg/ffmpeg.exe`.

### Database

For local development a SQLite database is used by default:

```bash
php artisan migrate:fresh --seed   # creates users, platforms, settings and an admin account
```

Seeded admin: `admin@mediaflow.app` / `admin123`.

### Queue worker (required for downloads)

Downloads run as queued jobs in the background so long videos (4K, multi-minute) don't hit PHP's `max_execution_time`. Start the worker:

```bash
php artisan queue:work --queue=downloads --timeout=0 --tries=3 --sleep=1
```

`MEDIA_QUEUE_DOWNLOADS=true` (the default) enables async downloads; the frontend polls `/api/downloads/{id}` until the status is `completed`, then uses the signed file URL to serve the file.

### Running

```bash
php artisan serve        # API on http://localhost:8000
npm run dev              # frontend (Vite, http://localhost:5173)
```

## Testing

```bash
php artisan test
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe that development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:


