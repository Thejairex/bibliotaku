<?php

namespace App\Http\Controllers;

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show()
    {
        $user = Auth::user();

        // 1. Core Stats
        $totalMedia = $user->mediaEntries()->count();
        $meanScore = $user->mediaEntries()->whereNotNull('rating')->avg('rating');
        $completed = $user->mediaEntries()->where('status', MediaStatus::Completed)->count();

        // 2. Days Spent Calculation (Estimated)
        // Average anime episode: 24 minutes
        // Average manga chapter: 10 minutes
        $animeMinutes = $user->mediaEntries()
            ->where('type', MediaType::Anime)
            ->sum('current_episode') * 24;

        $mangaMinutes = $user->mediaEntries()
            ->whereIn('type', [MediaType::Manga, MediaType::Manhwa, MediaType::Manhua])
            ->sum('current_chapter') * 10;

        $daysSpent = round(($animeMinutes + $mangaMinutes) / (60 * 24), 1);

        // 3. Collection Distribution
        $distribution = [
            'anime' => [
                'count' => $user->mediaEntries()->where('type', MediaType::Anime)->count(),
                'percent' => 0,
                'color' => 'primary',
            ],
            'manga' => [
                'count' => $user->mediaEntries()->whereIn('type', [MediaType::Manga, MediaType::Manhwa, MediaType::Manhua])->count(),
                'percent' => 0,
                'color' => 'secondary',
            ],
            'novel' => [
                'count' => $user->mediaEntries()->where('type', MediaType::Novel)->count(),
                'percent' => 0,
                'color' => 'tertiary',
            ],
        ];

        if ($totalMedia > 0) {
            foreach ($distribution as $key => $data) {
                $distribution[$key]['percent'] = round(($data['count'] / $totalMedia) * 100);
            }
        }

        // 4. Recent Activity (Last 5 updates)
        $recentActivity = $user->mediaEntries()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // 5. Showcase (Favorites - Top 5 by rating)
        $favorites = $user->mediaEntries()
            ->whereNotNull('rating')
            ->orderBy('rating', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('profile', compact(
            'user',
            'totalMedia',
            'meanScore',
            'daysSpent',
            'completed',
            'distribution',
            'recentActivity',
            'favorites'
        ));
    }
}
