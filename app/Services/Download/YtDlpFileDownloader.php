<?php

namespace App\Services\Download;

use App\Contracts\MediaDownloader;
use App\Enums\MediaFormat;
use App\Exceptions\DownloadException;
use App\Models\Download;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

/**
 * Downloads real media files using the yt-dlp engine.
 *
 * No ffmpeg dependency: only single-file formats (muxed containers and
 * native audio streams) are requested, so the process works on minimal
 * servers.
 */
class YtDlpFileDownloader implements MediaDownloader
{
    public function __construct(
        protected string $binary,
        protected string $root,
        protected int $timeout = 600,
        protected int $socketTimeout = 20,
        protected ?string $ffmpeg = null,
    ) {}

    public function download(Download $download): array
    {
        $relativeDirectory = 'downloads/'.now()->format('Y/m/d');
        $absoluteDirectory = rtrim($this->root, '/\\').'/'.$relativeDirectory;

        if (! is_dir($absoluteDirectory) && ! mkdir($absoluteDirectory, 0755, true) && ! is_dir($absoluteDirectory)) {
            throw new DownloadException('Unable to create the download directory.');
        }

        $stub = Str::slug($download->title ?? 'media', '_');
        $stub = $stub !== '' ? $stub : 'media';

        $outputTemplate = $absoluteDirectory.'/'.$stub.'_'.substr($download->id, 0, 8).'.%(ext)s';

        $command = array_values(array_filter([
            $this->binary,
            '--no-update',
            '--no-warnings',
            '--no-playlist',
            '--no-check-certificates',
            '--socket-timeout', (string) $this->socketTimeout,
            '--retries', 'infinite',
            '--file-access-retries', 'infinite',
            '--fragment-retries', 'infinite',
            '--extractor-retries', 'infinite',
            '--no-abort-on-error',
            '--ffmpeg-location', $this->ffmpeg ?? '',
            '--merge-output-format', 'mp4',
            '--newline',
            '-o', $outputTemplate,
            '-f', $this->formatSelector($download->format, $download->resolution),
            $download->source_url,
        ]));

        $process = new Process($command);
        $process->setTimeout($this->timeout);

        try {
            $process->run();
        } catch (ProcessTimedOutException) {
            throw new DownloadException('The download took too long and was aborted.');
        }

        if (! $process->isSuccessful()) {
            throw new DownloadException($this->extractErrorMessage($process->getErrorOutput()));
        }

        $file = $this->findDownloadedFile($absoluteDirectory, $download->id, $stub);

        if ($file === null) {
            throw new DownloadException('The download finished but the file could not be located.');
        }

        $relativePath = $relativeDirectory.'/'.basename($file);
        $size = filesize($file) ?: null;

        $download->update([
            'file_size' => $size,
            'processed_at' => now(),
        ]);

        return [
            'path' => $relativePath,
            'name' => basename($file),
            'size' => $size,
        ];
    }

    protected function formatSelector(MediaFormat $format, ?string $resolution): string
    {
        return match ($format) {
            MediaFormat::MP4 => $this->mp4Selector($resolution),
            MediaFormat::WEBM => $this->webmSelector($resolution),
            MediaFormat::M4A, MediaFormat::MP3 => 'bestaudio[ext=m4a]/bestaudio',
            MediaFormat::JPG, MediaFormat::PNG, MediaFormat::WEBP => 'b',
        };
    }

    /**
     * Select best video up to the requested height plus best audio,
     * merged to MP4 via ffmpeg. NO fallback to unconstrained quality.
     */
    protected function mp4Selector(?string $resolution): string
    {
        $height = $this->parseHeight($resolution);

        if ($height === null) {
            return 'bv*+ba/b[ext=mp4]/b';
        }

        return "bv*[height<={$height}][ext=mp4]+ba[ext=m4a]/b[ext=mp4][height<={$height}]/b";
    }

    protected function webmSelector(?string $resolution): string
    {
        $height = $this->parseHeight($resolution);

        if ($height === null) {
            return 'bv*[ext=webm]+ba[ext=webm]/b[ext=webm]/b';
        }

        return "bv*[ext=webm][height<={$height}]+ba[ext=webm]/b[ext=webm][height<={$height}]/b";
    }

    protected function parseHeight(?string $resolution): ?int
    {
        if ($resolution === null) {
            return null;
        }

        if (strcasecmp($resolution, '4K') === 0) {
            return 2160;
        }

        if (preg_match('/(\d+)p/i', $resolution, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    protected function findDownloadedFile(string $directory, string $downloadId, string $stub): ?string
    {
        $prefix = $stub.'_'.substr($downloadId, 0, 8);

        $files = glob($directory.'/'.$prefix.'.*');

        if ($files === false || $files === []) {
            return null;
        }

        return $files[0];
    }

    protected function extractErrorMessage(string $error): string
    {
        $error = trim($error);

        if ($error === '') {
            return 'The media download failed.';
        }

        $lines = preg_split('/\R/', $error) ?: [];

        $relevant = array_values(array_filter(
            $lines,
            static fn (string $line) => Str::contains($line, ['ERROR', 'Unsupported', 'Unable'])
        ));

        return Str::limit($relevant[0] ?? $error, 300);
    }
}
