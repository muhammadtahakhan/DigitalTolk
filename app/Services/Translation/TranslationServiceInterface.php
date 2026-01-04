<?php
namespace App\Services\Translation;

use App\DTOs\TranslationDTO;
use App\Models\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TranslationServiceInterface
{
    public function paginate(array $filters): LengthAwarePaginator;

    public function create(TranslationDTO $dto): Translation;

    public function update(Translation $translation, TranslationDTO $dto): Translation;

    public function delete(Translation $translation): void;
    public function search(string $query, ?string $locale, array $tags): LengthAwarePaginator;
    public function exportForFrontend(string $locale, array $tags): array;


}
