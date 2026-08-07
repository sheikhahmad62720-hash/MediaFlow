<?php

namespace App\Services\Download;

use App\Contracts\MediaDownloader;
use App\Exceptions\DownloadException;
use App\Models\Download;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Downloads publicly accessible, directly-hosted media files.
 *
 * This downloader performs no authentication, scraping or DRM bypass — it only
 * fetches files that are openly linked and served over HTTP(S).
 */
class DirectFileDownloader implements MediaDownloader
{
    public function __construct(
        protected string $disk,
    ) {}

    public function download(Download $download): array
    {
        $path = $this->directory().'/'.$this->filename($download);

        $response = Http::withOptions(['stream' => true])->get($download->source_url);

        if ($response->failed()) {
            throw new DownloadException('The remote server returned an error: '.$response->status());
        }

        $handle = Storage::disk($this->disk)->writeStream($path, $response->toPsrResponse()->getBody());

        if (! is_resource($handle)) {
            throw new DownloadException('Failed to open a stream to the downloaded file.');
        }

        fclose($handle);

        $size = Storage::disk($this->disk)->size($path);

        $download->update([
            'file_size' => $size,
            'processed_at' => now(),
        ]);

        return [
            'path' => $path,
            'name' => basename($path),
            'size' => $size,
        ];
    }

    protected function directory(): string
    {
        return 'downloads/'.now()->format('Y/m/d');
    }

    protected function filename(Download $download): string
    {
        $extension = $download->format->extension();

        return ($download->file_name ?? 'media').'_'.Str::random(8).'.'.$extension;
    }
}
