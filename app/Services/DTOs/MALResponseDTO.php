<?php

namespace App\Services\DTOs;

class MALResponseDTO
{
    public function __construct(
        public readonly int $mal_id,
        public readonly string $url,
        public readonly string $title,
        public readonly string $image_url,
        public readonly string $type,
        public readonly string $score,
        public readonly string $status,
        public readonly string $synopsis,
        public readonly string $background,
        public readonly string $premiered,
        public readonly string $broadcast,
        public readonly string $aired,
        public readonly string $rating,
        public readonly string $duration,
        public readonly string $source,
        public readonly string $rank,
        public readonly string $popularity,
        public readonly string $members,
        public readonly string $favorites,
        public readonly string $synonyms,
        public readonly string $related,
        public readonly string $opening_theme,
        public readonly string $ending_theme,
    ) {
    }
}