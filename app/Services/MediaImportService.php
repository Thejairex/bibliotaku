<?php

namespace App\Services;

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use App\Models\MediaEntry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MediaImportService
{
    public function parse(array $payload, ?int $userId = null): array
    {
        $rawManga = $payload['backupManga'] ?? null;
        $rawCategories = $payload['backupCategories'] ?? [];

        if (! is_array($rawManga)) {
            throw new \InvalidArgumentException('El JSON no contiene la clave "backupManga".');
        }

        $categories = $this->normalizeCategories($rawCategories);
        $entries = [];

        foreach ($rawManga as $index => $manga) {
            if (! is_array($manga) || ! isset($manga['title'])) {
                continue;
            }

            $entries[] = $this->normalizeManga($manga, $index);
        }

        $categoryCounts = [];
        foreach ($entries as $entry) {
            foreach ($entry['category_ids'] as $cid) {
                $categoryCounts[$cid] = ($categoryCounts[$cid] ?? 0) + 1;
            }
        }
        foreach ($categories as &$cat) {
            $cat['count'] = $categoryCounts[$cat['id']] ?? 0;
        }
        unset($cat);

        $existingTitles = [];
        if ($userId !== null) {
            $existingTitles = MediaEntry::query()
                ->forUser($userId)
                ->pluck('current_chapter', DB::raw('LOWER(title)'))
                ->all();
        }

        $stats = ['total' => count($entries), 'new' => 0, 'update' => 0, 'skip' => 0];
        foreach ($entries as &$entry) {
            $key = mb_strtolower($entry['title']);
            if (! array_key_exists($key, $existingTitles)) {
                $entry['dup_status'] = 'new';
                $stats['new']++;
            } elseif (($entry['current_chapter'] ?? 0) > ((int) ($existingTitles[$key] ?? 0))) {
                $entry['dup_status'] = 'update';
                $stats['update']++;
            } else {
                $entry['dup_status'] = 'skip';
                $stats['skip']++;
            }
        }
        unset($entry);

        return [
            'categories' => array_values($categories),
            'entries' => $entries,
            'stats' => $stats,
        ];
    }

    /**
     * @return array{created:int,updated:int,skipped:int}
     */
    public function commit(User $user, array $entries, array $categoryTypeMap, string $fallbackType): array
    {
        $fallback = MediaType::from($fallbackType);
        $counts = ['created' => 0, 'updated' => 0, 'skipped' => 0];

        DB::transaction(function () use ($user, $entries, $categoryTypeMap, $fallback, &$counts) {
            foreach ($entries as $entry) {
                $type = $this->resolveType($entry['category_ids'] ?? [], $categoryTypeMap, $fallback);
                $existing = MediaEntry::query()
                    ->forUser($user->id)
                    ->whereRaw('LOWER(title) = ?', [mb_strtolower($entry['title'])])
                    ->where('type', $type->value)
                    ->first();

                $payload = [
                    'title' => $entry['title'],
                    'original_title' => $entry['original_title'] ?? null,
                    'type' => $type->value,
                    'cover_url' => $entry['cover_url'] ?? null,
                    'status' => $entry['inferred_status'],
                    'current_chapter' => $entry['current_chapter'] ?? 0,
                    'total_chapters' => $entry['total_chapters'] ?? null,
                    'notes' => $entry['notes'] ?? null,
                ];

                if ($existing === null) {
                    $user->mediaEntries()->create($payload);
                    $counts['created']++;

                    continue;
                }

                $existingChapter = (int) ($existing->current_chapter ?? 0);
                $newChapter = (int) ($payload['current_chapter'] ?? 0);

                if ($newChapter <= $existingChapter) {
                    $counts['skipped']++;

                    continue;
                }

                $existing->update([
                    'current_chapter' => $newChapter,
                    'total_chapters' => max((int) $existing->total_chapters, (int) ($payload['total_chapters'] ?? 0)) ?: $existing->total_chapters,
                    'status' => $payload['status'],
                    'cover_url' => $existing->cover_url ?? $payload['cover_url'],
                ]);
                $counts['updated']++;
            }
        });

        return $counts;
    }

    private function normalizeCategories(array $rawCategories): array
    {
        $out = [];
        foreach ($rawCategories as $cat) {
            if (! is_array($cat) || ! isset($cat['id'])) {
                continue;
            }
            $id = (string) $cat['id'];
            $out[$id] = [
                'id' => $id,
                'name' => (string) ($cat['name'] ?? "Categoría {$id}"),
                'count' => 0,
            ];
        }

        return $out;
    }

    private function normalizeManga(array $manga, int $index): array
    {
        $chapters = is_array($manga['chapters'] ?? null) ? $manga['chapters'] : [];

        $totalFromCount = count($chapters);
        $maxNumber = 0.0;
        $maxRead = 0.0;
        $readCount = 0;
        foreach ($chapters as $ch) {
            $num = (float) ($ch['chapterNumber'] ?? 0);
            if ($num > $maxNumber) {
                $maxNumber = $num;
            }
            if (! empty($ch['read'])) {
                $readCount++;
                if ($num > $maxRead) {
                    $maxRead = $num;
                }
            }
        }

        $totalChapters = (int) max(ceil($maxNumber), $totalFromCount);
        $currentChapter = (int) floor($maxRead);

        if ($totalChapters > 0 && $readCount > 0 && $readCount >= $totalFromCount) {
            $status = MediaStatus::Completed;
        } elseif ($readCount > 0) {
            $status = MediaStatus::Reading;
        } else {
            $status = MediaStatus::PlanToWatch;
        }

        $categoryIds = [];
        if (isset($manga['categories']) && is_array($manga['categories'])) {
            foreach ($manga['categories'] as $cid) {
                $categoryIds[] = (string) $cid;
            }
        }

        $coverUrl = $manga['thumbnailUrl'] ?? null;
        if (! is_string($coverUrl) || filter_var($coverUrl, FILTER_VALIDATE_URL) === false) {
            $coverUrl = null;
        }

        $notes = isset($manga['description']) ? mb_substr((string) $manga['description'], 0, 2000) : null;

        return [
            'index' => $index,
            'title' => mb_substr((string) $manga['title'], 0, 255),
            'original_title' => null,
            'cover_url' => $coverUrl,
            'total_chapters' => $totalChapters ?: null,
            'current_chapter' => $currentChapter,
            'inferred_status' => $status->value,
            'notes' => $notes,
            'category_ids' => $categoryIds,
        ];
    }

    private function resolveType(array $categoryIds, array $mapping, MediaType $fallback): MediaType
    {
        foreach ($categoryIds as $cid) {
            $cid = (string) $cid;
            if (isset($mapping[$cid]) && is_string($mapping[$cid])) {
                $candidate = MediaType::tryFrom($mapping[$cid]);
                if ($candidate !== null) {
                    return $candidate;
                }
            }
        }

        return $fallback;
    }
}
