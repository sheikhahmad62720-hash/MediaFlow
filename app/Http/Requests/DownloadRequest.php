<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'A media URL is required to download.',
            'url.url' => 'Please provide a valid http or https URL.',
            'format.in' => 'The selected format is not supported.',
        ];
    }
}
