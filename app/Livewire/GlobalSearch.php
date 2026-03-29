<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GlobalSearch extends Component
{
    public string $query = '';
    public array $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $this->results = Auth::user()->mediaEntries()
            ->where('title', 'like', '%' . $this->query . '%')
            ->orWhere('original_title', 'like', '%' . $this->query . '%')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($entry) {
                return [
                    'id'    => $entry->id,
                    'title' => $entry->title,
                    'type'  => $entry->type->label(),
                    'cover' => $entry->cover_url,
                    'score' => $entry->rating,
                ];
            })
            ->toArray();
    }

    public function clear()
    {
        $this->reset(['query', 'results']);
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
