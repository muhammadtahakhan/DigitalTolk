<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportTranslationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale' => 'required|string|exists:locales,code',
            'tags'   => 'sometimes|array',
            'tags.*' => 'string', // or integer|exists:tags,id if using IDs
            
        ];
    }

    public function messages(): array
    {
        return [
            'locale.required' => 'Locale is required for export.',
            'locale.exists'   => 'The selected locale does not exist.',
        ];
    }
}
