<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreTranslationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

   public function rules(): array
    {
        return [
            'key' => [
                'required',
                'string',
                'max:255',
                Rule::unique('translations')->where('locale_id', $this->locale_id),
            ],
            'locale_id' => 'required|integer|exists:locales,id',
            'value' => 'required|string',
            'tags' => 'sometimes|array',
            'tags.*' => 'integer|exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'locale_id.exists' => 'The selected locale does not exist.',
            'key.required' => 'The translation key is required.',
            'key.string' => 'The translation key must be a valid string.',
            'key.max' => 'The translation key may not exceed 255 characters.',
            'key.unique' => 'This translation key already exists for the selected locale.',
        ];
    }
}