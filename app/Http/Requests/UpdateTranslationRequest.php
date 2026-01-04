<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTranslationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'value'  => ['required', 'string'],
            'tags'   => ['array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }
}
