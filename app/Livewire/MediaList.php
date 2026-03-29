<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class MediaList extends Component
{
    use WithPagination;

    #[Url]
    public $status = null;

    #[Url]
    public $type = null;

    #[On('entry-saved')]
    public function refreshList()
    {
        // Livewire will automatically trigger a re-render when an event is received
        // No explicit logic needed if we're just refreshing the query
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $entries = Auth::user()->mediaEntries()
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->type,   fn($q) => $q->where('type', $this->type))
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $totalCount = Auth::user()->mediaEntries()->count();

        return view('livewire.media-list', [
            'entries'    => $entries,
            'totalCount' => $totalCount,
        ]);
    }
}
