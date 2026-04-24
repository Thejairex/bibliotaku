<?php

namespace App\Livewire;

use App\Services\JikanService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SearchPage extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $query = '';

    #[Url]
    public string $mode = 'local';

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function updatedMode(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $results = [];

        if (strlen($this->query) >= 2) {
            if ($this->mode === 'local') {
                $results = Auth::user()->mediaEntries()
                    ->search($this->query)          // usa el scope seguro
                    ->orderByDesc('updated_at')
                    ->paginate(12);
            } else {
                $jikan = app(JikanService::class);
                $results = $jikan->searchByType('anime', $this->query, 24);
            }
        }

        return view('livewire.search-page', [
            'results' => $results,
        ]);
    }
}
