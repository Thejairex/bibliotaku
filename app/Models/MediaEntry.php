<?php

namespace App\Models;

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'title', 'original_title', 'type', 'cover_url', 'mal_id', 'status', 'current_episode', 'total_episodes', 'current_chapter', 'total_chapters', 'current_volume', 'total_volumes', 'rating', 'notes'])]
#[Hidden(['user_id', 'mal_id'])]
class MediaEntry extends Model
{
    /** @use HasFactory<\Database\Factories\MediaEntryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'mal_id' => 'integer',
            'type' => MediaType::class,
            'status' => MediaStatus::class,
            'current_episode' => 'integer',
            'total_episodes' => 'integer',
            'current_chapter' => 'integer',
            'total_chapters' => 'integer',
            'current_volume' => 'integer',
            'total_volumes' => 'integer',
            'rating' => 'integer',
        ];
    }

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -----------------------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------------------

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeWithRating(Builder $query, int $rating): Builder
    {
        return $query->where('rating', $rating);
    }

    /**
     * Búsqueda segura por título/título original, agrupada para evitar
     * que orWhere escape fuera del contexto del usuario.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('original_title', 'like', "%{$term}%");
        });
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    public function isAnime(): bool
    {
        return $this->type === MediaType::Anime;
    }

    public function progressLabel(): string
    {
        if ($this->isAnime()) {
            $current = $this->current_episode ?? 0;
            $total = $this->total_episodes ? "/{$this->total_episodes}" : '';
            return "Ep. {$current}{$total}";
        }

        $current = $this->current_chapter ?? 0;
        $total = $this->total_chapters ? "/{$this->total_chapters}" : '';
        return "Cap. {$current}{$total}";
    }

    public function malUrl(): ?string
    {
        if (!$this->mal_id) {
            return null;
        }

        $path = $this->isAnime() ? 'anime' : 'manga';
        return "https://myanimelist.net/{$path}/{$this->mal_id}";
    }
}