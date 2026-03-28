@extends('layouts.app')

@section('title', config('app.name', 'The Archive') . ' — ' . __('Digital Curator Dashboard'))

@section('content')
    <div class="max-w-[1600px] mx-auto">
        {{-- Welcome Section --}}
        <section class="mb-10 mt-4">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="max-w-xl">
                    <span class="text-primary font-label text-sm font-bold tracking-widest uppercase mb-2 block">
                        {{ __('DASHBOARD OVERVIEW') }}
                    </span>
                    <h2 class="text-4xl md:text-5xl font-headline font-black tracking-tight leading-tight">
                        {{ __('Your Digital') }} <br />
                        <span class="text-primary">{{ __('Curator Studio.') }}</span>
                    </h2>
                </div>
                <div class="flex gap-4">
                    <div class="bg-surface-container-low p-6 rounded-xl min-w-[140px] text-center shadow-sm border border-outline-variant/10">
                        <p class="text-3xl font-headline font-black text-primary">
                            {{ $avgRating ? number_format($avgRating, 1) : '—' }}
                        </p>
                        <p class="text-[10px] font-label text-on-surface-variant uppercase tracking-widest mt-1">{{ __('Avg Rating') }} (1-5)</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Stats Grid (Bento Style) --}}
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group shadow-lg shadow-black/5">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">visibility</span>
                    <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded-full">{{ __('active') }}</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $stats['watching'] }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('Watching') }}</p>
            </div>
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group shadow-lg shadow-black/5">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-tertiary group-hover:scale-110 transition-transform">auto_stories</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $stats['reading'] }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('Reading') }}</p>
            </div>
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group shadow-lg shadow-black/5">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">check_circle</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $stats['completed'] }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('Completed') }}</p>
            </div>
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group shadow-lg shadow-black/5">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-outline group-hover:scale-110 transition-transform">pause_circle</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $stats['on_hold'] }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('On Hold') }}</p>
            </div>
        </section>

        {{-- Main Content Area --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">

            {{-- Latest Updates Column --}}
            <div class="xl:col-span-2">
                <div class="flex items-center justify-between mb-8 px-2">
                    <h3 class="text-2xl font-headline font-bold">{{ __('Latest Updates') }}</h3>
                    <a href="{{ route('my-list') }}" class="text-sm font-bold text-primary hover:underline">{{ __('View All') }}</a>
                </div>

                <div class="flex flex-col gap-6">
                    @forelse ($recentEntries as $entry)
                        @php
                            $isAnime = $entry->type->usesEpisodes();
                            $accentColor = $isAnime ? 'primary' : 'secondary';
                            $icon = $isAnime ? 'play_arrow' : 'menu_book';

                            if ($isAnime) {
                                $current = $entry->current_episode ?? 0;
                                $total = $entry->total_episodes ?? 0;
                            } else {
                                $current = $entry->current_chapter ?? 0;
                                $total = $entry->total_chapters ?? 0;
                            }
                            $progress = ($total > 0) ? min(100, round(($current / $total) * 100)) : 0;
                            $unit = $isAnime ? 'eps' : 'ch';
                        @endphp
                        <div class="group bg-surface-container rounded-xl overflow-hidden flex flex-col sm:flex-row items-center gap-6 p-4 hover:bg-surface-container-highest transition-all duration-300 border border-outline-variant/5 shadow-sm">
                            <div class="relative w-full sm:w-32 h-44 sm:h-32 shrink-0 overflow-hidden rounded-lg shadow-md">
                                @if ($entry->cover_url)
                                    <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        src="{{ $entry->cover_url }}" alt="{{ $entry->title }}" />
                                @else
                                    <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                        <span class="material-symbols-outlined text-4xl text-on-surface-variant">image</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-2 sm:hidden">
                                    <span class="text-[10px] font-bold text-white uppercase bg-primary/80 px-2 py-0.5 rounded">{{ $entry->type->label() }}</span>
                                </div>
                            </div>
                            <div class="flex-1 w-full">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-lg font-headline font-extrabold leading-tight truncate max-w-[200px]">{{ $entry->title }}</h4>
                                    <span class="hidden sm:block text-[10px] font-bold text-{{ $accentColor }} border border-{{ $accentColor }}/30 px-2 py-0.5 rounded-full uppercase">
                                        {{ $entry->type->label() }}
                                    </span>
                                </div>
                                <p class="text-xs text-on-surface-variant font-medium mb-4">
                                    {{ $entry->status->label() }} · {{ $entry->updated_at->diffForHumans() }}
                                </p>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">
                                        <span>{{ __('Progress') }}</span>
                                        <span class="text-{{ $accentColor }}">
                                            {{ $current }} {{ $total > 0 ? '/ '.$total : '' }} {{ $unit }}
                                        </span>
                                    </div>
                                    <div class="w-full h-1.5 bg-surface-variant rounded-full overflow-hidden">
                                        <div class="h-full bg-{{ $accentColor }} rounded-full transition-all duration-500"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex shrink-0">
                                <a href="{{ route('my-list.show', $entry) }}" class="w-12 h-12 bg-surface-container-highest rounded-full flex items-center justify-center text-{{ $accentColor }} hover:bg-{{ $accentColor }} hover:text-on-primary transition-all duration-300">
                                    <span class="material-symbols-outlined">{{ $icon }}</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-surface-container rounded-xl p-12 text-center flex flex-col items-center gap-4">
                            <span class="material-symbols-outlined text-5xl text-on-surface-variant/40">library_add</span>
                            <p class="text-on-surface-variant font-label">{{ __("Your archive is empty. Start adding entries!") }}</p>
                            <button id="openAddModalDashboard" class="gradient-cta px-8 py-3 rounded-full font-label font-bold text-on-primary text-sm hover:scale-105 transition-transform active:scale-95">
                                {{ __('Add First Entry') }}
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Seasonal Progress Sidebar --}}
            <div class="space-y-10">
                {{-- Currently Watching/Reading at a Glance --}}
                <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/10 shadow-sm">
                    <h3 class="text-xl font-headline font-bold mb-8">{{ __('In Progress') }}</h3>

                    <div class="space-y-8">
                        @forelse ($inProgress as $entry)
                            @php
                                $isAnime = $entry->type->usesEpisodes();
                                $current = $isAnime ? ($entry->current_episode ?? 0) : ($entry->current_chapter ?? 0);
                                $total   = $isAnime ? ($entry->total_episodes ?? 0)  : ($entry->total_chapters ?? 0);
                                $pct     = ($total > 0) ? min(100, round(($current / $total) * 100)) : 0;
                                $circumference = 175;
                                $offset  = $circumference - ($circumference * $pct / 100);
                                $colors  = ['text-primary', 'text-secondary', 'text-tertiary'];
                                $color   = $colors[$loop->index % 3];
                            @endphp
                            <div class="flex items-center gap-6">
                                <div class="relative shrink-0 flex items-center justify-center w-16 h-16">
                                    <svg class="w-16 h-16 -rotate-90">
                                        <circle class="text-surface-variant" cx="32" cy="32" fill="transparent" r="28" stroke="currentColor" stroke-width="4"></circle>
                                        <circle class="{{ $color }} transition-all duration-1000" cx="32" cy="32" fill="transparent" r="28" stroke="currentColor"
                                            stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" stroke-width="4"></circle>
                                    </svg>
                                    <span class="absolute text-xs font-black">{{ $pct }}%</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold leading-tight truncate max-w-[140px]">{{ $entry->title }}</h4>
                                    <p class="text-[10px] text-on-surface-variant font-bold uppercase tracking-tighter">{{ $entry->status->label() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-on-surface-variant text-sm font-label text-center py-4">{{ __('Nothing in progress yet.') }}</p>
                        @endforelse
                    </div>

                    <a href="{{ route('my-list') }}" class="block w-full text-center mt-10 py-4 gradient-cta text-on-primary rounded-full font-label font-bold text-sm hover:scale-[1.02] active:scale-95 transition-all">
                        {{ __('View Collection') }}
                    </a>
                </div>

                {{-- Type Breakdown Bento --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-surface-container rounded-xl p-4 flex flex-col items-center justify-center gap-2 aspect-square shadow-sm border border-outline-variant/5">
                        <span class="text-2xl font-black text-primary">{{ $animePercent }}%</span>
                        <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Anime</span>
                    </div>
                    <div class="bg-surface-container rounded-xl p-4 flex flex-col items-center justify-center gap-2 aspect-square shadow-sm border border-outline-variant/5">
                        <span class="text-2xl font-black text-secondary">{{ $mangaPercent }}%</span>
                        <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Manga</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('openAddModalDashboard')?.addEventListener('click', () => {
            document.getElementById('openAddModal')?.click();
        });
    </script>
@endpush
