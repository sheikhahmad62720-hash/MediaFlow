<?php

namespace App\Http\Requests;

use App\Enums\DownloadStatus;
use App\Enums\MediaType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DownloadIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', Rule::enum(DownloadStatus::class)],
            'platform' => ['nullable', 'string', 'exists:supported_platforms,slug'],
            'format' => ['nullable', 'string', Rule::in(['mp4', 'webm', 'mp3', 'm4a', 'jpg', 'png', 'webp'])],
            'type' => ['nullable', 'string', Rule::enum(MediaType::class)],
            'sort' => ['nullable', 'string', Rule::in(['created_at', 'file_size', 'title', 'duration'])],
            'direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
