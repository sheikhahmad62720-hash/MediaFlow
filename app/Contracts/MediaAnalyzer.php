<?php

namespace App\Contracts;

use App\DTOs\MediaMetadata;
use App\Exceptions\MediaAnalysisException;

interface MediaAnalyzer
{
    /**
     * Determine whether this analyzer can handle the given URL.
     */
    public function supports(string $url): bool;

    /**
     * Inspect a media URL and return its metadata.
     *
     * @throws MediaAnalysisException
     */
    public function analyze(string $url): MediaMetadata;
}
