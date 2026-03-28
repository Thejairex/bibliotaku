<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaEntry;
use App\Http\Requests\StoreMediaEntryRequest;
use App\Http\Requests\UpdateMediaEntryRequest;

class MediaEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $type   = $request->query('type');

        $entries = auth()->user()->mediaEntries()
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($type,   fn($q) => $q->where('type', $type))
            ->orderBy('updated_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $totalCount = auth()->user()->mediaEntries()->count();

        return view('my-list', compact('entries', 'totalCount', 'status', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaEntryRequest $request)
    {
        auth()->user()->mediaEntries()->create($request->validated());

        return redirect()->route('my-list.index')->with('success', __('Entry added to your archive!'));
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
            $jikan = app(\App\Services\JikanService::class);
            if ($mediaEntry->isAnime()) {
                $malData = $jikan->getAnime($mediaEntry->mal_id);
            } else {
                $malData = $jikan->getManga($mediaEntry->mal_id);
            }
        }

        return view('media-details', compact('mediaEntry', 'malData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaEntryRequest $request, MediaEntry $mediaEntry)
    {
        $mediaEntry->update($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Entry updated successfully!'),
                'entry'   => $mediaEntry
            ]);
        }

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

        return redirect()->route('my-list.index')->with('success', __('Entry removed from your archive.'));
    }
}
