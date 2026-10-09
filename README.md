MediaFlow — Media Downloader Platform
A modern media downloading platform built with Laravel 12, Vue 3, and Tailwind CSS 4, powered by yt-dlp and ffmpeg via asynchronous background queues.

Features
Media Processing: Instant URL analysis, multi-quality video/audio selection, and clean mp4/m4a format extraction with platform allowlist validation.

Async Queue Architecture: Dedicated background workers (downloads queue) preventing HTTP timeouts on large 4K media, with live status polling and signed secure file serving.

Admin Panel: Built-in content and media management (admin@mediaflow.app / admin123).

Requirements
PHP 8.4+ (Laravel 12) | Composer | Node.js 20+ / npm

yt-dlp & ffmpeg binaries placed under tools/

Quick Setup
Bash
# 1. Install dependencies
composer install && npm install

# 2. Environment configuration
cp .env.example .env && php artisan key:generate

# 3. Database & storage setup
php artisan migrate --seed && php artisan storage:link

# 4. Build assets
npm run build
Running Locally
Run the following services in separate terminals:

Laravel App: php artisan serve --port=8001

Vite HMR (Dev): npm run dev

Queue Worker (Required): php artisan queue:work --queue=downloads --timeout=0 --tries=3 --sleep=1

Access the app at http://localhost:8001

Developer Guidelines
Conventions: PascalCase for components (DownloadCard.vue), suffixed View.vue for pages, use<Feature>.js for composables.

Formatting: Pint for backend (./vendor/bin/pint), ESLint/Prettier for frontend.

Useful Commands:

Reset DB & seed: php artisan migrate:fresh --seed

Relink storage: php artisan storage:link
