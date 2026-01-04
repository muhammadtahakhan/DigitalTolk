<?php

namespace App\DTOs;

use App\Http\Requests\StoreTranslationRequest;
use App\Models\Translation;

class TranslationDTO
{
    public function __construct(
        public readonly string $key,
        public readonly int $locale_id,
        public readonly string $value,
        public readonly array $tags = []
    ) {}

    public static function fromRequest(
        StoreTranslationRequest $request,
        ?Translation $translation = null
    ): self {
        return new self(
            $request->input('key', $translation?->key),
            $request->input('locale_id', $translation?->locale_id),
            $request->input('value'),
            $request->input('tags', [])
        );
    }
}