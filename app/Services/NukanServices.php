<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NukanServices
{
    private string $baseUrl;

    private int $searchTtl = 300;

    private int $detailTtl = 3600;

    public function __construct()
    {
        $this->baseUrl = config('services.nukan.api_url');
    }

    public function search(string $query, int $page = 1): array
    {
        $cacheKey = 'nukan_search_'.md5($query.'|'.$page);

        return Cache::remember($cacheKey, $this->searchTtl, function () use ($query, $page) {
            try {
                $response = Http::timeout(8)
                    ->retry(2, 500, throw: false)
                    ->get("{$this->baseUrl}/search", [
                        'query' => $query,
                        'page' => $page,
                    ]);

                if ($response->failed()) {
                    return $this->emptySearch($query, $page);
                }

                $data = $response->json();

                return [
                    'query' => $data['query'] ?? $query,
                    'results' => $data['results'] ?? [],
                    'pagination' => $data['pagination'] ?? ['page' => $page, 'has_next' => false],
                ];
            } catch (ConnectionException $e) {
                return $this->emptySearch($query, $page);
            } catch (\Exception $e) {
                return $this->emptySearch($query, $page);
            }
        });
    }

    public function getSerie(string $slug): ?array
    {
        $cacheKey = 'nukan_series_'.$slug;

        return Cache::remember($cacheKey, $this->detailTtl, function () use ($slug) {
            try {
                $response = Http::timeout(8)
                    ->retry(2, 500, throw: false)
                    ->get("{$this->baseUrl}/series/".urlencode($slug));

                if ($response->failed()) {
                    return null;
                }

                $data = $response->json();

                return is_array($data) ? $data : null;
            } catch (ConnectionException $e) {
                return null;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    private function emptySearch(string $query, int $page): array
    {
        return [
            'query' => $query,
            'results' => [],
            'pagination' => ['page' => $page, 'has_next' => false],
        ];
    }
}
