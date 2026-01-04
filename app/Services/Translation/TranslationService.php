<?php

namespace App\Services\Translation;

use App\DTOs\TranslationDTO;
use App\Models\Translation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TranslationService implements TranslationServiceInterface
{
 public function paginate(array $filters): LengthAwarePaginator
{
    $query = Translation::query()
        ->with(['locale', 'tags'])
        ->when($filters['key'] ?? null,
            fn ($q, $key) =>
                $q->where('translations.key', 'like', "%{$key}%")
        )
        ->when($filters['locale'] ?? null,
            fn ($q, $locale) =>
                $q->whereHas('locale', fn ($l) =>
                    $l->where('code', $locale)
                )
        )
        ->when($filters['tag'] ?? null, function ($q, $tags) {
            $tags = is_array($tags) ? $tags : [$tags];

            $q->whereHas('tags', function ($t) use ($tags) {
                $t->whereIn('name', $tags);
            });
        })
        ->orderBy('translations.key');

        // See the SQL before executing
        \Log::info('SQL: ' . $query->toSql());
        \Log::info('Bindings: ' . json_encode($query->getBindings()));

        return $query->paginate(50);
}


    public function create(TranslationDTO $dto): Translation
    {
        return DB::transaction(function () use ($dto) {
            $translation = Translation::create([
                'key'       => $dto->key,
                'locale_id' => $dto->locale_id,
                'value'     => $dto->value,
            ]);

            if (!empty($dto->tags)) {
                $translation->tags()->sync($dto->tags);
            }

            Cache::forget('translations_export');

            return $translation->load(['locale', 'tags']);
        });
    }

    public function update(Translation $translation, TranslationDTO $dto): Translation
    {
        return DB::transaction(function () use ($translation, $dto) {
            $translation->update([
                'value' => $dto->value,
            ]);

            if (!empty($dto->tags)) {
                $translation->tags()->sync($dto->tags);
            }

            Cache::forget('translations_export');

            return $translation->load(['locale', 'tags']);
        });
    }

    public function delete(Translation $translation): void
    {
        DB::transaction(function () use ($translation) {
            $translation->tags()->detach();
            $translation->delete();
        });
        
        Cache::forget('translations_export');
    }

    public function search(string $query, ?string $locale, array $tags): LengthAwarePaginator
    {
        $startTime = microtime(true);

        $builder = Translation::query()
            ->with(['locale:id,code,name', 'tags:id,name'])
            ->where(function ($q) use ($query) {
            $q->where('key', 'like', "%{$query}%")
              ->orWhere('value', 'like', "%{$query}%")
              ;
            });

        if ($locale) {
            $builder->whereHas('locale', function ($q) use ($locale) {
            $q->where('code', $locale);
            });
        }

        if (!empty($tags)) {
            $builder->whereHas('tags', function ($q) use ($tags) {
            $q->whereIn('name', $tags);
            });
        }

        $result = $builder->latest()->paginate(50);

        $endTime = microtime(true);
        \Log::info('Search execution time: ' . ($endTime - $startTime) . ' seconds');

        return $result;
    }

    public function exportForFrontend(string $locale, array $tags): array
    {
        // Cache key includes all parameters for unique caching
        $cacheKey = "translations.export.{$locale}." . md5(json_encode($tags));

        return cache()->remember($cacheKey, 500, function () use ($locale, $tags) {
            // Optimized query - only select needed columns
            $query = Translation::query()
                ->select(['key', 'value'])
                ->whereHas('locale', function ($q) use ($locale) {
                    $q->where('code', $locale);
                });

            if (!empty($tags)) {
                $query->whereHas('tags', function ($q) use ($tags) {
                    $q->whereIn('name', $tags);
                });
            }
           
           return $translations = $query->get()->toArray();

        });
    }
}