<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MediaList extends Component
{
    use WithPagination;

    #[Url]
    public $status = null;

    #[Url]
    public $type = null;

    #[Url(as: 'q')]
    public string $search = '';

    #[On('entry-saved')]
    public function refreshList(): void
    {
        // Livewire re-renderiza automáticamente
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $entries = Auth::user()->mediaEntries()
            ->search($this->search)
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('livewire.media-list', [
            'entries' => $entries,
            'totalCount' => $entries->total(), // ya viene del paginator, 0 queries extra
        ]);
    }
}
