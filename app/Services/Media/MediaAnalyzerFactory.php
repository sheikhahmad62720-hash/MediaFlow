<?php

namespace App\Services\Media;

use App\Contracts\MediaAnalyzer;
use App\Exceptions\UnsupportedPlatformException;
use Illuminate\Support\Str;

class MediaAnalyzerFactory
{
    /**
     * @param  iterable<MediaAnalyzer>  $analyzers
     */
    public function __construct(
        protected iterable $analyzers,
    ) {}

    public function make(?string $analyzerType, string $url): MediaAnalyzer
    {
        foreach ($this->analyzers as $analyzer) {
            if ($this->accepts($analyzer, $analyzerType, $url)) {
                return $analyzer;
            }
        }

        // Fall back to the generic direct analyzer for arbitrary URLs.
        foreach ($this->analyzers as $analyzer) {
            if ($analyzer->supports($url)) {
                return $analyzer;
            }
        }

        throw new UnsupportedPlatformException('No analyzer is available for the given URL.');
    }

    protected function accepts(MediaAnalyzer $analyzer, ?string $analyzerType, string $url): bool
    {
        if ($analyzerType !== null) {
            return $this->matchesAnalyzerType($analyzer, $analyzerType) && $analyzer->supports($url);
        }

        return $analyzer->supports($url);
    }

    protected function matchesAnalyzerType(MediaAnalyzer $analyzer, string $analyzerType): bool
    {
        return match (Str::lower($analyzerType)) {
            'demo' => $analyzer instanceof DemoAnalyzer,
            'direct' => $analyzer instanceof DirectUrlAnalyzer,
            'ytdlp', 'yt-dlp' => $analyzer instanceof YtDlpAnalyzer,
            default => false,
        };
    }
}
