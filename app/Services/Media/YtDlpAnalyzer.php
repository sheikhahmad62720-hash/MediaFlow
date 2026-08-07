<?php

namespace App\Services\Media;

use App\Contracts\MediaAnalyzer;
use App\DTOs\DownloadOption;
use App\DTOs\MediaMetadata;
use App\Enums\MediaFormat;
use App\Enums\MediaType;
use App\Exceptions\MediaAnalysisException;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

/**
 * Inspects real media using the yt-dlp engine.
 *
 * yt-dlp is invoked with --dump-json so no file is written during analysis;
 * only the live metadata is returned.
 */
class YtDlpAnalyzer implements MediaAnalyzer
{
    public function __construct(
        protected string $binary,
        protected int $timeout = 120,
        protected int $socketTimeout = 20,
        protected ?string $ffmpeg = null,
    ) {}

    public function supports(string $url): bool
    {
        return Str::startsWith($url, ['http://', 'https://']);
    }

    public function analyze(string $url): MediaMetadata
    {
        $process = new Process([
            $this->binary,
            '--no-update',
            '--no-warnings',
            '--skip-download',
            '--dump-json',
            '--no-playlist',
            '--no-check-certificates',
            '--socket-timeout', (string) $this->socketTimeout,
            $url,
        ]);

        $process->setTimeout($this->timeout);

        try {
            $process->run();
        } catch (ProcessTimedOutException) {
            throw new MediaAnalysisException('The media source took too long to respond.');
        }

        if (! $process->isSuccessful()) {
            throw new MediaAnalysisException($this->extractErrorMessage($process->getErrorOutput()));
        }

        $data = json_decode($process->getOutput(), true);

        if (! is_array($data) || ! isset($data['title'])) {
            throw new MediaAnalysisException('The media source could not be inspected.');
        }

        return $this->buildMetadata($data, $url);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function buildMetadata(array $data, string $url): MediaMetadata
    {
        $mediaType = $this->deriveMediaType($data);

        $options = $this->deriveOptions($data, $mediaType);

        if ($options === []) {
            throw new MediaAnalysisException('No downloadable formats were found for this media.');
        }

        $resolution = $options[0]->resolution ?? $data['resolution'] ?? null;

        return new MediaMetadata(
            title: (string) ($data['title'] ?? 'Untitled media'),
            thumbnailUrl: isset($data['thumbnail']) ? (string) $data['thumbnail'] : null,
            duration: isset($data['duration']) ? (int) round((float) $data['duration']) : null,
            resolution: $resolution,
            mediaType: $mediaType,
            platform: (string) ($data['extractor_key'] ?? $data['extractor'] ?? ''),
            formats: $options,
            raw: [
                'source' => $url,
                'extractor' => $data['extractor_key'] ?? $data['extractor'] ?? null,
                'id' => $data['id'] ?? null,
                'uploader' => $data['uploader'] ?? null,
                'channel' => $data['channel'] ?? null,
                'view_count' => $data['view_count'] ?? null,
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function deriveMediaType(array $data): MediaType
    {
        $hasAudio = ($data['acodec'] ?? 'none') !== 'none' || $data['vcodec'] ?? null !== null;
        $hasVideo = ($data['vcodec'] ?? 'none') !== 'none';

        if ($hasVideo) {
            return MediaType::VIDEO;
        }

        if ($hasAudio) {
            return MediaType::AUDIO;
        }

        return MediaType::VIDEO;
    }

    /**
     * Build the downloadable options offered to the client. Because the
     * web server may not ship ffmpeg, only formats yt-dlp can produce as a
     * single file are offered (a muxed container, or a native audio stream).
     *
     * @param  array<string, mixed>  $data
     *
     * @return list<DownloadOption>
     */
    protected function deriveOptions(array $data, MediaType $mediaType): array
    {
        if ($mediaType === MediaType::VIDEO) {
            return $this->videoOptions($data);
        }

        return $this->audioOptions($data);
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @return list<DownloadOption>
     */
    protected function videoOptions(array $data): array
    {
        $options = [];

        $videoStreams = $this->videoOnlyFormats($data, 'mp4');

        $seenHeights = [];

        foreach ($videoStreams as $format) {
            $height = (int) ($format['height'] ?? 0);

            if ($height < 360) {
                continue;
            }

            if (isset($seenHeights[$height])) {
                continue;
            }

            $seenHeights[$height] = true;

            $resolution = $this->formatResolution($format);

            $options[] = new DownloadOption(
                format: MediaFormat::MP4,
                quality: $resolution,
                fileSize: $this->formatSize($format),
                resolution: $resolution,
                mimeType: MediaFormat::MP4->mimeType(),
            );
        }

        if ($options === []) {
            $mp4 = $this->bestFormat($data, 'mp4');

            if ($mp4 !== null) {
                $options[] = new DownloadOption(
                    format: MediaFormat::MP4,
                    quality: 'best',
                    fileSize: $this->formatSize($mp4),
                    resolution: $this->formatResolution($mp4),
                    mimeType: MediaFormat::MP4->mimeType(),
                );
            }
        }

        $webm = $this->bestFormat($data, 'webm');

        if ($webm !== null) {
            $options[] = new DownloadOption(
                format: MediaFormat::WEBM,
                quality: 'best',
                fileSize: $this->formatSize($webm),
                resolution: $this->formatResolution($webm),
                mimeType: MediaFormat::WEBM->mimeType(),
            );
        }

        $audio = $this->bestAudioFormat($data, 'm4a');

        if ($audio !== null) {
            $options[] = new DownloadOption(
                format: MediaFormat::M4A,
                quality: 'high',
                fileSize: $this->formatSize($audio),
                mimeType: MediaFormat::M4A->mimeType(),
            );
        }

        return $options;
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @return list<DownloadOption>
     */
    protected function audioOptions(array $data): array
    {
        $options = [];

        $m4a = $this->bestAudioFormat($data, 'm4a');

        if ($m4a !== null) {
            $options[] = new DownloadOption(
                format: MediaFormat::M4A,
                quality: 'high',
                fileSize: $this->formatSize($m4a),
                mimeType: MediaFormat::M4A->mimeType(),
            );
        }

        $webm = $this->bestAudioFormat($data, 'webm');

        if ($webm !== null) {
            $options[] = new DownloadOption(
                format: MediaFormat::WEBM,
                quality: 'medium',
                fileSize: $this->formatSize($webm),
                mimeType: MediaFormat::WEBM->mimeType(),
            );
        }

        return $options;
    }

    /**
     * Find the best single-file (muxed) format with the given extension.
     *
     * @param  array<string, mixed>  $data
     *
     * @return array<string, mixed>|null
     */
    protected function bestFormat(array $data, string $extension): ?array
    {
        $formats = $this->formats($data);

        $candidates = array_filter(
            $formats,
            fn (array $f) => ($f['vcodec'] ?? 'none') !== 'none'
                && ($f['acodec'] ?? 'none') !== 'none'
                && ($f['ext'] ?? null) === $extension
                && ($f['protocol'] ?? 'https') !== 'm3u8_native'
        );

        if ($candidates === []) {
            return null;
        }

        return $this->pickBest($candidates);
    }

    /**
     * All video-only formats (no audio) with the given extension, sorted
     * from highest to lowest quality. These can be merged with audio using ffmpeg.
     *
     * @param  array<string, mixed>  $data
     *
     * @return list<array<string, mixed>>
     */
    protected function videoOnlyFormats(array $data, string $extension): array
    {
        $formats = $this->formats($data);

        $candidates = array_values(array_filter(
            $formats,
            fn (array $f) => ($f['vcodec'] ?? 'none') !== 'none'
                && ($f['acodec'] ?? 'none') === 'none'
                && ($f['ext'] ?? null) === $extension
                && ($f['protocol'] ?? 'https') !== 'm3u8_native'
        ));

        usort($candidates, function (array $a, array $b) {
            return (int) ($b['height'] ?? 0) <=> (int) ($a['height'] ?? 0);
        });

        return $candidates;
    }

    /**
     * Find the best audio-only format with the given extension.
     *
     * @param  array<string, mixed>  $data
     *
     * @return array<string, mixed>|null
     */
    protected function bestAudioFormat(array $data, string $extension): ?array
    {
        $formats = $this->formats($data);

        $candidates = array_filter(
            $formats,
            fn (array $f) => ($f['vcodec'] ?? 'none') === 'none'
                && ($f['acodec'] ?? 'none') !== 'none'
                && ($f['ext'] ?? null) === $extension
                && ($f['protocol'] ?? 'https') !== 'm3u8_native'
        );

        if ($candidates === []) {
            return null;
        }

        return $this->pickBest($candidates);
    }

    /**
     * @param  array<string, mixed>  $data
     *
     * @return list<array<string, mixed>>
     */
    protected function formats(array $data): array
    {
        $formats = $data['formats'] ?? [];

        if (! is_array($formats)) {
            return [];
        }

        return array_values(array_filter(
            $formats,
            static fn ($f) => is_array($f)
        ));
    }

    /**
     * @param  array<int, array<string, mixed>>  $candidates
     *
     * @return array<string, mixed>
     */
    protected function pickBest(array $candidates): array
    {
        usort($candidates, function (array $a, array $b) {
            $height = (int) ($b['height'] ?? 0) <=> (int) ($a['height'] ?? 0);

            if ($height !== 0) {
                return $height;
            }

            return (int) ($b['tbr'] ?? 0) <=> (int) ($a['tbr'] ?? 0);
        });

        return $candidates[0];
    }

    /**
     * @param  array<string, mixed>  $format
     */
    protected function formatSize(array $format): ?int
    {
        return isset($format['filesize']) && is_numeric($format['filesize'])
            ? (int) $format['filesize']
            : (isset($format['filesize_approx']) && is_numeric($format['filesize_approx'])
                ? (int) $format['filesize_approx']
                : null);
    }

    /**
     * @param  array<string, mixed>  $format
     */
    protected function formatResolution(array $format): ?string
    {
        if (isset($format['height']) && is_numeric($format['height'])) {
            return (int) $format['height'] >= 2160
                ? '4K'
                : (string) $format['height'].'p';
        }

        return null;
    }

    protected function extractErrorMessage(string $error): string
    {
        $error = trim($error);

        if ($error === '') {
            return 'The media source could not be inspected.';
        }

        $lines = preg_split('/\R/', $error) ?: [];

        $relevant = array_values(array_filter(
            $lines,
            static fn (string $line) => Str::contains($line, ['ERROR', 'Unsupported', 'Unable'])
        ));

        return Str::limit($relevant[0] ?? $error, 300);
    }
}
