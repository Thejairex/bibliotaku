<?php

namespace App\Http\Controllers;

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Una sola query para conteos por status
        $statusCounts = $user->mediaEntries()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Una sola query para conteos por type + total
        $typeCounts = $user->mediaEntries()
            ->select('type', DB::raw('COUNT(*) as total'))
            ->groupBy('type')
            ->pluck('total', 'type');

        $totalEntries = $typeCounts->sum();

        // Rating promedio
        $avgRating = $user->mediaEntries()
            ->whereNotNull('rating')
            ->avg('rating');

        // Stats de status armados desde el mapa
        $stats = [
            'watching'  => (int) ($statusCounts[MediaStatus::Watching->value]   ?? 0)
                         + (int) ($statusCounts[MediaStatus::Rewatching->value] ?? 0),
            'reading'   => (int) ($statusCounts[MediaStatus::Reading->value]    ?? 0),
            'completed' => (int) ($statusCounts[MediaStatus::Completed->value]  ?? 0),
            'on_hold'   => (int) ($statusCounts[MediaStatus::OnHold->value]     ?? 0),
        ];

        // Type breakdown
        $animeCount = (int) ($typeCounts[MediaType::Anime->value]   ?? 0);
        $mangaCount = (int) ($typeCounts[MediaType::Manga->value]   ?? 0)
                    + (int) ($typeCounts[MediaType::Manhwa->value]  ?? 0)
                    + (int) ($typeCounts[MediaType::Manhua->value]  ?? 0);

        $animePercent = $totalEntries > 0 ? round(($animeCount / $totalEntries) * 100) : 0;
        $mangaPercent = $totalEntries > 0 ? round(($mangaCount / $totalEntries) * 100) : 0;

        // Entradas recientes
        $recentEntries = $user->mediaEntries()
            ->whereIn('status', [
                MediaStatus::Watching,
                MediaStatus::Reading,
                MediaStatus::Rewatching,
                MediaStatus::Completed,
            ])
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        // En progreso con total para barra
        $inProgress = $user->mediaEntries()
            ->whereIn('status', [
                MediaStatus::Watching,
                MediaStatus::Reading,
                MediaStatus::Rewatching,
            ])
            ->where(function ($q) {
                $q->whereNotNull('total_episodes')
                  ->orWhereNotNull('total_chapters');
            })
            ->limit(3)
            ->get();

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