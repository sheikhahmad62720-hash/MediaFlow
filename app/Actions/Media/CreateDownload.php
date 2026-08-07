<?php

namespace App\Actions\Media;

use App\Enums\MediaFormat;
use App\Services\Download\DownloadService;
use Illuminate\Support\Facades\Validator;

class CreateDownload
{
    public function __construct(
        protected DownloadService $downloads,
    ) {}

    public function handle(string $url, ?string $format = null, ?string $quality = null): array
    {
        $data = Validator::validate([
            'url' => $url,
            'format' => $format,
            'quality' => $quality,
        ], [
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
            'format' => ['nullable', 'string', 'in:mp4,webm,mp3,m4a,jpg,png,webp'],
            'quality' => ['nullable', 'string', 'max:20'],
        ], [
            'url.required' => 'A media URL is required to download.',
            'url.url' => 'Please provide a valid http or https URL.',
            'format.in' => 'The selected format is not supported.',
        ]);

        $parsedFormat = $data['format'] !== null ? MediaFormat::tryFrom($data['format']) : null;

        $result = $this->downloads->createDownload($data['url'], $parsedFormat, $data['quality']);

        return $result;
    }
}
