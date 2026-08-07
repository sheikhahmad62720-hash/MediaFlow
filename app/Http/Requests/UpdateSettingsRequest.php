<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:128'],
            'group' => ['nullable', 'string', 'max:64'],
            'value' => ['required'],
            'type' => ['nullable', 'string', Rule::in(['string', 'integer', 'boolean', 'json'])],
            'is_public' => ['nullable', 'boolean'],
        ];
    }
}
