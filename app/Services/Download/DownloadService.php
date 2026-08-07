<?php

namespace App\Services\Download;

use App\Contracts\MediaDownloader;
use App\DTOs\DownloadOption;
use App\Enums\ActivityType;
use App\Enums\DownloadStatus;
use App\Enums\MediaFormat;
use App\Events\DownloadCompleted;
use App\Events\DownloadFailed;
use App\Events\DownloadStarted;
use App\Exceptions\DownloadException;
use App\Jobs\ProcessDownloadJob;
use App\Models\Download;
use App\Models\SupportedPlatform;
use App\Repositories\ActivityLogRepository;
use App\Repositories\DownloadRepository;
use App\Repositories\PlatformRepository;
use App\Services\Media\MediaAnalyzerFactory;
use App\Services\Media\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DownloadService
{
    public function __construct(
        protected DownloadRepository $downloads,
        protected PlatformRepository $platforms,
        protected ActivityLogRepository $activities,
        protected MediaAnalyzerFactory $analyzers,
        protected iterable $downloaders,
        protected Request $request,
        protected string $disk,
        protected MediaService $media,
    ) {}

    /**
     * Create a download record and dispatch a background job to perform it.
     *
     * @return array{download: Download, options: array<int, array<string, mixed>>}
     */
    public function createDownload(string $url, ?MediaFormat $format = null, ?string $quality = null): array
    {
        $presence = $this->media->resolvePresence($url);

        $platform = $presence->slug !== null ? $this->platforms->find($presence->slug) : null;

        $analyzer = $this->analyzers->make($presence->analyzer, $url);
        $metadata = $analyzer->analyze($url);

        $selectedFormat = $format ?? ($metadata->formats[0]?->format ?? MediaFormat::MP4);

        if (! $metadata->formats[0]?->format instanceof MediaFormat || $metadata->formats === []) {
            throw new DownloadException('No downloadable formats are available.');
        }

        $option = $this->pickOption($metadata->formats, $selectedFormat, $platform, $quality);

        $download = $this->downloads->create([
            'id' => (string) Str::orderedUuid(),
            'user_id' => $this->request->user()?->getAuthIdentifier(),
            'platform_id' => $platform?->id,
            'source_url' => $url,
            'title' => $metadata->title,
            'thumbnail_url' => $metadata->thumbnailUrl,
            'duration' => $metadata->duration,
            'resolution' => $option->resolution ?? $metadata->resolution,
            'media_type' => $metadata->mediaType,
            'format' => $option->format,
            'quality' => $option->quality,
            'file_size' => $option->fileSize,
            'status' => DownloadStatus::QUEUED,
            'metadata' => array_merge($metadata->raw, ['analyzer' => $presence->analyzer]),
        ]);

        $this->activities->create(
            type: ActivityType::DOWNLOAD_CREATED,
            description: 'Created download for: '.Str::limit($metadata->title, 120),
            metadata: ['download_id' => $download->id, 'format' => $option->format->value],
            userId: $download->user_id,
            ip: $this->request->ip(),
            userAgent: $this->request->userAgent(),
        );

        if (config('media.queue_downloads')) {
            ProcessDownloadJob::dispatch($download->id)->onQueue('downloads');
        } else {
            ProcessDownloadJob::dispatchSync($download->id);

            $download->refresh();
        }

        return [
            'download' => $download,
            'options' => $metadata->formats,
        ];
    }

    /**
     * Resolve the matching download option or fall back to the first one.
     *
     * @param  list<DownloadOption>  $formats
     */
    protected function pickOption(array $formats, MediaFormat $format, ?SupportedPlatform $platform, ?string $quality): DownloadOption
    {
        foreach ($formats as $option) {
            if ($option->format !== $format) {
                continue;
            }

            $qualityMatches = $quality === null
                || $option->quality === $quality
                || $option->resolution === $quality;

            if ($qualityMatches && ($platform === null || $platform->supportsFormat($format))) {
                return $option;
            }
        }

        foreach ($formats as $option) {
            if ($option->format === $format) {
                return $option;
            }
        }

        return $formats[0];
    }

    /**
     * Execute the actual file retrieval (intended to run inside the queued job).
     */
    public function perform(string $downloadId): void
    {
        $download = $this->downloads->find($downloadId);

        if ($download === null) {
            throw new DownloadException("Download #{$downloadId} not found.");
        }

        if (! in_array($download->status, [DownloadStatus::QUEUED, DownloadStatus::FAILED], true)) {
            return;
        }

        $download->update(['status' => DownloadStatus::PROCESSING]);

        event(new DownloadStarted($download));

        $platform = $download->platform?->analyzer;

        $analyzerKey = $download->metadata['analyzer'] ?? $platform ?? 'demo';

        /** @var MediaDownloader $downloader */
        $downloader = $this->resolveDownloader($analyzerKey);

        try {
            $result = $downloader->download($download);

            $download->markAsCompleted($result['path'], $result['name']);

            $this->recordCompletion($download);
        } catch (\Throwable $e) {
            $download->markAsFailed($e->getMessage());

            event(new DownloadFailed($download, $e));

            $this->activities->create(
                type: ActivityType::DOWNLOAD_FAILED,
                description: 'Download failed: '.Str::limit($e->getMessage(), 120),
                metadata: ['download_id' => $download->id],
                userId: $download->user_id,
            );
        }
    }

    protected function resolveDownloader(?string $analyzer): MediaDownloader
    {
        foreach ($this->downloaders as $downloader) {
            if ($downloader instanceof YtDlpFileDownloader && in_array($analyzer, ['ytdlp', 'yt-dlp'], true)) {
                return $downloader;
            }

            if ($downloader instanceof DirectFileDownloader && $analyzer === 'direct') {
                return $downloader;
            }

            if ($downloader instanceof DemoFileDownloader && $analyzer === 'demo') {
                return $downloader;
            }
        }

        return $this->downloaders[0] ?? throw new DownloadException('No downloader configured.');
    }

    protected function recordCompletion(Download $download): void
    {
        $download->platform?->slug !== null
            ? $this->platforms->incrementDownload($download->platform->slug)
            : null;

        $this->activities->create(
            type: ActivityType::DOWNLOAD_COMPLETED,
            description: 'Completed download: '.Str::limit($download->title, 120),
            metadata: ['download_id' => $download->id, 'file_size' => $download->file_size],
            userId: $download->user_id,
        );

        event(new DownloadCompleted($download));
    }
}
