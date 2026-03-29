<?php

namespace App\Livewire;

use App\Services\JikanService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class SearchPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $query = '';

    #[Url]
    public string $mode = 'local'; // 'local' or 'mal'

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function updatedMode()
    {
        $this->resetPage();
    }

    public function render()
    {
        $results = [];

        if (strlen($this->query) >= 2) {
            if ($this->mode === 'local') {
                $results = Auth::user()->mediaEntries()
                    ->where('title', 'like', '%' . $this->query . '%')
                    ->orWhere('original_title', 'like', '%' . $this->query . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(12);
            } else {
                // MAL Search via API
                $jikan = app(JikanService::class);
                // Note: Jikan API search doesn't support standard Laravel pagination easily, 
                // but we can fetch a set and display it. For now, simple results.
                $results = $jikan->searchByType('anime', $this->query, 24); 
            }
        }

        return view('livewire.search-page', [
            'results' => $results
        ]);
    }
}
