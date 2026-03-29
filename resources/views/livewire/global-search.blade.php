<div class="relative w-full group" x-data="{ open: false }" @click.away="open = false">
    <div class="relative">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
        <input
            wire:model.live.debounce.300ms="query"
            @focus="open = true"
            class="w-full bg-surface-container-low border-none rounded-full py-3 pl-12 pr-10 text-sm focus:ring-2 focus:ring-primary/50 transition-all outline-none placeholder:text-on-surface-variant text-on-surface"
            placeholder="{{ __('Search within your archive...') }}" 
            type="text" 
            autocomplete="off" />
        
        {{-- Loading indicator inside input --}}
        <div wire:loading wire:target="query" class="absolute right-4 top-1/2 -translate-y-1/2">
            <div class="w-4 h-4 border-2 border-primary/20 border-t-primary rounded-full animate-spin"></div>
        </div>

        {{-- Clear button if query exists --}}
        @if($query)
            <button wire:click="clear" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors" wire:loading.remove wire:target="query">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        @endif
    </div>

    {{-- Results Dropdown --}}
    @if($query && strlen($query) >= 2)
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute top-full left-0 w-full mt-2 bg-surface-container-high rounded-2xl shadow-2xl border border-outline-variant/10 overflow-hidden z-50 backdrop-blur-xl">
            
            <div class="max-h-[400px] overflow-y-auto no-scrollbar">
                @forelse($results as $item)
                    <a href="{{ route('my-list.show', $item['id']) }}" 
                       class="flex items-center gap-4 p-4 hover:bg-surface-container-highest transition-colors group border-b border-outline-variant/5 last:border-none">
                        <div class="w-10 h-14 rounded-lg overflow-hidden bg-surface-container-low shrink-0 shadow-lg">
                            @if($item['cover'])
                                <img src="{{ $item['cover'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-outline text-lg">image</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-on-surface truncate group-hover:text-primary transition-colors">{{ $item['title'] }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] uppercase font-black tracking-widest text-on-surface-variant bg-surface-container-low px-1.5 py-0.5 rounded">
                                    {{ $item['type'] }}
                                </span>
                                @if($item['score'])
                                    <span class="flex items-center gap-0.5 text-[10px] font-bold text-secondary">
                                        <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                        {{ $item['score'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors group-hover:translate-x-1 duration-300">chevron_right</span>
                    </a>
                @empty
                    <div class="p-8 text-center">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant/20">search_off</span>
                        <p class="text-xs text-on-surface-variant font-medium mt-2">{{ __('No results in your collection') }}</p>
                        <a href="{{ route('search', ['q' => $query]) }}" class="inline-block mt-4 text-xs font-bold text-primary hover:underline">
                            {{ __('Search on MAL instead') }} →
                        </a>
                    </div>
                @endforelse
            </div>

            @if(count($results) > 0)
                <a href="{{ route('search', ['q' => $query]) }}" class="block px-4 py-3 bg-surface-container-highest text-center text-xs font-bold text-primary hover:bg-primary/10 transition-colors uppercase tracking-widest">
                    {{ __('See all results') }}
                </a>
            @endif
        </div>
    @endif
</div>
