<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MediaEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MediaEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->mediaEntries()
            ->latest()
            ->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'original_title' => 'nullable|string|max:255',
            'type' => 'required|string',
            'status' => 'required|string',
            'current_episode' => 'nullable|integer',
            'total_episodes' => 'nullable|integer',
            'current_chapter' => 'nullable|integer',
            'total_chapters' => 'nullable|integer',
            'current_volume' => 'nullable|integer',
            'total_volumes' => 'nullable|integer',
            'rating' => 'nullable|integer|min:0|max:10',
            'notes' => 'nullable|string',
            'cover_url' => 'nullable|url',
            'mal_id' => 'nullable|integer',
        ]);

        $mediaEntry = $request->user()->mediaEntries()->create($validated);

        return response()->json($mediaEntry, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, MediaEntry $mediaEntry)
    {
        $this->authorizeUser($request, $mediaEntry);

        return $mediaEntry;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MediaEntry $mediaEntry)
    {
        $this->authorizeUser($request, $mediaEntry);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'original_title' => 'nullable|string|max:255',
            'status' => 'sometimes|string',
            'current_episode' => 'nullable|integer',
            'current_chapter' => 'nullable|integer',
            'current_volume' => 'nullable|integer',
            'rating' => 'nullable|integer|min:0|max:10',
            'notes' => 'nullable|string',
        ]);

        $mediaEntry->update($validated);

        return $mediaEntry;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, MediaEntry $mediaEntry)
    {
        $this->authorizeUser($request, $mediaEntry);

        $mediaEntry->delete();

        return response()->noContent();
    }

    /**
     * Ensure the user owns the media entry.
     */
    protected function authorizeUser(Request $request, MediaEntry $mediaEntry)
    {
        if ($mediaEntry->user_id !== $request->user()->id) {
            abort(Response::HTTP_FORBIDDEN, 'You do not own this media entry.');
        }
    }
}
