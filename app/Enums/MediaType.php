<?php

namespace App\Enums;

enum MediaType: string
{
    case Anime = 'anime';
    case Manga = 'manga';
    case Manhwa = 'manhwa';
    case Manhua = 'manhua';
    case Novel = 'novel';

    public function label(): string
    {
        return match ($this) {
            self::Anime => 'Anime',
            self::Manga => 'Manga',
            self::Manhwa => 'Manhwa',
            self::Manhua => 'Manhua',
            self::Novel => 'Novela',
        };
    }

    public function usesEpisodes(): bool
    {
        return $this === self::Anime;
    }
}
