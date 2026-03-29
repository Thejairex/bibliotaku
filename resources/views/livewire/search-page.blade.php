<div class="space-y-8 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-outline-variant/10">
        <div>
            <span class="text-primary font-bold text-sm tracking-[0.2em] uppercase mb-2 block">{{ __('Global Explorer') }}</span>
            <h2 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tight">
                {{ __('Search') }}
            </h2>
        </div>

        {{-- Mode Switch --}}
        <div class="flex p-1 bg-surface-container rounded-full w-fit">
            <button wire:click="$set('mode', 'local')"
                class="px-6 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2
                    {{ $mode === 'local' ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                {{ __('Your Archive') }}
            </button>
            <button wire:click="$set('mode', 'mal')"
                class="px-6 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2
                    {{ $mode === 'mal' ? 'bg-secondary text-on-secondary shadow-lg shadow-secondary/20' : 'text-on-surface-variant hover:text-on-surface' }}">
                <span class="material-symbols-outlined text-[18px]">public</span>
                {{ __('MyAnimeList') }}
            </button>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="max-w-3xl">
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-{{ $mode === 'local' ? 'primary' : 'secondary' }} text-2xl transition-colors">search</span>
            
            <input
                wire:model.live.debounce.500ms="query"
                class="w-full bg-surface-container-high border-none rounded-2xl py-5 pl-16 pr-16 text-lg focus:ring-4 focus:ring-{{ $mode === 'local' ? 'primary' : 'secondary' }}/20 transition-all font-body text-on-surface placeholder:text-on-surface-variant outline-none shadow-xl"
                placeholder="{{ $mode === 'local' ? __('Search titles in your collection...') : __('Discover new anime & manga on MAL...') }}"
                type="text"
                autofocus />

            {{-- Loading Spinner --}}
            <div wire:loading wire:target="query, mode" class="absolute right-6 top-1/2 -translate-y-1/2">
                <div class="w-6 h-6 border-2 border-{{ $mode === 'local' ? 'primary' : 'secondary' }}/30 border-t-{{ $mode === 'local' ? 'primary' : 'secondary' }} rounded-full animate-spin"></div>
            </div>

            {{-- Clear Button --}}
            @if($query)
                <button wire:click="$set('query', '')" class="absolute right-6 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors" wire:loading.remove wire:target="query">
                    <span class="material-symbols-outlined">close</span>
                </button>
            @endif
        </div>
        @if(strlen($query) > 0 && strlen($query) < 2)
            <p class="text-xs text-primary font-bold uppercase tracking-widest mt-3 px-6 animate-pulse">
                {{ __('Keep typing...') }}
            </p>
        @endif
    </div>

    {{-- Results Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6" wire:loading.class="opacity-50">
        @if(strlen($query) >= 2)
            @forelse($results as $item)
                @if($mode === 'local')
                    {{-- Local Result Card --}}
                    <div class="group relative bg-surface-container rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-outline-variant/10">
                        <a href="{{ route('my-list.show', $item->id) }}" class="block aspect-[2/3] overflow-hidden relative">
                            @if($item->cover_url)
                                <img src="{{ $item->cover_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            @else
                                <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-outline-variant">image</span>
                                </div>
                            @endif
                            {{-- Quick Status Badge --}}
                            <div class="absolute top-3 left-3">
                                <span class="bg-black/60 backdrop-blur-md text-[10px] font-black uppercase tracking-widest text-white px-2 py-1 rounded-lg">
                                    {{ $item->status->label() }}
                                </span>
                            </div>
                        </a>
                        <div class="p-4">
                            <h4 class="font-headline font-bold text-sm text-on-surface truncate group-hover:text-primary transition-colors mb-1">
                                {{ $item->title }}
                            </h4>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">{{ $item->type->label() }}</span>
                                @if($item->rating)
                                    <span class="flex items-center gap-1 text-xs font-bold text-secondary">
                                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                        {{ $item->rating }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- MAL Result Card --}}
                    <div class="group relative bg-surface-container rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-outline-variant/10">
                        <div class="aspect-[2/3] overflow-hidden relative">
                            @if($item['cover_url'])
                                <img src="{{ $item['cover_url'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            @else
                                <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-outline-variant">image</span>
                                </div>
                            @endif
                            
                            {{-- Add to Archive Overlay --}}
                            <div class="absolute inset-x-4 bottom-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <button type="button" 
                                    onclick="window.Livewire.dispatch('open-mal-search'); setTimeout(() => { window.Livewire.dispatch('open-mal-search', { query: '{{ addslashes($item['title']) }}' }) }, 100)"
                                    class="w-full py-3 bg-secondary text-on-secondary rounded-xl font-bold text-xs uppercase tracking-widest shadow-xl flex items-center justify-center gap-2 hover:bg-secondary-dim transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h4 class="font-headline font-bold text-sm text-on-surface truncate mb-1">
                                {{ $item['title'] }}
                            </h4>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">{{ $item['type'] }}</span>
                                @if($item['score'])
                                    <span class="flex items-center gap-1 text-xs font-bold text-secondary">
                                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                        {{ $item['score'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full py-24 text-center">
                    <span class="material-symbols-outlined text-6xl text-on-surface-variant/20 mb-4">search_off</span>
                    <h4 class="text-on-surface font-bold text-xl">{{ __('No results found') }}</h4>
                    <p class="text-on-surface-variant text-sm mt-2 max-w-md mx-auto">
                        {{ $mode === 'local' 
                            ? __('We couldn\'t find anything in your archive matching those terms.') 
                            : __('No results from MyAnimeList. Try a different search term.') }}
                    </p>
                    @if($mode === 'local')
                        <button wire:click="$set('mode', 'mal')" class="mt-8 px-8 py-3 bg-secondary/10 text-secondary font-bold rounded-full hover:bg-secondary/20 transition-all">
                            {{ __('Search on MAL instead') }} →
                        </button>
                    @endif
                </div>
            @endforelse
        @else
            <div class="col-span-full py-32 text-center flex flex-col items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-{{ $mode === 'local' ? 'primary' : 'secondary' }}/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-5xl text-{{ $mode === 'local' ? 'primary' : 'secondary' }}/40">
                        {{ $mode === 'local' ? 'travel_explore' : 'explore' }}
                    </span>
                </div>
                <div>
                    <h3 class="font-headline font-black text-2xl text-on-surface">
                        {{ $mode === 'local' ? __('Your Archive Explorer') : __('Global Discovery') }}
                    </h3>
                    <p class="text-on-surface-variant mt-2 max-w-md">
                        {{ $mode === 'local' 
                            ? __('Quickly find and navigate through the entries already in your curated collection.') 
                            : __('Search the entire massive MyAnimeList database to add new titles to your archive.') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- Pagination for Local Mode --}}
    @if($mode === 'local' && $results instanceof \Illuminate\Pagination\LengthAwarePaginator && $results->hasPages())
        <div class="mt-12">
            {{ $results->links() }}
        </div>
    @endif
</div>
