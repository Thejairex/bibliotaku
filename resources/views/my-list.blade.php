<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'The Archive') }} — {{ __('My List') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-surface text-on-surface font-body selection:bg-primary/30">

    {{-- SideNavBar (Desktop) --}}
    <aside class="hidden md:flex flex-col h-full py-8 px-4 w-64 fixed left-0 top-0 rounded-r-xl bg-neutral-950/70 backdrop-blur-xl shadow-2xl shadow-black/40 z-50">
        <div class="mb-12 px-4">
            <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter text-primary font-headline hover:scale-105 transition-transform inline-block">
                The Archive
            </a>
            <p class="text-xs text-on-surface-variant font-medium tracking-widest uppercase mt-1">{{ __('Digital Curator') }}</p>
        </div>
        <nav class="flex-1 space-y-2">
            <a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50 transition-all duration-300 font-headline font-bold text-sm tracking-wide"
                href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">home</span>
                {{ __('Home') }}
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-full text-primary font-bold border-r-4 border-primary bg-neutral-900/80 transition-all duration-300 font-headline text-sm tracking-wide"
                href="{{ route('my-list') }}">
                <span class="material-symbols-outlined">library_books</span>
                {{ __('My List') }}
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50 transition-all duration-300 font-headline font-bold text-sm tracking-wide" href="#">
                <span class="material-symbols-outlined">search</span>
                {{ __('Search') }}
            </a>
            <a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50 transition-all duration-300 font-headline font-bold text-sm tracking-wide" href="#">
                <span class="material-symbols-outlined">person</span>
                {{ __('Profile') }}
            </a>
        </nav>
        <div class="mt-auto px-4">
            <a class="flex items-center gap-4 py-3 text-on-surface-variant hover:text-on-surface transition-all duration-300 font-headline font-bold text-sm tracking-wide"
                href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined">settings</span>
                {{ __('Settings') }}
            </a>
        </div>
    </aside>

    {{-- TopNavBar --}}
    <header class="fixed top-0 w-full z-40 glass-header flex justify-between items-center px-8 h-20 md:pl-72">
        <div class="flex items-center flex-1 max-w-xl">
            <div class="relative w-full group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
                <input
                    class="w-full bg-surface-container-low border-none rounded-full py-3 pl-12 pr-6 text-sm focus:ring-2 focus:ring-primary/50 transition-all outline-none placeholder:text-on-surface-variant"
                    placeholder="{{ __('Search within your archive...') }}" type="text" />
            </div>
        </div>
        <div class="flex items-center gap-6 ml-8">
            <button class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">grid_view</span>
            </button>
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-primary/20 hover:ring-primary/60 transition-all">
                @if (auth()->user()->avatar)
                    <img alt="{{ auth()->user()->name }}" src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover" />
                @else
                    <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                        <span class="text-primary font-headline font-black text-sm">{{ auth()->user()->initials() }}</span>
                    </div>
                @endif
            </a>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-24 pb-32 md:pb-12 px-6 md:pl-72 min-h-screen">
        <section class="max-w-7xl mx-auto mt-8">

            {{-- Flash Success --}}
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 px-6 py-4 bg-secondary/10 border border-secondary/20 rounded-xl text-secondary text-sm font-medium">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Page Header & Status Filter --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
                <div class="flex items-center gap-6">
                    <div>
                        <span class="text-primary font-bold text-sm tracking-[0.2em] uppercase mb-2 block">{{ __('Curated Collection') }}</span>
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

                {{-- Status Filter Pills --}}
                <div class="flex gap-2 p-1.5 bg-surface-container-low rounded-full overflow-x-auto hide-scrollbar">
                    @php
                        $statuses = [
                            null        => __('All'),
                            'watching'  => __('Watching'),
                            'completed' => __('Completed'),
                            'on_hold'   => __('On Hold'),
                            'dropped'   => __('Dropped'),
                        ];
                    @endphp
                    @foreach ($statuses as $value => $label)
                        <a href="{{ route('my-list', array_filter(['status' => $value, 'type' => $type])) }}"
                            class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all
                                {{ $status === $value ? 'bg-primary-dim text-on-primary shadow-lg shadow-primary-dim/20' : 'text-on-surface-variant hover:text-on-surface' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Type Selector --}}
            @php
                $types = [
                    null      => ['label' => __('All Types'), 'icon' => 'auto_awesome'],
                    'anime'   => ['label' => 'Anime',        'icon' => 'play_circle'],
                    'manga'   => ['label' => 'Manga',        'icon' => 'menu_book'],
                    'manhwa'  => ['label' => 'Manhwa',       'icon' => 'book_5'],
                    'manhua'  => ['label' => 'Manhua',       'icon' => 'book_5'],
                    'novel'   => ['label' => __('Novel'),    'icon' => 'article'],
                ];
            @endphp
            <div class="flex gap-3 mb-8 overflow-x-auto hide-scrollbar pb-2">
                @foreach ($types as $value => $meta)
                    <a href="{{ route('my-list', array_filter(['status' => $status, 'type' => $value])) }}"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-full font-medium text-sm whitespace-nowrap transition-all
                            {{ $type === $value ? 'bg-surface-container-highest text-primary font-bold' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $meta['icon'] }}</span>
                        {{ $meta['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Editorial Table --}}
            <div class="bg-surface-container-low rounded-xl overflow-hidden shadow-2xl">

                {{-- Table Header --}}
                <div class="grid grid-cols-12 px-8 py-5 border-b border-outline-variant/10 text-xs font-bold uppercase tracking-widest text-on-surface-variant">
                    <div class="col-span-6 md:col-span-5">{{ __('Title') }}</div>
                    <div class="hidden md:block col-span-2 text-center">{{ __('Score') }}</div>
                    <div class="col-span-3 md:col-span-2 text-center">{{ __('Progress') }}</div>
                    <div class="hidden md:block col-span-1 text-center">{{ __('Type') }}</div>
                    <div class="col-span-3 md:col-span-2 text-right">{{ __('Actions') }}</div>
                </div>

                {{-- Table Rows --}}
                <div class="divide-y divide-outline-variant/10">
                    @forelse ($entries as $entry)
                        @php
                            $isAnime = $entry->type->usesEpisodes();

                            // Status dot color
                            $dotColor = match($entry->status->value) {
                                'watching', 'rewatching', 'reading' => 'bg-primary animate-pulse',
                                'completed'                         => 'bg-secondary',
                                'on_hold'                           => 'bg-outline',
                                'dropped'                           => 'bg-error',
                                default                             => 'bg-outline',
                            };

                            // Progress bar color
                            $barColor = match($entry->status->value) {
                                'watching', 'rewatching', 'reading' => 'bg-primary',
                                'completed'                         => 'bg-secondary',
                                'dropped'                           => 'bg-error',
                                default                             => 'bg-outline',
                            };

                            // Text accent color for progress numbers
                            $textAccent = match($entry->status->value) {
                                'completed'                         => 'text-secondary',
                                'dropped'                           => 'text-error',
                                default                             => 'text-primary',
                            };

                            // Progress numbers
                            if ($isAnime) {
                                $current = $entry->current_episode ?? 0;
                                $total   = $entry->total_episodes;
                                $unit    = 'Ep.';
                            } else {
                                $current = $entry->current_chapter ?? 0;
                                $total   = $entry->total_chapters;
                                $unit    = 'Ch.';
                            }
                            $pct = ($total > 0) ? min(100, round(($current / $total) * 100)) : 0;
                        @endphp

                        <div class="grid grid-cols-12 items-center px-8 py-6 hover:bg-surface-container-high transition-colors group">

                            {{-- Title & Status --}}
                            <div class="col-span-6 md:col-span-5 flex items-center gap-5">
                                <div class="w-14 h-20 rounded-lg overflow-hidden flex-shrink-0 shadow-lg group-hover:scale-105 transition-transform duration-300 bg-surface-container-highest">
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
                                        <span class="text-xs text-on-surface-variant font-medium">{{ $entry->status->label() }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Score --}}
                            <div class="hidden md:flex col-span-2 justify-center">
                                @if ($entry->rating)
                                    <div class="flex items-center gap-1.5 px-3 py-1 bg-surface-variant rounded-full">
                                        <span class="material-symbols-outlined text-primary text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
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
                                <span class="text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-tighter
                                    {{ $isAnime ? 'bg-secondary/10 text-secondary border border-secondary/20' : 'bg-tertiary/10 text-tertiary border border-tertiary/20' }}">
                                    {{ $entry->type->label() }}
                                </span>
                            </div>

                            {{-- Actions --}}
                            <div class="col-span-3 md:col-span-2 flex justify-end gap-2">
                                <a href="{{ route('my-list.show', $entry) }}"
                                    class="p-2 rounded-full bg-surface-container-highest hover:bg-primary/20 hover:text-primary transition-all duration-300"
                                    title="{{ __('View Details') }}">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                @if ($entry->mal_id)
                                    <a href="{{ $entry->malUrl() }}" target="_blank"
                                        class="p-2 rounded-full bg-surface-container-highest hover:bg-surface-variant transition-all"
                                        title="Ver en MAL">
                                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                                    </a>
                                @endif
                                <button
                                    class="p-2 rounded-full bg-surface-container-highest hover:bg-surface-variant transition-all"
                                    title="{{ __('Edit') }}">
                                    <span class="material-symbols-outlined text-[20px]">edit_note</span>
                                </button>
                                <button
                                    class="p-2 rounded-full bg-surface-container-highest hover:bg-error/20 hover:text-error transition-all duration-300"
                                    title="{{ __('Delete') }}">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-24 text-center flex flex-col items-center gap-4">
                            <span class="material-symbols-outlined text-6xl text-on-surface-variant/30">library_add</span>
                            <p class="text-on-surface-variant font-label text-lg">{{ __('Your archive is empty.') }}</p>
                            <p class="text-on-surface-variant/60 text-sm">
                                @if ($status || $type)
                                    {{ __('No entries match the current filters.') }}
                                    <a href="{{ route('my-list') }}" class="text-primary font-bold ml-1 hover:underline">{{ __('Clear filters') }}</a>
                                @else
                                    {{ __('Start adding anime and manga to your collection!') }}
                                @endif
                            </p>
                            @if (!$status && !$type)
                                <button id="openAddModalEmpty" class="gradient-cta mt-4 px-8 py-3 rounded-full font-label font-bold text-on-primary text-sm hover:scale-105 transition-transform active:scale-95">
                                    {{ __('Add First Entry') }}
                                </button>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pagination --}}
            @if ($entries->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $entries->links() }}
                </div>
            @endif

        </section>
    </main>

    {{-- BottomNavBar (Mobile) --}}
    <nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-6 pb-8 pt-4 bg-neutral-950/80 backdrop-blur-xl rounded-t-xl z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.6)] font-label text-[10px] uppercase tracking-widest">
        <a class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform"
            href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined">home</span>
            <span class="mt-1">{{ __('Home') }}</span>
        </a>
        <a class="flex flex-col items-center justify-center bg-primary/20 text-primary rounded-full px-6 py-2 active:scale-95 transition-transform"
            href="{{ route('my-list') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">format_list_bulleted</span>
            <span class="mt-1">{{ __('List') }}</span>
        </a>
        <button id="openAddModalMobile" class="flex flex-col items-center justify-center text-primary active:scale-95 transition-transform">
            <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">add_circle</span>
            <span class="mt-1">{{ __('Add') }}</span>
        </button>
        <a class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform"
            href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="mt-1">{{ __('Profile') }}</span>
        </a>
    </nav>

    {{-- ============================================================ --}}
    {{-- ADD ENTRY MODAL --}}
    {{-- ============================================================ --}}
    <div id="addEntryModal"
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">

        {{-- Backdrop --}}
        <div id="modalBackdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-surface-container rounded-xl shadow-2xl shadow-black/60 flex flex-col
                    transform scale-95 transition-transform duration-300" id="modalPanel">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 py-6 border-b border-outline-variant/10">
                <div>
                    <h3 class="font-headline font-black text-2xl tracking-tight">{{ __('Add to Archive') }}</h3>
                    <p class="text-on-surface-variant text-sm mt-0.5">{{ __('Log a new anime, manga, or any other medium.') }}</p>
                </div>
                <button id="closeAddModal" class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition-all">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Modal Form --}}
            <form method="POST" action="{{ route('my-list.store') }}" class="px-8 py-6 space-y-6">
                @csrf

                {{-- Title Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_title">
                            {{ __('Title') }} <span class="text-error">*</span>
                        </label>
                        <input id="modal_title" name="title" type="text" required
                            value="{{ old('title') }}"
                            placeholder="{{ __('e.g. Attack on Titan') }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                        @error('title') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_original_title">
                            {{ __('Original Title') }}
                        </label>
                        <input id="modal_original_title" name="original_title" type="text"
                            value="{{ old('original_title') }}"
                            placeholder="{{ __('e.g. 進撃の巨人') }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                </div>

                {{-- Type & Status Row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_type">
                            {{ __('Type') }} <span class="text-error">*</span>
                        </label>
                        <select id="modal_type" name="type" required
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all appearance-none cursor-pointer">
                            <option value="anime"  {{ old('type') === 'anime'   ? 'selected' : '' }}>Anime</option>
                            <option value="manga"  {{ old('type') === 'manga'   ? 'selected' : '' }}>Manga</option>
                            <option value="manhwa" {{ old('type') === 'manhwa'  ? 'selected' : '' }}>Manhwa</option>
                            <option value="manhua" {{ old('type') === 'manhua'  ? 'selected' : '' }}>Manhua</option>
                            <option value="novel"  {{ old('type') === 'novel'   ? 'selected' : '' }}>{{ __('Novel') }}</option>
                        </select>
                        @error('type') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_status">
                            {{ __('Status') }} <span class="text-error">*</span>
                        </label>
                        <select id="modal_status" name="status" required
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all appearance-none cursor-pointer">
                            <option value="watching"      {{ old('status') === 'watching'      ? 'selected' : '' }}>{{ __('Watching') }}</option>
                            <option value="reading"       {{ old('status') === 'reading'       ? 'selected' : '' }}>{{ __('Reading') }}</option>
                            <option value="rewatching"    {{ old('status') === 'rewatching'    ? 'selected' : '' }}>{{ __('Rewatching') }}</option>
                            <option value="completed"     {{ old('status') === 'completed'     ? 'selected' : '' }}>{{ __('Completed') }}</option>
                            <option value="on_hold"       {{ old('status') === 'on_hold'       ? 'selected' : '' }}>{{ __('On Hold') }}</option>
                            <option value="dropped"       {{ old('status') === 'dropped'       ? 'selected' : '' }}>{{ __('Dropped') }}</option>
                            <option value="plan_to_watch" {{ old('status', 'plan_to_watch') === 'plan_to_watch' ? 'selected' : '' }}>{{ __('Plan to Watch') }}</option>
                        </select>
                        @error('status') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Progress (shows dynamically based on type) --}}
                <div id="animeProgress" class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_current_episode">{{ __('Current Episode') }}</label>
                        <input id="modal_current_episode" name="current_episode" type="number" min="0"
                            value="{{ old('current_episode', 0) }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_total_episodes">{{ __('Total Episodes') }}</label>
                        <input id="modal_total_episodes" name="total_episodes" type="number" min="0"
                            value="{{ old('total_episodes') }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                </div>

                <div id="mangaProgress" class="grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Current Chapter') }}</label>
                        <input name="current_chapter" type="number" min="0" value="{{ old('current_chapter', 0) }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Total Chapters') }}</label>
                        <input name="total_chapters" type="number" min="0" value="{{ old('total_chapters') }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Current Volume') }}</label>
                        <input name="current_volume" type="number" min="0" value="{{ old('current_volume', 0) }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Total Volumes') }}</label>
                        <input name="total_volumes" type="number" min="0" value="{{ old('total_volumes') }}"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                </div>

                {{-- Rating & MAL ID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_rating">
                            {{ __('Rating') }} <span class="text-on-surface-variant font-normal">(1–10)</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                            <input id="modal_rating" name="rating" type="number" min="1" max="10"
                                value="{{ old('rating') }}"
                                placeholder="—"
                                class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                        </div>
                        @error('rating') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_mal_id">
                            {{ __('MAL ID') }} <span class="text-on-surface-variant font-normal">({{ __('optional') }})</span>
                        </label>
                        <input id="modal_mal_id" name="mal_id" type="number" min="1"
                            value="{{ old('mal_id') }}"
                            placeholder="e.g. 16498"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                        @error('mal_id') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Cover URL --}}
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_cover_url">
                        {{ __('Cover Image URL') }} <span class="text-on-surface-variant font-normal">({{ __('optional') }})</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">image</span>
                        <input id="modal_cover_url" name="cover_url" type="url"
                            value="{{ old('cover_url') }}"
                            placeholder="https://..."
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                    @error('cover_url') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_notes">
                        {{ __('Notes') }} <span class="text-on-surface-variant font-normal">({{ __('optional') }})</span>
                    </label>
                    <textarea id="modal_notes" name="notes" rows="3"
                        placeholder="{{ __('Your thoughts, recommendations, reminders...') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all resize-none">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4 pt-2 border-t border-outline-variant/10">
                    <button type="button" id="cancelAddModal"
                        class="px-6 py-3 rounded-full font-label font-bold text-sm text-on-surface-variant hover:text-on-surface bg-surface-container-highest hover:bg-surface-variant transition-all">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="gradient-cta px-10 py-3 rounded-full font-label font-bold text-on-primary text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95">
                        {{ __('Add to Archive') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const modal       = document.getElementById('addEntryModal');
            const panel       = document.getElementById('modalPanel');
            const backdrop    = document.getElementById('modalBackdrop');
            const typeSelect  = document.getElementById('modal_type');
            const animeBlock  = document.getElementById('animeProgress');
            const mangaBlock  = document.getElementById('mangaProgress');

            const ANIME_TYPES = ['anime'];

            function openModal() {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                panel.classList.remove('scale-95');
                panel.classList.add('scale-100');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('opacity-0', 'pointer-events-none');
                panel.classList.add('scale-95');
                panel.classList.remove('scale-100');
                document.body.style.overflow = '';
            }

            function updateProgressFields() {
                const isAnime = ANIME_TYPES.includes(typeSelect.value);
                animeBlock.classList.toggle('hidden', !isAnime);
                mangaBlock.classList.toggle('hidden', isAnime);
            }

            // Triggers
            document.getElementById('openAddModal')?.addEventListener('click', openModal);
            document.getElementById('openAddModalEmpty')?.addEventListener('click', openModal);
            document.getElementById('openAddModalMobile')?.addEventListener('click', openModal);
            document.getElementById('closeAddModal').addEventListener('click', closeModal);
            document.getElementById('cancelAddModal').addEventListener('click', closeModal);
            backdrop.addEventListener('click', closeModal);

            // Type toggle
            typeSelect.addEventListener('change', updateProgressFields);
            updateProgressFields(); // init

            // Auto-open if there are validation errors
            @if ($errors->any())
                openModal();
            @endif

            // Esc key
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

            // Open MAL modal via Livewire event
            document.getElementById('openMalModal')?.addEventListener('click', () => {
                window.Livewire.dispatch('open-mal-search');
            });

            // Mobile: show a small picker when tapping Add
            const mobileAddBtn = document.getElementById('openAddModalMobile');
            const mobilePicker = document.getElementById('mobileAddPicker');
            mobileAddBtn?.addEventListener('click', () => {
                mobilePicker?.classList.toggle('hidden');
            });
            document.getElementById('mobilePickerManual')?.addEventListener('click', () => {
                mobilePicker?.classList.add('hidden');
                openModal();
            });
            document.getElementById('mobilePickerMal')?.addEventListener('click', () => {
                mobilePicker?.classList.add('hidden');
                window.Livewire.dispatch('open-mal-search');
            });

            // Reload page when Livewire saves an entry
            document.addEventListener('livewire:entry-saved', () => window.location.reload());
            document.addEventListener('entry-saved', () => window.location.reload());
        })();
    </script>

    {{-- Livewire MAL Search Modal Component --}}
    <livewire:media-search-mal />

    {{-- Mobile Add Picker --}}
    <div id="mobileAddPicker"
        class="md:hidden hidden fixed bottom-28 left-1/2 -translate-x-1/2 z-[150] flex flex-col gap-2 w-56 p-3 bg-surface-container rounded-xl shadow-2xl shadow-black/60 border border-outline-variant/10">
        <button id="mobilePickerManual"
            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-surface-container-high hover:bg-surface-variant transition-all text-sm font-bold text-on-surface">
            <span class="material-symbols-outlined text-[20px] text-on-surface-variant">edit_note</span>
            {{ __('Add Manually') }}
        </button>
        <button id="mobilePickerMal"
            class="flex items-center gap-3 px-4 py-3 rounded-xl gradient-cta text-on-primary transition-all text-sm font-bold">
            <span class="material-symbols-outlined text-[20px]">travel_explore</span>
            {{ __('Search in MAL') }}
        </button>
    </div>

    @livewireScripts

</body>
</html>
