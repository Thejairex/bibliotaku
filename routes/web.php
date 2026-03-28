<?php

use App\Models\MediaEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('my-list', function (Request $request) {
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
    })->name('my-list');

    Route::post('my-list', function (Request $request) {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'original_title'  => 'nullable|string|max:255',
            'type'            => 'required|in:anime,manga,manhwa,manhua,novel',
            'status'          => 'required|in:watching,rewatching,reading,completed,on_hold,dropped,plan_to_watch',
            'cover_url'       => 'nullable|url|max:2048',
            'mal_id'          => 'nullable|integer|min:1',
            'current_episode' => 'nullable|integer|min:0',
            'total_episodes'  => 'nullable|integer|min:0',
            'current_chapter' => 'nullable|integer|min:0',
            'total_chapters'  => 'nullable|integer|min:0',
            'current_volume'  => 'nullable|integer|min:0',
            'total_volumes'   => 'nullable|integer|min:0',
            'rating'          => 'nullable|integer|min:1|max:10',
            'notes'           => 'nullable|string|max:2000',
        ]);

        auth()->user()->mediaEntries()->create($validated);

        return redirect()->route('my-list')->with('success', __('Entry added to your archive!'));
    })->name('my-list.store');

    Route::get('my-list/{mediaEntry}', function (MediaEntry $mediaEntry) {
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
    })->name('my-list.show');
});

require __DIR__.'/settings.php';
