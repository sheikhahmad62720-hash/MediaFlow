<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyzeMediaRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'Please paste a media link to get started.',
            'url.url' => 'Please provide a valid http or https URL.',
            'url.max' => 'That URL is too long.',
        ];
    }
}
