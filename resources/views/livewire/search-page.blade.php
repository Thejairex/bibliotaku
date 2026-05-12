<div class="space-y-12 animate-fade-in">
    <div class="relative overflow-hidden rounded-3xl bg-surface-container border border-outline-variant/5">
        <div class="relative z-10 px-8 py-10 md:px-12 md:py-14">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-[11px] font-black uppercase tracking-[0.2em] mb-6">
                    <span class="material-symbols-outlined text-[14px]">travel_explore</span>
                    {{ __('Global Explorer') }}
                </span>
                <h1 class="text-5xl md:text-6xl font-headline font-black tracking-tight mb-4">
                    {{ __('Find Your Next Obsession') }}
                </h1>
                <p class="text-on-surface-variant text-lg leading-relaxed mb-8">
                    {{ $mode === 'local' 
                        ? __('Search your curated archive. Instantly filter by title, genre, or status.') 
                        : __('Dive into the MyAnimeList database. Discover 20,000+ anime and manga titles.') }}
                </p>
                
                <div class="relative group">
                    <span @class([
                        'material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-2xl transition-all duration-300 z-10',
                        'text-primary' => $mode === 'local',
                        'text-secondary' => $mode === 'mal',
                        'opacity-50 group-focus-within:opacity-100' => true,
                    ])>search</span>
                    
                    <input
                        wire:model.live.debounce.500ms="query"
                        @class([
                            'w-full bg-background/80 backdrop-blur-xl border border-outline-variant/20 rounded-2xl py-5 pl-14 pr-14 text-lg transition-all outline-none',
                            'text-on-surface placeholder:text-on-surface-variant',
                            'focus:border-primary/50 focus:shadow-[0_0_30px_-12px_theme(colors.primary)]' => $mode === 'local',
                            'focus:border-secondary/50 focus:shadow-[0_0_30px_-12px_theme(colors.secondary)]' => $mode === 'mal',
                        ])
                        placeholder="{{ $mode === 'local' ? __('Search your archive...') : __('Discover on MyAnimeList...') }}"
                        type="text"
                        autofocus />

                    <div wire:loading wire:target="query, mode" class="absolute right-5 top-1/2 -translate-y-1/2 z-10">
                        <div @class([
                            'w-5 h-5 border-2 rounded-full animate-spin',
                            'border-primary/30 border-t-primary' => $mode === 'local',
                            'border-secondary/30 border-t-secondary' => $mode === 'mal',
                        ])></div>
                    </div>

                    @if($query)
                        <button wire:click="$set('query', '')" class="absolute right-5 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors z-10" wire:loading.remove wire:target="query">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    @endif
                </div>

                @if(strlen($query) > 0 && strlen($query) < 2)
                    <p class="text-xs text-primary font-bold uppercase tracking-widest mt-4 animate-pulse">
                        {{ __('Keep typing to search...') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="absolute right-0 top-0 w-1/3 h-full bg-gradient-to-l from-primary/5 to-transparent pointer-events-none"></div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h3 class="text-2xl font-headline font-bold text-on-surface">
                {{ __('Search Results') }}
                @if($query && strlen($query) >= 2)
                    <span class="text-on-surface-variant font-normal text-base ml-2">
                        "{{ $query }}"
                    </span>
                @endif
            </h3>
        </div>

        <div class="flex p-1 bg-surface-container rounded-full w-fit border border-outline-variant/5">
            <button wire:click="$set('mode', 'local')"
                @class([
                    'px-5 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2',
                    'bg-primary text-on-primary shadow-lg shadow-primary/20' => $mode === 'local',
                    'text-on-surface-variant hover:text-on-surface' => $mode !== 'local',
                ])>
                <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                {{ __('Your Archive') }}
            </button>
            <button wire:click="$set('mode', 'mal')"
                @class([
                    'px-5 py-2 rounded-full text-xs font-bold transition-all flex items-center gap-2',
                    'bg-secondary text-on-secondary shadow-lg shadow-secondary/20' => $mode === 'mal',
                    'text-on-surface-variant hover:text-on-surface' => $mode !== 'mal',
                ])>
                <span class="material-symbols-outlined text-[16px]">public</span>
                {{ __('MyAnimeList') }}
            </button>
        </div>
    </div>

    <div wire:loading.class="opacity-50">
        @if(strlen($query) >= 2)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
                @forelse($results as $item)
                    @if($mode === 'local')
                        <div class="group relative bg-surface-container rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-outline-variant/5">
                            <a href="{{ route('my-list.show', $item->id) }}" class="block aspect-[2/3] overflow-hidden relative">
                                @if($item->cover_url)
                                    <img src="{{ $item->cover_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" />
                                @else
                                    <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl text-outline-variant">image</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-3 left-3">
                                    <span class="bg-black/60 backdrop-blur-md text-[10px] font-black uppercase tracking-widest text-white px-2 py-1 rounded-lg">
                                        {{ $item->status->label() }}
                                    </span>
                                </div>
                            </a>
                            <div class="p-4">
                                <h4 class="font-headline font-bold text-sm text-on-surface truncate mb-2">
                                    {{ $item->title }}
                                </h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">{{ $item->type->label() }}</span>
                                    @if($item->rating)
                                        <span class="flex items-center gap-1 text-xs font-bold text-secondary">
                                            <span class="material-symbols-outlined text-[12px] fill">star</span>
                                            {{ $item->rating }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="group relative bg-surface-container rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-outline-variant/5">
                            <div class="aspect-[2/3] overflow-hidden relative">
                                @if($item['cover_url'])
                                    <img src="{{ $item['cover_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" />
                                @else
                                    <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl text-outline-variant">image</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute inset-x-4 bottom-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <button type="button" 
                                        onclick="window.Livewire.dispatch('open-mal-search'); setTimeout(() => { window.Livewire.dispatch('open-mal-search', { query: '{{ addslashes($item['title']) }}' }) }, 100)"
                                        class="w-full py-3 bg-secondary text-on-secondary rounded-xl font-bold text-xs uppercase tracking-widest shadow-xl flex items-center justify-center gap-2 hover:bg-secondary/80 transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                                        {{ __('Add to Archive') }}
                                    </button>
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-headline font-bold text-sm text-on-surface truncate mb-2">
                                    {{ $item['title'] }}
                                </h4>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">{{ $item['type'] }}</span>
                                    @if($item['score'])
                                        <span class="flex items-center gap-1 text-xs font-bold text-secondary">
                                            <span class="material-symbols-outlined text-[12px] fill">star</span>
                                            {{ $item['score'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-20 h-20 mx-auto rounded-full bg-surface-container flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">search_off</span>
                        </div>
                        <h4 class="text-xl font-bold text-on-surface mb-2">{{ __('No results found') }}</h4>
                        <p class="text-on-surface-variant max-w-md mx-auto">
                            {{ $mode === 'local' 
                                ? __('We couldn\'t find anything matching your terms. Try different keywords or browse your collection.') 
                                : __('No matches from MyAnimeList. Double-check the spelling or try a different search.') }}
                        </p>
                        @if($mode === 'local')
                            <button wire:click="$set('mode', 'mal')" class="mt-6 px-6 py-3 bg-secondary/10 text-secondary font-bold rounded-full hover:bg-secondary/20 transition-all inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">public</span>
                                {{ __('Search on MAL instead') }}
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($mode === 'local' && $results instanceof \Illuminate\Pagination\LengthAwarePaginator && $results->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $results->links() }}
                </div>
            @endif
        @else
            <div class="py-16 text-center">
                <div @class([
                    'w-24 h-24 mx-auto rounded-3xl flex items-center justify-center mb-8 transition-colors duration-500',
                    'bg-primary/10' => $mode === 'local',
                    'bg-secondary/10' => $mode === 'mal',
                ])>
                    <span @class([
                        'material-symbols-outlined text-5xl transition-colors duration-500',
                        'text-primary/50' => $mode === 'local',
                        'text-secondary/50' => $mode === 'mal',
                    ])>
                        {{ $mode === 'local' ? 'inventory_2' : 'explore' }}
                    </span>
                </div>
                <h3 class="text-2xl font-headline font-black text-on-surface mb-3">
                    {{ $mode === 'local' ? __('Your Archive Awaits') : __('Explore MyAnimeList') }}
                </h3>
                <p class="text-on-surface-variant max-w-lg mx-auto leading-relaxed">
                    {{ $mode === 'local' 
                        ? __('Type at least 2 characters to search through your curated collection. Filter by title, status, or type.') 
                        : __('Enter at least 2 characters to search the entire MyAnimeList database. Discover anime, manga, and more.') }}
                </p>
            </div>
        @endif
    </div>
</div>
