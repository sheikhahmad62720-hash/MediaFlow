<?php

namespace App\Providers;

use App\Contracts\MediaAnalyzer;
use App\Events\DownloadCompleted;
use App\Events\DownloadFailed;
use App\Events\DownloadStarted;
use App\Listeners\RecordDownloadActivity;
use App\Listeners\SendDownloadNotification;
use App\Repositories\ActivityLogRepository;
use App\Repositories\DownloadRepository;
use App\Repositories\PlatformRepository;
use App\Services\Download\DemoFileDownloader;
use App\Services\Download\DirectFileDownloader;
use App\Services\Download\DownloadService;
use App\Services\Download\YtDlpFileDownloader;
use App\Services\Media\DemoAnalyzer;
use App\Services\Media\DirectUrlAnalyzer;
use App\Services\Media\MediaAnalyzerFactory;
use App\Services\Media\MediaService;
use App\Services\Media\YtDlpAnalyzer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public const ANALYZERS = [
        YtDlpAnalyzer::class,
        DirectUrlAnalyzer::class,
        DemoAnalyzer::class,
    ];

    public const DOWNLOADERS = [
        YtDlpFileDownloader::class,
        DirectFileDownloader::class,
        DemoFileDownloader::class,
    ];

    public function register(): void
    {
        $this->app->singleton(MediaAnalyzerFactory::class, function ($app) {
            $analyzers = [];

            foreach (array_reverse(self::ANALYZERS) as $analyzerClass) {
                if ($app->make($analyzerClass) instanceof MediaAnalyzer) {
                    $analyzers[] = $app->make($analyzerClass);
                }
            }

            return new MediaAnalyzerFactory($analyzers);
        });

        $this->app->singleton(MediaService::class, function ($app) {
            return new MediaService(
                analyzers: $app->make(MediaAnalyzerFactory::class),
                platforms: $app->make(PlatformRepository::class),
                activities: $app->make(ActivityLogRepository::class),
                request: $app->make(Request::class),
            );
        });
    }

    public function boot(Kernel $kernel): void
    {
        $this->registerRateLimiters();
        $this->registerEvents();
        $this->registerBindings();
    }

    protected function registerRateLimiters(): void
    {
        RateLimiter::for('analyze', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(config('media.rate_limit.analyze'))->by($request->user()->getAuthIdentifier())
                : Limit::perMinute(config('media.rate_limit.analyze'))->by($request->ip());
        });

        RateLimiter::for('download', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(config('media.rate_limit.download'))->by($request->user()->getAuthIdentifier())
                : Limit::perMinute(config('media.rate_limit.download'))->by($request->ip());
        });
    }

    protected function registerEvents(): void
    {
        Event::listen(DownloadStarted::class, [RecordDownloadActivity::class, 'handle']);
        Event::listen(DownloadCompleted::class, [RecordDownloadActivity::class, 'handleCompleted']);
        Event::listen(DownloadFailed::class, [RecordDownloadActivity::class, 'handleFailed']);
        Event::listen(DownloadCompleted::class, SendDownloadNotification::class);
    }

    protected function registerBindings(): void
    {
        $disk = config('media.download_disk');

        $ytDlp = config('media.yt_dlp');
        $root = Storage::disk($disk)->path('');

        $this->app->bind(YtDlpAnalyzer::class, fn () => new YtDlpAnalyzer(
            binary: $ytDlp['binary'],
            timeout: $ytDlp['timeout'],
            socketTimeout: $ytDlp['socket_timeout'],
            ffmpeg: $ytDlp['ffmpeg'],
        ));

        $this->app->bind(DirectFileDownloader::class, fn () => new DirectFileDownloader($disk));
        $this->app->bind(DemoFileDownloader::class, fn () => new DemoFileDownloader($disk));
        $this->app->bind(
            YtDlpFileDownloader::class,
            fn () => new YtDlpFileDownloader(
                binary: $ytDlp['binary'],
                root: $root,
                timeout: $ytDlp['timeout'] * 2,
                socketTimeout: $ytDlp['socket_timeout'],
                ffmpeg: $ytDlp['ffmpeg'],
            )
        );

        $this->app->singleton(DownloadService::class, function ($app) use ($disk) {
            return new DownloadService(
                downloads: $app->make(DownloadRepository::class),
                platforms: $app->make(PlatformRepository::class),
                activities: $app->make(ActivityLogRepository::class),
                analyzers: $app->make(MediaAnalyzerFactory::class),
                downloaders: [
                    $app->make(YtDlpFileDownloader::class),
                    $app->make(DirectFileDownloader::class),
                    $app->make(DemoFileDownloader::class),
                ],
                request: $app->make(Request::class),
                disk: $disk,
                media: $app->make(MediaService::class),
            );
        });
    }
}
