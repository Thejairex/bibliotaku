<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Enums\MediaStatus;
use App\Enums\MediaType;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with stats and recent entries.
     */
    public function index()
    {
        $user = auth()->user();

        // Calculate average rating
        $avgRating = $user->mediaEntries()->whereNotNull('rating')->avg('rating');

        // Stats counts
        $stats = [
            'watching'  => $user->mediaEntries()->whereIn('status', [MediaStatus::Watching, MediaStatus::Rewatching])->count(),
            'reading'   => $user->mediaEntries()->where('status', MediaStatus::Reading)->count(),
            'completed' => $user->mediaEntries()->where('status', MediaStatus::Completed)->count(),
            'on_hold'   => $user->mediaEntries()->where('status', MediaStatus::OnHold)->count(),
        ];

        // Recent Updates
        $recentEntries = $user->mediaEntries()
            ->whereIn('status', [MediaStatus::Watching, MediaStatus::Reading, MediaStatus::Rewatching, MediaStatus::Completed])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Items in progress with a total count for progress bars
        $inProgress = $user->mediaEntries()
            ->whereIn('status', [MediaStatus::Watching, MediaStatus::Reading, MediaStatus::Rewatching])
            ->where(function ($query) {
                $query->whereNotNull('total_episodes')
                      ->orWhereNotNull('total_chapters');
            })
            ->limit(3)
            ->get();

        // Type breakdown
        $totalEntries = $user->mediaEntries()->count();
        $animeCount   = $user->mediaEntries()->where('type', MediaType::Anime)->count();
        $mangaCount   = $user->mediaEntries()->whereIn('type', [MediaType::Manga, MediaType::Manhwa, MediaType::Manhua])->count();

        $animePercent = $totalEntries > 0 ? round(($animeCount / $totalEntries) * 100) : 0;
        $mangaPercent = $totalEntries > 0 ? round(($mangaCount / $totalEntries) * 100) : 0;

        return view('dashboard', compact(
            'avgRating',
            'stats',
            'recentEntries',
            'inProgress',
            'animePercent',
            'mangaPercent'
        ));
    }
}
