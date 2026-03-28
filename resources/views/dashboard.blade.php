<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'The Archive') }} | {{ __('Digital Curator Dashboard') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-surface text-on-surface font-body">

    {{-- SideNavBar (Desktop Only) --}}
    <aside class="hidden md:flex flex-col h-full w-64 fixed left-0 top-0 rounded-r-xl bg-neutral-950/70 backdrop-blur-xl shadow-2xl shadow-black/40 z-50 py-8 px-4 font-headline font-bold text-sm tracking-wide">
        <div class="mb-10 px-4">
            <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter text-primary hover:scale-105 transition-transform inline-block">
                The Archive
            </a>
            <p class="text-xs text-on-surface-variant font-medium mt-1">{{ __('Digital Curator') }}</p>
        </div>
        <nav class="flex flex-col gap-2">
            <a class="flex items-center gap-4 px-4 py-3 text-primary font-bold border-r-4 border-primary bg-neutral-900/80 transition-all duration-300 rounded-l-lg"
                href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
                {{ __('Home') }}
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50 transition-all duration-300 rounded-lg" href="#">
                <span class="material-symbols-outlined">library_books</span>
                {{ __('My List') }}
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50 transition-all duration-300 rounded-lg" href="#">
                <span class="material-symbols-outlined">search</span>
                {{ __('Search') }}
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50 transition-all duration-300 rounded-lg" href="#">
                <span class="material-symbols-outlined">person</span>
                {{ __('Profile') }}
            </a>
            <a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50 transition-all duration-300 rounded-lg"
                href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined">settings</span>
                {{ __('Settings') }}
            </a>
        </nav>
        {{-- User Profile Card --}}
        <div class="mt-auto px-4 pb-4">
            <div class="flex items-center gap-3 p-3 bg-surface-container rounded-xl">
                @if (auth()->user()->avatar)
                    <img class="w-10 h-10 rounded-full object-cover"
                        src="{{ auth()->user()->avatar }}"
                        alt="{{ auth()->user()->name }}" />
                @else
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center shrink-0">
                        <span class="text-primary font-headline font-black text-sm">{{ auth()->user()->initials() }}</span>
                    </div>
                @endif
                <div class="overflow-hidden">
                    <p class="text-xs font-bold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-on-surface-variant font-medium uppercase tracking-tighter">{{ __('Curator') }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- TopNavBar --}}
    <header class="fixed top-0 w-full z-40 flex justify-between items-center px-8 h-20 glass-header md:pl-72 transition-all">
        <div class="flex items-center gap-6 flex-1">
            <div class="relative w-full max-w-md group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
                <input
                    class="w-full bg-surface-container-low border-none rounded-full py-2.5 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary/50 transition-all outline-none"
                    placeholder="{{ __('Search your archive...') }}" type="text" />
            </div>
        </div>
        <div class="flex items-center gap-4 ml-4">
            <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-high transition-colors text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface-container-high transition-colors text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">grid_view</span>
            </button>
            {{-- Mobile avatar --}}
            @if (auth()->user()->avatar)
                <img class="md:hidden w-10 h-10 rounded-full object-cover"
                    src="{{ auth()->user()->avatar }}"
                    alt="{{ auth()->user()->name }}" />
            @else
                <div class="md:hidden w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                    <span class="text-primary font-headline font-black text-sm">{{ auth()->user()->initials() }}</span>
                </div>
            @endif
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-24 pb-28 md:pl-72 min-h-screen px-4 md:px-8 max-w-[1600px] mx-auto">

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
                    @php
                        $avgRating = auth()->user()->mediaEntries()->whereNotNull('rating')->avg('rating');
                    @endphp
                    <div class="bg-surface-container-low p-6 rounded-xl min-w-[140px] text-center">
                        <p class="text-3xl font-headline font-black text-primary">
                            {{ $avgRating ? number_format($avgRating, 1) : '—' }}
                        </p>
                        <p class="text-[10px] font-label text-on-surface-variant uppercase tracking-widest mt-1">{{ __('Avg Rating') }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Stats Grid (Bento Style) --}}
        @php
            $watching  = auth()->user()->mediaEntries()->whereIn('status', ['watching', 'rewatching'])->count();
            $reading   = auth()->user()->mediaEntries()->where('status', 'reading')->count();
            $completed = auth()->user()->mediaEntries()->where('status', 'completed')->count();
            $onHold    = auth()->user()->mediaEntries()->where('status', 'on_hold')->count();
        @endphp
        <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-12">
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-primary group-hover:scale-110 transition-transform">visibility</span>
                    <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded-full">{{ __('active') }}</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $watching }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('Watching') }}</p>
            </div>
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-tertiary group-hover:scale-110 transition-transform">auto_stories</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $reading }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('Reading') }}</p>
            </div>
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-secondary group-hover:scale-110 transition-transform">check_circle</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $completed }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('Completed') }}</p>
            </div>
            <div class="bg-surface-container p-6 rounded-xl hover:bg-surface-container-high transition-all duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <span class="material-symbols-outlined text-outline group-hover:scale-110 transition-transform">pause_circle</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold">{{ $onHold }}</h3>
                <p class="text-sm font-label text-on-surface-variant">{{ __('On Hold') }}</p>
            </div>
        </section>

        {{-- Main Content Area --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">

            {{-- Latest Updates Column --}}
            <div class="xl:col-span-2">
                <div class="flex items-center justify-between mb-8 px-2">
                    <h3 class="text-2xl font-headline font-bold">{{ __('Latest Updates') }}</h3>
                    <a href="#" class="text-sm font-bold text-primary hover:underline">{{ __('View All') }}</a>
                </div>

                @php
                    $recentEntries = auth()->user()->mediaEntries()
                        ->whereIn('status', ['watching', 'reading', 'rewatching', 'completed'])
                        ->orderBy('updated_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                <div class="flex flex-col gap-6">
                    @forelse ($recentEntries as $entry)
                        @php
                            $isAnime = $entry->type->usesEpisodes();
                            $accentColor = $isAnime ? 'primary' : 'tertiary';
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
                        <div class="group bg-surface-container rounded-xl overflow-hidden flex flex-col sm:flex-row items-center gap-6 p-4 hover:bg-surface-container-highest transition-all duration-300">
                            <div class="relative w-full sm:w-32 h-44 sm:h-32 shrink-0 overflow-hidden rounded-lg">
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
                                    <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest">
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
                                <button class="w-12 h-12 bg-surface-container-highest rounded-full flex items-center justify-center text-{{ $accentColor }} hover:bg-{{ $accentColor }} hover:text-on-primary transition-all duration-300">
                                    <span class="material-symbols-outlined">{{ $icon }}</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="bg-surface-container rounded-xl p-12 text-center flex flex-col items-center gap-4">
                            <span class="material-symbols-outlined text-5xl text-on-surface-variant/40">library_add</span>
                            <p class="text-on-surface-variant font-label">{{ __("Your archive is empty. Start adding entries!") }}</p>
                            <button class="gradient-cta px-8 py-3 rounded-full font-label font-bold text-on-primary text-sm hover:scale-105 transition-transform active:scale-95">
                                {{ __('Add First Entry') }}
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Seasonal Progress Sidebar --}}
            <div class="space-y-10">
                {{-- Currently Watching/Reading at a Glance --}}
                <div class="bg-surface-container-low p-8 rounded-xl border border-outline-variant/10">
                    <h3 class="text-xl font-headline font-bold mb-8">{{ __('In Progress') }}</h3>

                    @php
                        $inProgress = auth()->user()->mediaEntries()
                            ->whereIn('status', ['watching', 'reading', 'rewatching'])
                            ->whereNotNull('total_episodes')
                            ->orWhere(function($q) {
                                $q->whereIn('status', ['watching', 'reading', 'rewatching'])
                                  ->whereNotNull('total_chapters');
                            })
                            ->limit(3)
                            ->get();
                    @endphp

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

                    <button class="w-full mt-10 py-4 gradient-cta text-on-primary rounded-full font-label font-bold text-sm hover:scale-[1.02] active:scale-95 transition-all">
                        {{ __('Edit Schedule') }}
                    </button>
                </div>

                {{-- Type Breakdown Bento --}}
                @php
                    $totalEntries = auth()->user()->mediaEntries()->count();
                    $animeCount   = auth()->user()->mediaEntries()->where('type', 'anime')->count();
                    $mangaCount   = auth()->user()->mediaEntries()->whereIn('type', ['manga', 'manhwa', 'manhua'])->count();
                    $animePercent = $totalEntries > 0 ? round(($animeCount / $totalEntries) * 100) : 0;
                    $mangaPercent = $totalEntries > 0 ? round(($mangaCount / $totalEntries) * 100) : 0;
                @endphp
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-surface-container rounded-xl p-4 flex flex-col items-center justify-center gap-2 aspect-square">
                        <span class="text-2xl font-black text-primary">{{ $animePercent }}%</span>
                        <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Anime</span>
                    </div>
                    <div class="bg-surface-container rounded-xl p-4 flex flex-col items-center justify-center gap-2 aspect-square">
                        <span class="text-2xl font-black text-secondary">{{ $mangaPercent }}%</span>
                        <span class="text-[9px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Manga</span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- BottomNavBar (Mobile Only) --}}
    <nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-6 pb-8 pt-4 bg-neutral-950/80 backdrop-blur-xl rounded-t-xl z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.6)] font-label text-[10px] uppercase tracking-widest">
        <a class="flex flex-col items-center justify-center bg-primary/20 text-primary rounded-full px-6 py-2 transition-transform active:scale-95"
            href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
            <span class="mt-1">{{ __('Home') }}</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant transition-transform active:scale-95" href="#">
            <span class="material-symbols-outlined">format_list_bulleted</span>
            <span class="mt-1">{{ __('List') }}</span>
        </a>
        <button class="flex flex-col items-center justify-center text-on-surface-variant transition-transform active:scale-95">
            <span class="material-symbols-outlined text-3xl">add_circle</span>
        </button>
        <a class="flex flex-col items-center justify-center text-on-surface-variant transition-transform active:scale-95"
            href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="mt-1">{{ __('Profile') }}</span>
        </a>
    </nav>

</body>

</html>
