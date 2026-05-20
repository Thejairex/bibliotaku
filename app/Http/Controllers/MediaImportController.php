<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommitMediaImportRequest;
use App\Http\Requests\ParseMediaImportRequest;
use App\Services\MediaImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MediaImportController extends Controller
{
    public function __construct(private readonly MediaImportService $importer) {}

    public function create(): Response
    {
        return Inertia::render('MyList/Import');
    }

    public function parse(ParseMediaImportRequest $request): JsonResponse
    {
        $contents = file_get_contents($request->file('file')->getRealPath());

        try {
            $payload = json_decode((string) $contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return response()->json([
                'message' => 'El archivo no es un JSON válido: '.$e->getMessage(),
            ], 422);
        }

        if (! is_array($payload)) {
            return response()->json(['message' => 'El JSON raíz debe ser un objeto.'], 422);
        }

        try {
            $result = $this->importer->parse($payload, $request->user()->id);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json($result);
    }

    public function store(CommitMediaImportRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $counts = $this->importer->commit(
            $request->user(),
            $data['entries'],
            $data['mapping'] ?? [],
            $data['fallback_type'],
        );

        $message = sprintf(
            'Importación completada · Nuevas: %d · Actualizadas: %d · Saltadas: %d',
            $counts['created'],
            $counts['updated'],
            $counts['skipped'],
        );

        return redirect()->route('my-list')->with('success', $message);
    }
}
