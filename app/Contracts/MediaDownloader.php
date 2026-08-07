<?php

namespace App\Contracts;

use App\Exceptions\DownloadException;
use App\Models\Download;

interface MediaDownloader
{
    /**
     * Persist the media source to local storage and return the stored path.
     *
     *
     * @return array{path: string, name: string, size: int|null}
     *
     * @throws DownloadException
     */
    public function download(Download $download): array;
}
