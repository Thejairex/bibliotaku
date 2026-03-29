<?php

namespace App\Livewire;

use App\Enums\MediaStatus;
use App\Services\JikanService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class MediaSearchMal extends Component
{
    // Search panel state
    public string $query       = '';
    public string $type        = 'anime';
    public array  $results     = [];
    public bool   $loading     = false;
    public bool   $searched    = false;

    // Selected item state
    public ?array $selected    = null;

    // User-input form fields
    public string $status      = 'plan_to_watch';
    public ?int   $rating      = null;
    public ?int   $currentProgress = null;
    public string $notes       = '';

    // Modal visibility
    public bool   $open        = false;

    // Success toast state (shown inline after saving)
    public bool   $savedToast  = false;
    public string $savedTitle  = '';

    // ----------------------------------------------------------------
    // Lifecycle
    // ----------------------------------------------------------------

    #[On('open-mal-search')]
    public function openModal(?string $query = null): void
    {
        $this->open = true;
        $this->reset(['query', 'results', 'searched', 'selected', 'rating', 'notes', 'currentProgress', 'savedToast', 'savedTitle']);
        $this->status = 'plan_to_watch';
        $this->type   = 'anime';

        if ($query) {
            $this->query = $query;
            $this->performSearch();
        }
    }

    public function closeModal(): void
    {
        $this->open = false;
        $this->reset(['query', 'results', 'searched', 'selected', 'rating', 'notes', 'currentProgress', 'savedToast', 'savedTitle']);
    }

    public function dismissToast(): void
    {
        $this->savedToast = false;
        $this->savedTitle = '';
    }

    // ----------------------------------------------------------------
    // Search reactivity
    // ----------------------------------------------------------------

    public function updatedQuery(): void
    {
        if (strlen($this->query) < 3) {
            $this->results  = [];
            $this->searched = false;
            return;
        }
        $this->performSearch();
    }

    public function updatedType(): void
    {
        $this->selected = null;
        if (strlen($this->query) >= 3) {
            $this->performSearch();
        }
    }

    public function performSearch(): void
    {
        $this->loading  = true;
        $this->searched = false;

        /** @var JikanService $jikan */
        $jikan = app(JikanService::class);
        $this->results  = $jikan->searchByType($this->type, $this->query);

        $this->loading  = false;
        $this->searched = true;
    }

    // ----------------------------------------------------------------
    // Select a result
    // ----------------------------------------------------------------

    public function select(int $index): void
    {
        $this->selected = $this->results[$index] ?? null;

        if ($this->selected) {
            // Pre-fill status based on type
            $this->status          = $this->type === 'anime' ? 'watching' : 'reading';
            $this->currentProgress = 0;
        }
    }

    public function clearSelection(): void
    {
        $this->selected = null;
        $this->reset(['rating', 'notes', 'currentProgress']);
        $this->status = 'plan_to_watch';
    }

    // ----------------------------------------------------------------
    // Save entry
    // ----------------------------------------------------------------

    public function save(): void
    {
        $this->validate([
            'status'          => 'required|in:watching,rewatching,reading,completed,on_hold,dropped,plan_to_watch',
            'rating'          => 'nullable|integer|min:1|max:5',
            'currentProgress' => 'nullable|integer|min:0',
            'notes'           => 'nullable|string|max:2000',
        ]);

        if (!$this->selected) {
            return;
        }

        // Duplicate protection: Check if this MAL ID is already in the user's archive
        if ($this->selected['mal_id'] && Auth::user()->mediaEntries()->where('mal_id', $this->selected['mal_id'])->exists()) {
            $this->addError('selected', __('This title is already in your archive!'));
            return;
        }

        $isAnime = $this->type === 'anime';

        Auth::user()->mediaEntries()->create([
            'title'           => $this->selected['title'] ?? '',
            'original_title'  => $this->selected['title_japanese'] ?? null,
            'type'            => $this->type,
            'cover_url'       => $this->selected['cover_url'] ?? null,
            'mal_id'          => $this->selected['mal_id'] ?? null,
            'status'          => $this->status,
            'current_episode' => $isAnime ? ($this->currentProgress ?? 0) : null,
            'total_episodes'  => $isAnime ? ($this->selected['total_episodes'] ?? null) : null,
            'current_chapter' => !$isAnime ? ($this->currentProgress ?? 0) : null,
            'total_chapters'  => !$isAnime ? ($this->selected['total_chapters'] ?? null) : null,
            'total_volumes'   => !$isAnime ? ($this->selected['total_volumes'] ?? null) : null,
            'rating'          => $this->rating,
            'notes'           => $this->notes ?: null,
        ]);

        $savedTitle = $this->selected['title'] ?? __('Entry');

        // Stay open — only reset the selection + form, keep the search results
        $this->reset(['selected', 'rating', 'notes', 'currentProgress']);
        $this->status = 'plan_to_watch';

        // Show inline toast
        $this->savedTitle = $savedTitle;
        $this->savedToast = true;

        // Notify the page that the list changed
        $this->dispatch('entry-saved');
    }

    // ----------------------------------------------------------------
    // Render
    // ----------------------------------------------------------------

    public function render()
    {
        return view('livewire.media-search-mal');
    }
}