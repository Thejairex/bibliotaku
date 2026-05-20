<?php

namespace App\Http\Controllers;

use App\Models\MediaEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Search', [
            'query' => request('q', ''),
            'type' => request('type'),
            'status' => request('status'),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $results = MediaEntry::query()
            ->forUser($request->user()->id)
            ->search($validated['q'] ?? null)
            ->when($validated['type'] ?? null, fn ($q, $type) => $q->ofType($type))
            ->when($validated['status'] ?? null, fn ($q, $status) => $q->withStatus($status))
            ->orderBy('title')
            ->limit($validated['limit'] ?? 20)
            ->get();

        return response()->json([
            'results' => $results,
        ]);
    }
}
