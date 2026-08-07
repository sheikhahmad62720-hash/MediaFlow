<?php

namespace App\Services\Download;

use App\Contracts\MediaDownloader;
use App\Enums\MediaFormat;
use App\Models\Download;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Generates a small, clearly-marked placeholder file so the complete
 * analyze -> download -> store -> serve workflow is functional in demo mode.
 *
 * In a production deployment this downloader is replaced by providers that
 * integrate with officially-supported media APIs.
 */
class DemoFileDownloader implements MediaDownloader
{
    public function __construct(
        protected string $disk,
    ) {}

    public function download(Download $download): array
    {
        $extension = $download->format->extension();
        $path = $this->directory().'/'.$this->filename($download, $extension);

        $contents = $this->buildContents($download);

        Storage::disk($this->disk)->put($path, $contents);

        $download->update([
            'file_size' => Storage::disk($this->disk)->size($path),
            'processed_at' => now(),
        ]);

        return [
            'path' => $path,
            'name' => basename($path),
            'size' => Storage::disk($this->disk)->size($path),
        ];
    }

    protected function directory(): string
    {
        return 'downloads/'.now()->format('Y/m/d');
    }

    protected function filename(Download $download, string $extension): string
    {
        $slug = Str::slug($download->title ?? 'media');

        return ($slug ?: 'media').'_'.Str::random(8).'.'.$extension;
    }

    protected function buildContents(Download $download): string
    {
        $header = match ($download->format) {
            MediaFormat::MP3 => 'ID3 demo placeholder',
            MediaFormat::M4A => 'ftypisom demo placeholder',
            default => 'MediaFlow demo download',
        };

        $body = json_encode([
            'application' => 'MediaFlow',
            'notice' => 'This is a demo download generated to showcase the end-to-end flow.',
            'title' => $download->title,
            'source_url' => $download->source_url,
            'media_type' => $download->media_type,
            'format' => $download->format?->value,
            'resolution' => $download->resolution,
            'duration' => $download->duration,
            'platform' => $download->platform?->name,
        ], JSON_PRETTY_PRINT);

        return $header."\n".$body."\n";
    }
}
