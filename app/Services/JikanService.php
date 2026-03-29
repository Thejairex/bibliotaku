<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class JikanService
{
    private string $baseUrl = 'https://api.jikan.moe/v4';
    private int $cacheTtl = 300;

    public function searchAnime(string $query, int $limit = 10)
    {
        return $this->search('anime', $query, $limit);
    }

    public function searchManga(string $query, int $limit = 10)
    {
        return $this->search('manga', $query, $limit);
    }

    public function searchByType(string $type, string $query, int $limit = 10)
    {
        $malType = match ($type) {
            'anime' => null,
            'manga' => 'manga',
            'manhwa' => 'manhwa',
            'manhua' => 'manhua',
            'novel' => 'novel',
            default => 'manga',
        };

        if ($type === 'anime') {
            return $this->searchAnime($query, $limit);
        }

        return $this->search('manga', $query, $limit, ['type' => $malType]);
    }


    private function search(string $endpoint, string $query, int $limit, array $extra = [])
    {
        $cacheKey = "jikan_{$endpoint}_" . md5($query . serialize($extra));

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($endpoint, $query, $limit, $extra) {
            $response = Http::timeout(45)
                // ->retry(2, 1000)
                ->get("{$this->baseUrl}/{$endpoint}", array_merge([
                    'q' => $query,
                    'limit' => $limit,
                    'sfw' => 0,
                ], $extra));

            if ($response->failed()) {
                return [];
            }

            return collect($response->json('data', []))
                ->map(fn($item) => $this->normalize($item, $endpoint))
                ->values()
                ->toArray();
        });
    }

    public function getAnime(int $malId): ?array
    {
        return $this->getById('anime', $malId);
    }

    public function getManga(int $malId): ?array
    {
        return $this->getById('manga', $malId);
    }

    private function getById(string $endpoint, int $malId): ?array
    {
        $cacheKey = "jikan_{$endpoint}_{$malId}";

        return Cache::remember($cacheKey, 3600, function () use ($endpoint, $malId) {
            $response = Http::timeout(45)
                // ->retry(2, 1000)
                ->get("{$this->baseUrl}/{$endpoint}/{$malId}");

            if ($response->failed())
                return null;

            return $this->normalize($response->json('data', []), $endpoint);
        });
    }

    private function normalize(array $item, string $endpoint): array
    {
        $isAnime = $endpoint === 'anime';

        return [
            'mal_id' => $item['mal_id'],
            'title' => $item['title'] ?? $item['title_english'] ?? '',
            'title_japanese' => $item['title_japanese'] ?? null,
            'cover_url' => $item['images']['jpg']['image_url'] ?? null,
            'type' => $item['type'] ?? null, // TV, Movie, Manga, etc.
            'status' => $item['status'] ?? null,
            'total_episodes' => $isAnime ? ($item['episodes'] ?? null) : null,
            'total_chapters' => !$isAnime ? ($item['chapters'] ?? null) : null,
            'total_volumes' => !$isAnime ? ($item['volumes'] ?? null) : null,
            'score' => $item['score'] ?? null,
            'synopsis' => $item['synopsis'] ?? null,
            'mal_url' => $item['url'] ?? null,
        ];
    }
}
