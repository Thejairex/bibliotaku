<?php

namespace App\Http\Controllers;

use App\Enums\MediaStatus;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        // Una sola query para conteos por status
        $statusCounts = $user->mediaEntries()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalEntries = $statusCounts->sum();

        $stats = [
            'total_entries' => $totalEntries,
            'watching' => (int) ($statusCounts[MediaStatus::Watching->value] ?? 0)
                         + (int) ($statusCounts[MediaStatus::Rewatching->value] ?? 0),
            'reading' => (int) ($statusCounts[MediaStatus::Reading->value] ?? 0),
            'completed' => (int) ($statusCounts[MediaStatus::Completed->value] ?? 0),
            'on_hold' => (int) ($statusCounts[MediaStatus::OnHold->value] ?? 0),
            'dropped' => (int) ($statusCounts[MediaStatus::Dropped->value] ?? 0),
            'plan_to_watch' => (int) ($statusCounts[MediaStatus::PlanToWatch->value] ?? 0),
        ];

        $recentEntries = $user->mediaEntries()
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recent_entries' => $recentEntries,
        ]);
    }
}
