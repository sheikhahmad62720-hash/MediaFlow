<?php

namespace App\Actions\Media;

use App\DTOs\MediaMetadata;
use App\Services\Media\MediaService;
use Illuminate\Support\Facades\Validator;

/**
 * Analyze a media URL and return its metadata.
 */
class AnalyzeMedia
{
    public function __construct(
        protected MediaService $media,
    ) {}

    public function handle(string $url): MediaMetadata
    {
        Validator::validate([
            'url' => [
                'required',
                'string',
                'max:'.config('media.max_url_length'),
                'url:http,https',
                function (string $attribute, mixed $value, $fail): void {
                    if (! is_valid_url((string) $value, config('media.max_url_length'))) {
                        $fail('The :attribute must be a valid http(s) URL.');
                    }
                },
            ],
        ], [
            'url.required' => 'Please paste a media link.',
            'url.url' => 'Please provide a valid URL.',
            'url.max' => 'The URL is too long.',
        ]);

        return $this->media->analyze($url);
    }
}
