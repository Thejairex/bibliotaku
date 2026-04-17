<div>
    {{-- Page Header & Status Filter --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
        <div class="flex items-center gap-6">
            <div>
                <span
                    class="text-primary font-bold text-sm tracking-[0.2em] uppercase mb-2 block">{{ __('Curated Collection') }}</span>
                <h2 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tight">
                    {{ __('My List') }}
                    <span class="text-on-surface-variant text-2xl font-medium ml-3">({{ $totalCount }})</span>
                </h2>
            </div>
            {{-- Add mode split-button (Desktop) --}}
            <div class="hidden md:flex items-center gap-2">
                <button id="openAddModal"
                    class="flex items-center gap-2 bg-surface-container-high hover:bg-surface-variant text-on-surface font-label font-bold px-5 py-3 rounded-l-full shadow transition-all active:scale-95 whitespace-nowrap border-r border-outline-variant/20">
                    <span class="material-symbols-outlined text-[20px]">edit_note</span>
                    {{ __('Manual') }}
                </button>
                <button id="openMalModal"
                    class="flex items-center gap-2 gradient-cta text-on-primary font-label font-bold px-5 py-3 rounded-r-full shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[20px]">travel_explore</span>
                    {{ __('Search MAL') }}
                </button>
            </div>
        </div>

        {{-- Status Filter Pills (Livewire controlled) --}}
        <div class="flex gap-2 p-1.5 bg-surface-container-low rounded-full overflow-x-auto hide-scrollbar">
            @php
                $statuses = [
                    null => __('All'),
                    'watching' => __('Watching'),
                    'completed' => __('Completed'),
                    'on_hold' => __('On Hold'),
                    'dropped' => __('Dropped'),
                ];
            @endphp
            @foreach ($statuses as $value => $label)
                <button wire:click="$set('status', {{ $value ? "'$value'" : 'null' }})"
                    class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all
                            {{ $status === $value ? 'bg-primary-dim text-on-primary shadow-lg shadow-primary-dim/20' : 'text-on-surface-variant hover:text-on-surface' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Type Selector (Livewire controlled) --}}
    @php
        $types = [
            null => ['label' => __('All Types'), 'icon' => 'auto_awesome'],
            'anime' => ['label' => 'Anime', 'icon' => 'play_circle'],
            'manga' => ['label' => 'Manga', 'icon' => 'menu_book'],
            'manhwa' => ['label' => 'Manhwa', 'icon' => 'book_5'],
            'manhua' => ['label' => 'Manhua', 'icon' => 'book_5'],
            'novel' => ['label' => __('Novel'), 'icon' => 'article'],
        ];
    @endphp
    <div class="flex gap-3 mb-8 overflow-x-auto hide-scrollbar pb-2">
        @foreach ($types as $value => $meta)
            <button wire:click="$set('type', {{ $value ? "'$value'" : 'null' }})"
                class="flex items-center gap-2 px-5 py-2.5 rounded-full font-medium text-sm whitespace-nowrap transition-all
                        {{ $type === $value ? 'bg-surface-container-highest text-primary font-bold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                <span class="material-symbols-outlined text-[20px]">{{ $meta['icon'] }}</span>
                {{ $meta['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Búsqueda local --}}
    <div class="mb-6">
        <div class="relative group max-w-xl">
            <span
                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
            <input wire:model.live.debounce.400ms="search" type="text"
                placeholder="{{ __('Search in your archive...') }}"
                class="w-full bg-surface-container-highest border-none rounded-full py-3.5 pl-12 pr-12 text-sm focus:ring-2 focus:ring-primary/50 transition-all text-on-surface placeholder:text-on-surface-variant outline-none" />
            @if ($search !== '')
                <button wire:click="$set('search', '')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            @endif
        </div>
    </div>

    {{-- Editorial Table --}}
    <div class="bg-surface-container-low rounded-xl overflow-hidden shadow-2xl relative">
        {{-- Loading Overlay --}}
        <div wire:loading.flex
            class="absolute inset-0 bg-surface-container-low/50 backdrop-blur-[1px] z-10 items-center justify-center">
            <div class="w-8 h-8 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
        </div>

        {{-- Table Header --}}
        <div
            class="grid grid-cols-12 px-8 py-5 border-b border-outline-variant/10 text-xs font-bold uppercase tracking-widest text-on-surface-variant">
            <div class="col-span-6 md:col-span-5">{{ __('Title') }}</div>
            <div class="hidden md:block col-span-2 text-center">{{ __('Score') }}</div>
            <div class="col-span-3 md:col-span-2 text-center">{{ __('Progress') }}</div>
            <div class="hidden md:block col-span-1 text-center">{{ __('Type') }}</div>
            <div class="col-span-3 md:col-span-2 text-right">{{ __('Actions') }}</div>
        </div>

        {{-- Table Rows --}}
        <div class="divide-y divide-outline-variant/10" wire:key="media-list-results">
            @forelse ($entries as $entry)
                @php
                    $isAnime = $entry->type->usesEpisodes();

                    // Status dot color
                    $dotColor = match ($entry->status->value) {
                        'watching', 'rewatching', 'reading' => 'bg-primary animate-pulse',
                        'completed' => 'bg-secondary',
                        'on_hold' => 'bg-outline',
                        'dropped' => 'bg-error',
                        default => 'bg-outline',
                    };

                    // Progress bar color
                    $barColor = match ($entry->status->value) {
                        'watching', 'rewatching', 'reading' => 'bg-primary',
                        'completed' => 'bg-secondary',
                        'dropped' => 'bg-error',
                        default => 'bg-outline',
                    };

                    // Text accent color for progress numbers
                    $textAccent = match ($entry->status->value) {
                        'completed' => 'text-secondary',
                        'dropped' => 'text-error',
                        default => 'text-primary',
                    };

                    // Progress numbers
                    if ($isAnime) {
                        $current = $entry->current_episode ?? 0;
                        $total = $entry->total_episodes;
                        $unit = 'Ep.';
                    } else {
                        $current = $entry->current_chapter ?? 0;
                        $total = $entry->total_chapters;
                        $unit = 'Ch.';
                    }
                    $pct = ($total > 0) ? min(100, round(($current / $total) * 100)) : 0;
                @endphp

                <div class="grid grid-cols-12 items-center px-8 py-6 hover:bg-surface-container-high transition-colors group"
                    wire:key="entry-{{ $entry->id }}">

                    {{-- Title & Status --}}
                    <div class="col-span-6 md:col-span-5 flex items-center gap-5">
                        <div
                            class="w-14 h-20 rounded-lg overflow-hidden flex-shrink-0 shadow-lg group-hover:scale-105 transition-transform duration-300 bg-surface-container-highest">
                            @if ($entry->cover_url)
                                <img alt="{{ $entry->title }}" class="w-full h-full object-cover"
                                    src="{{ $entry->cover_url }}" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-on-surface-variant/40 text-3xl">image</span>
                                </div>
                            @endif
                        </div>
                        <div class="overflow-hidden min-w-0">
                            <h3 class="font-headline font-bold text-lg truncate group-hover:text-primary transition-colors">
                                {{ $entry->title }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $dotColor }}"></span>
                                <span
                                    class="text-xs text-on-surface-variant font-medium">{{ $entry->status->label() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Score --}}
                    <div class="hidden md:flex col-span-2 justify-center">
                        @if ($entry->rating)
                            <div class="flex items-center gap-1.5 px-3 py-1 bg-surface-variant rounded-full">
                                <span class="material-symbols-outlined text-primary text-[18px]"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="text-sm font-bold">{{ $entry->rating }}</span>
                            </div>
                        @else
                            <span class="text-xs text-on-surface-variant/40 font-medium">—</span>
                        @endif
                    </div>

                    {{-- Progress --}}
                    <div class="col-span-3 md:col-span-2 text-center">
                        <div class="text-sm font-medium">
                            {{ $unit }} <span class="{{ $textAccent }} font-bold">{{ $current }}</span>
                            @if ($total)/ {{ $total }}@endif
                        </div>
                        <div class="w-full h-1 bg-surface-variant rounded-full mt-2 overflow-hidden max-w-[80px] mx-auto">
                            <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>

                    {{-- Type Badge --}}
                    <div class="hidden md:block col-span-1 text-center">
                        <span
                            class="text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-tighter
                                {{ $isAnime ? 'bg-secondary/10 text-secondary border border-secondary/20' : 'bg-tertiary/10 text-tertiary border border-tertiary/20' }}">
                            {{ $entry->type->label() }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="col-span-3 md:col-span-2 flex justify-end gap-2">
                        <a href="{{ route('my-list.show', $entry) }}"
                            class="p-2 rounded-full bg-surface-container-highest hover:bg-primary/20 hover:text-primary transition-all duration-300"
                            title="{{ __('View Details') }}" wire:navigate>
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </a>
                        <button
                            onclick="window.dispatchEvent(new CustomEvent('open-edit-modal', { detail: {{ $entry->toJson() }} }))"
                            class="p-2 rounded-full bg-surface-container-highest hover:bg-surface-variant transition-all"
                            title="{{ __('Edit') }}">
                            <span class="material-symbols-outlined text-[20px]">edit_note</span>
                        </button>
                        <form action="{{ route('my-list.destroy', $entry) }}" method="POST" class="inline"
                            onsubmit="return confirm('{{ __('Are you sure you want to remove this entry?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 rounded-full bg-surface-container-highest hover:bg-error/20 hover:text-error transition-all duration-300"
                                title="{{ __('Delete') }}">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center flex flex-col items-center gap-4">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant/30">library_add</span>
                    <p class="text-on-surface-variant font-label text-lg">{{ __('Your archive is empty.') }}</p>
                    <p class="text-on-surface-variant/60 text-sm">
                        @if ($status || $type)
                            {{ __('No entries match the current filters.') }}
                            <button wire:click="reset(['status', 'type'])"
                                class="text-primary font-bold ml-1 hover:underline">{{ __('Clear filters') }}</button>
                        @else
                            {{ __('Start adding anime and manga to your collection!') }}
                        @endif
                    </p>
                    @if (!$status && !$type)
                        <button id="openAddModalEmpty"
                            class="gradient-cta mt-4 px-8 py-3 rounded-full font-label font-bold text-on-primary text-sm hover:scale-105 transition-transform active:scale-95">
                            {{ __('Add First Entry') }}
                        </button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    @if ($entries->hasPages())
        <div class="mt-8">
            {{ $entries->links() }}
        </div>
    @endif
</div>