<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaEntryRequest;
use App\Http\Requests\UpdateMediaEntryRequest;
use App\Models\MediaEntry;
use App\Services\JikanService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MediaEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'type', 'search']);

        $entries = MediaEntry::forUser(auth()->id())
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->withStatus($v))
            ->when($filters['type'] ?? null, fn ($q, $v) => $q->ofType($v))
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->search($v))
            ->orderBy('updated_at', 'desc')
            ->paginate(24)
            ->withQueryString();

        return Inertia::render('MyList/Index', [
            'entries' => $entries,
            'filters' => $filters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaEntryRequest $request)
    {
        if (MediaEntry::where('user_id', auth()->id())->where('mal_id', $request->mal_id)->exists()) {
            return back()->with('error', __('Entry already exists in your archive!'));
        }

        $entry = auth()->user()->mediaEntries()->create($request->validated());

        return back()->with('success', __('Entry added to your archive!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(MediaEntry $mediaEntry)
    {
        if ($mediaEntry->user_id !== auth()->id()) {
            abort(403);
        }

        $malData = null;
        if ($mediaEntry->mal_id) {
            $jikan = app(JikanService::class);
            if ($mediaEntry->isAnime()) {
                $malData = $jikan->getAnime($mediaEntry->mal_id);
            } else {
                $malData = $jikan->getManga($mediaEntry->mal_id);
            }
        }

        return Inertia::render('MyList/Show', [
            'entry' => $mediaEntry,
            'malData' => $malData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaEntryRequest $request, MediaEntry $mediaEntry)
    {
        $mediaEntry->update($request->validated());

        return back()->with('success', __('Entry updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaEntry $mediaEntry)
    {
        if ($mediaEntry->user_id !== auth()->id()) {
            abort(403);
        }

        $mediaEntry->delete();

        return redirect()->route('my-list')->with('success', __('Entry removed from your archive.'));
    }
}
