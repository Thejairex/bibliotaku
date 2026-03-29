@php
    $isAnime   = $type === 'anime';
    $totalProg = $selected
        ? ($isAnime ? ($selected['total_episodes'] ?? null) : ($selected['total_chapters'] ?? null))
        : null;
@endphp

{{-- Root tag always required by Livewire --}}
<div>
@if ($open)
<div
    class="fixed inset-0 z-[200] flex items-center justify-center p-4 md:p-8"
    style="background: rgba(0,0,0,0.80); backdrop-filter: blur(6px);"
    wire:key="mal-search-modal">

    {{-- Modal Container: split left/right --}}
    <div class="bg-surface-container-low w-full max-w-5xl h-[90vh] max-h-[870px] rounded-xl overflow-hidden flex flex-col md:flex-row shadow-2xl shadow-black/60 relative">

        {{-- Close Button --}}
        <button
            wire:click="closeModal"
            class="absolute top-4 right-4 z-[60] w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-highest text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition-all">
            <span class="material-symbols-outlined">close</span>
        </button>

        {{-- ============================================================ --}}
        {{-- LEFT PANEL: Search Results                                    --}}
        {{-- ============================================================ --}}
        <div class="w-full md:w-2/5 border-r border-outline-variant/10 flex flex-col h-full bg-surface-container-low">

            <div class="p-6 pb-4 space-y-4">
                <h2 class="font-headline text-2xl font-bold">{{ __('Find Media') }}</h2>

                {{-- Type Toggle --}}
                <div class="flex gap-1 p-1 bg-surface-container rounded-full">
                    @foreach (['anime' => 'Anime', 'manga' => 'Manga', 'manhwa' => 'Manhwa', 'novel' => __('Novel')] as $val => $lbl)
                        <button wire:click="$set('type', '{{ $val }}')"
                            class="flex-1 py-1.5 rounded-full text-xs font-bold transition-all
                                {{ $type === $val ? 'bg-primary text-on-primary shadow' : 'text-on-surface-variant hover:text-on-surface' }}">
                            {{ $lbl }}
                        </button>
                    @endforeach
                </div>

                {{-- Search Input --}}
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
                    
                    {{-- Input --}}
                    <input
                        wire:model.live.debounce.1000ms="query"
                        class="w-full bg-surface-container-highest border-none rounded-full py-3.5 pl-12 pr-12 text-sm focus:ring-2 focus:ring-primary/50 transition-all font-body text-on-surface placeholder:text-on-surface-variant outline-none"
                        placeholder="{{ __('Search anime, manga...') }}"
                        type="text"
                        autofocus />

                    {{-- Loading Spinner inside Search Bar --}}
                    <div wire:loading wire:target="query" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <div class="w-5 h-5 border-2 border-primary/30 border-t-primary rounded-full animate-spin"></div>
                    </div>
                </div>

                {{-- Character Limit HINT --}}
                @if(strlen($query) > 0 && strlen($query) < 3)
                    <p class="text-[10px] text-primary font-bold uppercase tracking-widest px-4 animate-pulse">
                        {{ __('Type at least 3 characters...') }}
                    </p>
                @endif
            </div>

            {{-- Results List --}}
            <div class="flex-1 overflow-y-auto px-4 pb-6 space-y-2 no-scrollbar">

                @if ($loading)
                    <div class="flex flex-col items-center justify-center py-16 gap-3 text-on-surface-variant">
                        <div class="w-8 h-8 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <p class="text-xs font-label uppercase tracking-widest">{{ __('Searching...') }}</p>
                    </div>

                @elseif (!$searched && !$query)
                    <div class="flex flex-col items-center justify-center py-16 gap-3 text-on-surface-variant/40">
                        <span class="material-symbols-outlined text-5xl">manage_search</span>
                        <p class="text-xs font-label uppercase tracking-widest text-center">{{ __('Type to search MAL') }}</p>
                    </div>

                @elseif ($searched && empty($results))
                    <div class="flex flex-col items-center justify-center py-16 gap-3 text-on-surface-variant/40">
                        <span class="material-symbols-outlined text-5xl">search_off</span>
                        <p class="text-xs font-label uppercase tracking-widest">{{ __('No results found') }}</p>
                    </div>

                @else
                    @foreach ($results as $i => $item)
                        @php $isActive = $selected && $selected['mal_id'] === $item['mal_id']; @endphp
                        <button
                            wire:click="select({{ $i }})"
                            class="w-full flex items-center gap-4 p-3 rounded-xl transition-all text-left group cursor-pointer
                                {{ $isActive
                                    ? 'bg-primary/10 border-l-4 border-primary'
                                    : 'hover:bg-surface-container-highest border-l-4 border-transparent' }}">

                            <div class="w-14 h-20 rounded-lg overflow-hidden flex-shrink-0 {{ $isActive ? '' : 'opacity-60 group-hover:opacity-100' }} transition-opacity">
                                @if ($item['cover_url'])
                                    <img class="w-full h-full object-cover" src="{{ $item['cover_url'] }}" alt="{{ $item['title'] }}" />
                                @else
                                    <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-surface-variant/40">image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="font-headline font-bold text-sm {{ $isActive ? 'text-primary' : 'text-on-surface' }} truncate">
                                    {{ $item['title'] }}
                                </h4>
                                <p class="text-xs text-on-surface-variant mt-1 uppercase tracking-wider">
                                    {{ $item['type'] ?? '—' }}
                                    @if ($item['total_episodes'])
                                        · {{ $item['total_episodes'] }} eps
                                    @elseif ($item['total_chapters'])
                                        · {{ $item['total_chapters'] }} ch
                                    @endif
                                    @if ($item['score'])
                                        · ★ {{ $item['score'] }}
                                    @endif
                                </p>
                            </div>

                            @if ($isActive)
                                <span class="material-symbols-outlined text-primary text-lg flex-shrink-0"
                                      style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            @endif
                        </button>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- RIGHT PANEL: Preview + Form                                   --}}
        {{-- ============================================================ --}}
        <div class="flex-1 h-full overflow-y-auto bg-surface-container relative no-scrollbar">

            {{-- ── Success Toast (inline, after saving) ── --}}
            @if ($savedToast)
                <div class="sticky top-0 z-20 px-6 pt-4">
                    <div class="flex items-center gap-3 px-5 py-4 bg-secondary/15 border border-secondary/25 rounded-2xl backdrop-blur-sm shadow-xl">
                        <span class="material-symbols-outlined text-secondary text-[22px] shrink-0" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-on-surface truncate">"{{ $savedTitle }}" {{ __('added!') }}</p>
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-widest mt-0.5">{{ __('Select the next title to add') }}</p>
                        </div>
                        <button wire:click="dismissToast" class="shrink-0 w-7 h-7 flex items-center justify-center rounded-full hover:bg-secondary/20 text-on-surface-variant hover:text-on-surface transition-all">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </button>
                    </div>
                </div>
            @endif

            @if ($selected)

                {{-- Blurry hero background --}}
                @if ($selected['cover_url'])
                    <div class="absolute top-0 left-0 w-full h-64 opacity-20 pointer-events-none overflow-hidden">
                        <img class="w-full h-full object-cover blur-3xl scale-110"
                            src="{{ $selected['cover_url'] }}" alt="" />
                    </div>
                @endif

                <div class="relative p-8 md:p-10">
                    <div class="flex flex-col lg:flex-row gap-8 items-start">

                        {{-- Cover --}}
                        <div class="w-40 lg:w-48 aspect-[2/3] rounded-xl shadow-2xl overflow-hidden flex-shrink-0 bg-surface-container-highest">
                            @if ($selected['cover_url'])
                                <img class="w-full h-full object-cover"
                                    src="{{ $selected['cover_url'] }}"
                                    alt="{{ $selected['title'] }}" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">image</span>
                                </div>
                            @endif
                        </div>

                        {{-- Info & Form --}}
                        <div class="flex-1 w-full">

                            {{-- Title & Meta --}}
                            <div class="mb-6">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <span class="bg-primary/20 text-primary text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-widest">
                                        {{ $selected['type'] ?? ucfirst($type) }}
                                    </span>
                                    @if ($selected['score'])
                                        <span class="flex items-center gap-1 text-on-surface-variant text-sm">
                                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size:16px;">star</span>
                                            {{ $selected['score'] }} MAL
                                        </span>
                                    @endif
                                    @if ($selected['total_episodes'])
                                        <span class="text-on-surface-variant text-xs uppercase tracking-wider">{{ $selected['total_episodes'] }} eps</span>
                                    @elseif ($selected['total_chapters'])
                                        <span class="text-on-surface-variant text-xs uppercase tracking-wider">
                                            {{ $selected['total_chapters'] }} ch
                                            @if ($selected['total_volumes']) · {{ $selected['total_volumes'] }} vol @endif
                                        </span>
                                    @endif
                                </div>

                                <h3 class="font-headline text-2xl md:text-3xl font-black tracking-tight leading-tight">
                                    {{ $selected['title'] }}
                                </h3>
                                @if ($selected['title_japanese'])
                                    <p class="text-on-surface-variant text-sm mt-1">{{ $selected['title_japanese'] }}</p>
                                @endif
                                @if ($selected['synopsis'])
                                    <p class="text-on-surface-variant mt-3 text-sm leading-relaxed line-clamp-3">
                                        {{ $selected['synopsis'] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Form --}}
                            <form wire:submit="save" class="space-y-5">

                                {{-- Status + Rating --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">
                                            {{ __('Status') }} <span class="text-error">*</span>
                                        </label>
                                        <select wire:model="status"
                                            class="w-full bg-surface-container-highest border-none rounded-full py-3 px-5 text-sm focus:ring-2 focus:ring-primary/50 text-on-surface appearance-none cursor-pointer outline-none">
                                            @if ($isAnime)
                                                <option value="watching">{{ __('Watching') }}</option>
                                                <option value="rewatching">{{ __('Rewatching') }}</option>
                                            @else
                                                <option value="reading">{{ __('Reading') }}</option>
                                            @endif
                                            <option value="completed">{{ __('Completed') }}</option>
                                            <option value="on_hold">{{ __('On Hold') }}</option>
                                            <option value="dropped">{{ __('Dropped') }}</option>
                                            <option value="plan_to_watch">{{ __('Plan to Watch') }}</option>
                                        </select>
                                        @error('status') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">
                                            {{ __('Score') }} (1-5)
                                        </label>
                                        <div class="flex items-center bg-surface-container-highest rounded-full px-5 py-3 gap-2">
                                            <span class="material-symbols-outlined text-primary text-lg"
                                                  style="font-variation-settings: 'FILL' 1;">star</span>
                                            <input wire:model="rating"
                                                class="w-full bg-surface-container border-none rounded-xl py-3 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all placeholder:text-on-surface-variant/40"
                                                max="5" min="1" placeholder="Valora del 1 al 5..." type="number" />
                                        </div>
                                        @error('rating') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Progress --}}
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">
                                        {{ $isAnime ? __('Episode Progress') : __('Chapter Progress') }}
                                    </label>
                                    <div class="flex items-center gap-4">
                                        <div class="flex-1 bg-surface-container-highest rounded-full px-5 py-3 flex items-center justify-between">
                                            <input wire:model="currentProgress"
                                                class="w-16 bg-transparent border-none p-0 focus:ring-0 text-sm font-bold text-on-surface text-center outline-none"
                                                min="0" type="number" />
                                            <span class="text-outline font-bold mx-2">/</span>
                                            <span class="text-sm font-bold text-on-surface-variant">
                                                {{ $totalProg ?? '?' }}
                                            </span>
                                        </div>
                                        <button type="button"
                                            wire:click="$set('currentProgress', {{ ($currentProgress ?? 0) + 1 }})"
                                            class="w-11 h-11 flex items-center justify-center rounded-full bg-primary/10 text-primary hover:bg-primary hover:text-on-primary transition-all">
                                            <span class="material-symbols-outlined">add</span>
                                        </button>
                                    </div>
                                    @error('currentProgress') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Notes --}}
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-on-surface-variant ml-1">
                                        {{ __('Notes') }} <span class="text-on-surface-variant font-normal">({{ __('optional') }})</span>
                                    </label>
                                    <textarea wire:model="notes" rows="2"
                                        placeholder="{{ __('Your thoughts, reminders...') }}"
                                        class="w-full bg-surface-container-highest border-none rounded-xl py-3 px-5 text-sm focus:ring-2 focus:ring-primary/50 text-on-surface outline-none resize-none placeholder:text-on-surface-variant"></textarea>
                                    @error('notes') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Actions --}}
                                <div class="pt-4 flex flex-col sm:flex-row gap-4">
                                    <button type="submit"
                                        class="flex-1 h-14 rounded-full gradient-cta text-on-primary font-headline font-bold text-sm uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                                        {{ __('Add to Archive') }}
                                    </button>
                                    <button type="button" wire:click="clearSelection"
                                        class="px-8 h-14 rounded-full bg-surface-container-highest text-on-surface font-bold text-sm uppercase tracking-widest hover:bg-surface-variant transition-colors">
                                        {{ __('Back') }}
                                    </button>
                                </div>
                                <p class="text-center text-[10px] text-outline mt-2 uppercase tracking-widest">
                                    {{ __('Modal stays open') }} · {{ __('Pick the next season from the list') }}
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                {{-- Empty right panel --}}
                <div class="h-full flex flex-col items-center justify-center gap-6 p-12 text-center">
                    <div class="w-24 h-24 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-5xl text-primary/60">auto_stories</span>
                    </div>
                    <div>
                        <h4 class="font-headline font-bold text-xl text-on-surface mb-2">{{ __('Select a title') }}</h4>
                        <p class="text-on-surface-variant text-sm leading-relaxed max-w-xs">
                            {{ __('Search for an anime or manga on the left, then click a result to preview and add it to your archive.') }}
                        </p>
                    </div>
                    @if ($searched && !empty($results))
                        <div class="flex items-center gap-2 text-primary text-sm {{ $savedToast ? '' : 'animate-pulse' }}">
                            <span class="material-symbols-outlined">arrow_back</span>
                            {{ $savedToast ? __('Pick the next title →') : __('Choose from results') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endif
</div>
