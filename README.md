MediaFlow — Media Downloader Platform

A modern, high-performance media downloading platform built with Laravel 12, Vue 3 (Composition API), and Tailwind CSS 4. Powered by yt-dlp and ffmpeg for seamless video and audio extraction via an asynchronous background queue.

Features

Media Processing

Analyze URLs instantly using real yt-dlp --dump-json metadata extraction
Multi-quality selection (360p, 480p, 720p, 1080p, 4K, and audio-only) via custom Vue quality picker
Real downloads via yt-dlp + ffmpeg merge (bv*[height<=H][ext=mp4]+ba[ext=m4a]) outputting clean mp4/m4a formats
Platform allowlist validation — strictly permits supported platforms (YouTube, Vimeo, etc.) while rejecting arbitrary URLs with 422 errors
Async Queue Architecture

Asynchronous background worker — dispatches jobs to a dedicated `downloads` queue to eliminate HTTP timeouts
No execution limits — CLI worker runs with max_execution_time=0, resolving 30s timeout limitations on large 4K/1440p media
Live status polling — real-time job tracking (queued → processing → completed) over UUID-addressable endpoints
Signed file serving — secure download delivery via downloads.file with strict attachment headers
Admin Panel

Admin content & media management interface
Default Credentials: admin@mediaflow.app / admin123
Requirements

PHP 8.4+ (Laravel 12 / Herd environment)
Composer
Node.js 20+ / npm
yt-dlp & ffmpeg binaries installed under tools/
Installation

## 1. Install PHP dependencies
composer install

## 2. Environment config
copy .env.example .env     # Windows
php artisan key:generate

## 3. Install frontend dependencies

npm install

## 4. Set up the database
php artisan migrate --seed

## 5. Link storage for downloads
php artisan storage:link

## 6. Build assets (or use dev server)
npm run build

Running locally

In three terminals:
## Terminal 1 - Laravel App
php artisan serve --port=8001     # 8000 may be used by another project

## Terminal 2 - Vite HMR (development only)
npm run dev

## Terminal 3 - Queue Worker (REQUIRED for processing downloads)
php artisan queue:work --queue=downloads --timeout=0 --tries=3 --sleep=1

Open http://localhost:8001
Note: dev assets are served by Vite on port 5173 via Laravel's @vite. For a production-like preview run npm run build then just php artisan serve.

## Naming conventions
- Vue components: PascalCase (`DownloadCard.vue`)
- Vue pages: suffixed `View.vue`
- Composables: `use<Feature>.js`
- Controllers: `<Model>Controller.php`

## Formatting & quality
- Backend: `./vendor/bin/pint`
- Frontend: lint via ESLint/Prettier (add config as needed), code is manually ordered per component

## Useful commands
```bash
php artisan queue:work --queue=downloads --timeout=0 --tries=3 --sleep=1   # start media processing worker
php artisan migrate:fresh --seed                                          # reset + reseed demo data & admin
php artisan storage:link                                                  # re-link storage if missing
