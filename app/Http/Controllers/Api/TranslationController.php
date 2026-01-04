<?php
namespace App\Http\Controllers\Api;

use App\DTOs\TranslationDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExportTranslationsRequest;
use App\Http\Requests\StoreTranslationRequest;
use App\Http\Requests\UpdateTranslationRequest;
use App\Models\Translation;
use App\Services\Translation\TranslationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(
        private readonly TranslationServiceInterface $service
    ) {}

    public function index(Request $request)
    {
        return response()->json(
            $this->service->paginate($request->only([
                'key', 'locale', 'tag'
            ]))
        );
    }

    public function store(StoreTranslationRequest $request)
    {
        $dto = TranslationDTO::fromRequest($request);

        return response()->json(
            $this->service->create($dto),
            201
        );
    }

    public function show(Translation $translation)
    {
        return response()->json(
            $translation->load(['locale', 'tags'])
        );
    }

    public function update(UpdateTranslationRequest $request, Translation $translation)
    {
        $dto = new TranslationDTO(
            $translation->key,
            $translation->locale_id,
            $request->value,
            $request->tags ?? []
        );

        return response()->json(
            $this->service->update($translation, $dto)
        );
    }

    public function destroy(Translation $translation)
    {
        $this->service->delete($translation);

        return response()->noContent();
    }

    /**
 * Search translations
 */
public function search(Request $request): JsonResponse
{
    $validated = $request->validate([
        'query' => 'required|string|min:2',
        'locale' => 'sometimes|string',
        'tags' => 'sometimes|array',
    ]);

    return response()->json(
        $this->service->search(
            $validated['query'],
            $validated['locale'] ?? null,
            $validated['tags'] ?? []
        )
    );
}

/**
 * Export translations for frontend (Vue.js, React, etc.)
 * Must respond in <500ms even with 100k+ records
 */
public function export(ExportTranslationsRequest $request): JsonResponse
{
   
    $translations = $this->service->exportForFrontend(
        $request->validated()['locale'],
        $request->validated()['tags'] ?? [],
        
    );

    return response()->json($translations);
        
}
}
