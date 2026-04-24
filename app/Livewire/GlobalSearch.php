<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public array $results = [];

    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->results = [];

            return;
        }

        $this->results = Auth::user()->mediaEntries()
            ->search($this->query)          // scope agrupado, sin fuga de orWhere
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get()
            ->map(fn ($entry) => [
                'id' => $entry->id,
                'title' => $entry->title,
                'type' => $entry->type->label(),
                'cover' => $entry->cover_url,
                'score' => $entry->rating,
            ])
            ->toArray();
    }

    public function clear(): void
    {
        $this->reset(['query', 'results']);
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
