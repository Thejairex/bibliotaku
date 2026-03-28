@php
    $isAnime    = $mediaEntry->isAnime();
    $current    = $isAnime ? ($mediaEntry->current_episode ?? 0) : ($mediaEntry->current_chapter ?? 0);
    $total      = $isAnime ? $mediaEntry->total_episodes : $mediaEntry->total_chapters;
    $progLabel  = $isAnime ? 'Ep' : 'Ch';
    $progText   = "{$current}";
    $progTotal  = $total ? " / {$total} {$progLabel}" : " / ? {$progLabel}";

    $synopsis   = current(explode('[Written by MAL Rewrite]', $malData['synopsis'] ?? '')) ?: ($mediaEntry->notes ?? __('No synopsis available.'));

    $statusColor = match($mediaEntry->status->value) {
        'watching', 'rewatching', 'reading' => 'bg-primary animate-pulse',
        'completed'                         => 'bg-secondary',
        'on_hold'                           => 'bg-outline',
        'dropped'                           => 'bg-error',
        default                             => 'bg-outline',
    };
@endphp

<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'The Archive') }} — {{ $mediaEntry->title }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hero-gradient {
            background: linear-gradient(to top, #0e0e0e 0%, rgba(14, 14, 14, 0.4) 50%, rgba(14, 14, 14, 0) 100%);
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-body selection:bg-primary/30">

    {{-- SideNavBar (Desktop) --}}
    <aside class="hidden lg:flex flex-col py-8 px-4 h-full w-64 fixed left-0 top-0 rounded-r-[3rem] bg-neutral-950/70 backdrop-blur-xl shadow-2xl shadow-black/40 z-50">
        <div class="mb-12 px-4">
            <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter text-violet-500 font-headline">The Archive</a>
            <p class="text-[10px] text-neutral-500 font-label uppercase tracking-widest mt-1">{{ __('Digital Curator') }}</p>
        </div>
        <nav class="flex flex-col gap-2">
            <a class="flex items-center gap-4 px-4 py-4 rounded-xl text-neutral-400 hover:text-neutral-100 transition-all duration-300 hover:bg-neutral-800/50 group" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined text-xl group-hover:text-violet-400">home</span>
                <span class="font-headline font-bold text-sm tracking-wide">{{ __('Home') }}</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-4 rounded-xl text-neutral-400 hover:text-neutral-100 transition-all duration-300 hover:bg-neutral-800/50 group" href="{{ route('my-list') }}">
                <span class="material-symbols-outlined text-xl group-hover:text-violet-400">library_books</span>
                <span class="font-headline font-bold text-sm tracking-wide">{{ __('My List') }}</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-4 rounded-xl text-neutral-400 hover:text-neutral-100 transition-all duration-300 hover:bg-neutral-800/50 group" href="#">
                <span class="material-symbols-outlined text-xl group-hover:text-violet-400">search</span>
                <span class="font-headline font-bold text-sm tracking-wide">{{ __('Search') }}</span>
            </a>
            <a class="flex items-center gap-4 px-4 py-4 rounded-xl text-neutral-400 hover:text-neutral-100 transition-all duration-300 hover:bg-neutral-800/50 group" href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined text-xl group-hover:text-violet-400">person</span>
                <span class="font-headline font-bold text-sm tracking-wide">{{ __('Profile') }}</span>
            </a>
        </nav>
    </aside>

    {{-- TopNavBar --}}
    <nav class="fixed top-0 w-full z-40 bg-neutral-950/70 backdrop-blur-2xl flex justify-between items-center px-8 h-20 ml-auto lg:pl-72">
        <div class="flex items-center gap-8">
            <span class="text-xl font-black text-violet-400 font-headline tracking-tighter lg:hidden">The Archive</span>
        </div>
        <div class="flex items-center gap-4">
            <button class="p-2 text-neutral-400 hover:text-violet-300 rounded-full transition-all">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <div class="w-10 h-10 rounded-full bg-surface-container-highest overflow-hidden">
                @if (auth()->user()->avatar)
                    <img alt="User avatar" class="w-full h-full object-cover" src="{{ auth()->user()->avatar }}"/>
                @else
                    <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                        <span class="text-primary font-headline font-black text-sm">{{ auth()->user()->initials() }}</span>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <main class="lg:ml-64 pt-20 pb-32 min-h-screen">
        {{-- Hero Section --}}
        <header class="relative w-full h-[500px] lg:h-[614px] overflow-hidden">
            <div class="absolute inset-0 z-0 bg-surface-container">
                @if ($mediaEntry->cover_url)
                    <img alt="Background" class="w-full h-full object-cover opacity-30 scale-110 blur-[8px]" src="{{ $mediaEntry->cover_url }}"/>
                @endif
                <div class="absolute inset-0 hero-gradient"></div>
            </div>
            <div class="relative z-10 container mx-auto px-8 h-full flex flex-col justify-end pb-12 lg:pb-16">
                <div class="flex flex-col lg:flex-row items-end lg:items-center gap-8 lg:gap-12">
                    {{-- Poster --}}
                    <div class="hidden lg:block w-72 aspect-[2/3] rounded-lg shadow-2xl shadow-black overflow-hidden transform -rotate-2 hover:rotate-0 transition-transform duration-700 bg-surface-container">
                        @if ($mediaEntry->cover_url)
                            <img alt="{{ $mediaEntry->title }}" class="w-full h-full object-cover" src="{{ $mediaEntry->cover_url }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-surface-variant/40 text-6xl">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-left w-full">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-primary/20 text-primary-fixed-dim font-label font-bold text-[10px] px-3 py-1 rounded-full border border-primary/20 tracking-widest uppercase">
                                {{ $mediaEntry->status->label() }}
                            </span>
                            <span class="bg-surface-container-highest text-on-surface-variant font-label font-bold text-[10px] px-3 py-1 rounded-full tracking-widest uppercase">
                                {{ $mediaEntry->type->label() }}
                            </span>
                            @if ($malData && isset($malData['premiered']))
                                <span class="bg-surface-container-highest text-on-surface-variant font-label font-bold text-[10px] px-3 py-1 rounded-full tracking-widest uppercase">
                                    {{ $malData['premiered'] }}
                                </span>
                            @endif
                        </div>
                        <h1 class="font-headline font-extrabold text-4xl lg:text-7xl text-on-surface tracking-tight mb-2">
                            {{ $mediaEntry->title }}
                        </h1>
                        @if ($mediaEntry->original_title)
                            <h2 class="font-headline text-xl text-on-surface-variant tracking-normal">
                                {{ $mediaEntry->original_title }}
                            </h2>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        {{-- Content Grid --}}
        <section class="container mx-auto px-6 lg:px-8 -mt-8 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- Left Column: Primary Info --}}
                <div class="lg:col-span-8 space-y-10">
                    
                    {{-- Tracking Card --}}
                    <div class="bg-surface-container-low rounded-xl p-6 lg:p-10 shadow-xl border border-outline-variant/5">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                            <div>
                                <h3 class="font-headline font-bold text-2xl mb-1">{{ __('Your Tracking') }}</h3>
                                <p class="text-on-surface-variant font-body text-sm">{{ __('Last updated') }} {{ $mediaEntry->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex gap-3">
                                <button class="gradient-cta text-on-primary font-label font-bold text-sm px-6 py-3 rounded-full flex items-center gap-2 shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                    {{ __('Edit Entry') }}
                                </button>
                                <button class="w-12 h-12 flex items-center justify-center rounded-full bg-surface-container-highest hover:bg-surface-variant text-on-surface active:scale-95 transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[20px]">share</span>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Status --}}
                            <div class="bg-surface-container rounded-[1.5rem] p-6 lg:p-7 flex flex-col justify-center gap-2">
                                <span class="text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest">{{ __('Status') }}</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full {{ $statusColor }}"></div>
                                    <span class="font-headline font-bold text-lg">{{ $mediaEntry->status->label() }}</span>
                                </div>
                            </div>
                            
                            {{-- Progress --}}
                            <div class="bg-surface-container rounded-[1.5rem] p-6 lg:p-7 flex flex-col justify-center gap-2">
                                <span class="text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest">{{ __('Progress') }}</span>
                                <div class="flex items-end gap-1">
                                    <span class="font-headline font-bold text-3xl text-primary leading-none">{{ $progText }}</span>
                                    <span class="text-on-surface-variant font-headline font-medium text-sm pb-1">{{ $progTotal }}</span>
                                </div>
                            </div>
                            
                            {{-- Score --}}
                            <div class="bg-surface-container rounded-[1.5rem] p-6 lg:p-7 flex flex-col justify-center gap-2">
                                <span class="text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest">{{ __('Your Score') }}</span>
                                <div class="flex items-end gap-2">
                                    <span class="material-symbols-outlined text-primary text-2xl pb-0.5" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span class="font-headline font-bold text-3xl leading-none w-min">{{ $mediaEntry->rating ?? '—' }}</span>
                                    <span class="text-on-surface-variant font-headline font-medium text-sm pb-1">/ 10</span>
                                </div>
                            </div>
                        </div>

                        @if ($mediaEntry->notes)
                            <div class="mt-6 bg-surface-container rounded-[1.5rem] p-6 lg:p-8">
                                <span class="text-[10px] font-label font-bold text-on-surface-variant uppercase tracking-widest mb-3 block">{{ __('Personal Notes') }}</span>
                                <p class="text-on-surface-variant font-body leading-relaxed italic">"{{ $mediaEntry->notes }}"</p>
                            </div>
                        @endif
                    </div>

                    {{-- Synopsis --}}
                    <div class="space-y-6">
                        <h3 class="font-headline font-bold text-3xl">{{ __('Synopsis') }}</h3>
                        <div class="prose prose-invert max-w-none text-on-surface-variant font-body leading-loose text-[15px] space-y-4">
                            {!! nl2br(e($synopsis)) !!}
                        </div>
                    </div>

                </div>

                {{-- Right Column: Metadata & External Links --}}
                <aside class="lg:col-span-4 space-y-8">
                    
                    {{-- Metadata Bento --}}
                    <div class="bg-surface-container-highest/30 rounded-xl p-8 space-y-8 border border-outline-variant/10">
                        <div>
                            <h4 class="text-[10px] font-label font-black text-primary uppercase tracking-widest mb-4">{{ __('Metadata') }}</h4>
                            <div class="space-y-6">
                                
                                @if ($malData && !empty($malData['genres']))
                                    <div class="flex justify-between items-start">
                                        <span class="text-on-surface-variant font-label text-sm">{{ __('Genres') }}</span>
                                        <div class="flex flex-wrap justify-end gap-2 max-w-[200px]">
                                            @foreach ((array) $malData['genres'] as $genre)
                                                <span class="bg-surface-container-highest px-3 py-1 rounded-full text-[10px] font-label">{{ $genre['name'] ?? $genre }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($malData && !empty($malData['studios']) && is_array($malData['studios']))
                                    <div class="flex justify-between">
                                        <span class="text-on-surface-variant font-label text-sm">{{ __('Studios') }}</span>
                                        <span class="text-on-surface font-headline font-bold text-sm text-right">
                                            {{ implode(', ', array_column($malData['studios'], 'name')) }}
                                        </span>
                                    </div>
                                @endif

                                @if ($malData && isset($malData['source']))
                                    <div class="flex justify-between">
                                        <span class="text-on-surface-variant font-label text-sm">{{ __('Source') }}</span>
                                        <span class="text-on-surface font-headline font-bold text-sm text-right">{{ $malData['source'] }}</span>
                                    </div>
                                @endif

                                @if ($malData && isset($malData['aired']) && isset($malData['aired']['string']))
                                    <div class="flex justify-between">
                                        <span class="text-on-surface-variant font-label text-sm">{{ __('Aired') }}</span>
                                        <span class="text-on-surface font-headline font-bold text-sm text-right">{{ $malData['aired']['string'] }}</span>
                                    </div>
                                @endif
                                
                                @if ($malData && isset($malData['status']))
                                    <div class="flex justify-between">
                                        <span class="text-on-surface-variant font-label text-sm">{{ __('Release Status') }}</span>
                                        <span class="text-on-surface font-headline font-bold text-sm text-right">{{ $malData['status'] }}</span>
                                    </div>
                                @endif

                                @if ($malData && isset($malData['score']))
                                    <div class="flex justify-between">
                                        <span class="text-on-surface-variant font-label text-sm">{{ __('MAL Score') }}</span>
                                        <span class="text-on-surface font-headline font-bold text-sm text-right flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px] text-primary" style="font-variation-settings: 'FILL' 1;">star</span>
                                            {{ $malData['score'] }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- External Links --}}
                        <div>
                            <h4 class="text-[10px] font-label font-black text-primary uppercase tracking-widest mb-4">{{ __('External Links') }}</h4>
                            <div class="flex flex-col gap-3">
                                @if ($mediaEntry->mal_id)
                                    <a class="flex items-center justify-between p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-all group" href="{{ $mediaEntry->malUrl() }}" target="_blank">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-blue-400">open_in_new</span>
                                            <span class="font-headline font-bold text-sm">MyAnimeList</span>
                                        </div>
                                        <span class="material-symbols-outlined text-on-surface-variant opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all">chevron_right</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </section>
    </main>

    {{-- Bottom Navigation (Mobile Only) --}}
    <nav class="lg:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-6 pb-8 pt-4 bg-neutral-950/80 backdrop-blur-xl rounded-t-[3rem] z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.6)]">
        <a class="flex flex-col items-center justify-center text-neutral-500 active:scale-95 transition-transform" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined">home</span>
            <span class="font-label text-[10px] uppercase tracking-widest mt-1">{{ __('Home') }}</span>
        </a>
        <a class="flex flex-col items-center justify-center text-neutral-500 active:scale-95 transition-transform" href="{{ route('my-list') }}">
            <span class="material-symbols-outlined">format_list_bulleted</span>
            <span class="font-label text-[10px] uppercase tracking-widest mt-1">{{ __('List') }}</span>
        </a>
        <a class="flex flex-col items-center justify-center text-neutral-500 active:scale-95 transition-transform" href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="font-label text-[10px] uppercase tracking-widest mt-1">{{ __('Profile') }}</span>
        </a>
    </nav>
</body>
</html>
