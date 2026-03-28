@extends('layouts.app')

@section('title', config('app.name', 'The Archive') . ' — ' . __('Profile: :name', ['name' => $user->name]))

@push('styles')
<style>
    .gradient-button-profile {
        background: linear-gradient(135deg, #ba9eff 0%, #8455ef 100%);
    }
    .profile-hero-grad {
        background: linear-gradient(to bottom, rgba(14,14,14,0) 0%, rgba(14,14,14,0.7) 60%, #0e0e0e 100%);
    }
</style>
@endpush

@section('content')

{{-- Pull the section edge-to-edge by cancelling the parent's padding --}}
<div class="-mt-24 -mx-6 md:-mx-0 md:ml-0">

    {{-- ┌─────────────────────────────────────────────────────┐ --}}
    {{--   HERO SECTION                                         --}}
    {{-- └─────────────────────────────────────────────────────┘ --}}
    <section class="relative overflow-hidden">

        {{-- Background image --}}
        @php
            $heroBg = $favorites->first()?->cover_url ?? null;
        @endphp
        @if ($heroBg)
            <div class="absolute inset-0 z-0">
                <img class="w-full h-full object-cover opacity-25 grayscale blur-md scale-110"
                     src="{{ $heroBg }}" alt="">
                <div class="profile-hero-grad absolute inset-0"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0 bg-surface-container-low"></div>
        @endif

        {{-- Content --}}
        <div class="relative z-10 px-8 md:px-12 pt-32 pb-16 max-w-[1400px] mx-auto">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-8 md:gap-10">

                {{-- Avatar block --}}
                <div class="relative shrink-0">
                    <div class="w-40 h-40 md:w-48 md:h-48 rounded-2xl overflow-hidden border-4 border-surface shadow-2xl shadow-black">
                        @if ($user->avatar)
                            <img class="w-full h-full object-cover" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                        @else
                            <div class="w-full h-full bg-primary/20 flex items-center justify-center">
                                <span class="font-headline font-black text-6xl text-primary">{{ $user->initials() }}</span>
                            </div>
                        @endif
                    </div>
                    {{-- Procurator badge --}}
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap gradient-button-profile px-5 py-1 rounded-full text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl">
                        {{ __('Procurator') }}
                    </div>
                </div>

                {{-- Name + Meta --}}
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl md:text-6xl font-black font-headline tracking-tighter mb-3 text-on-surface">
                        {{ $user->name }}
                    </h1>
                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-4 text-on-surface-variant text-sm font-label mb-5">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base">calendar_today</span>
                            {{ __('Joined') }} {{ $user->created_at->format('M Y') }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-outline-variant hidden md:block"></span>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-base">location_on</span>
                            {{ __('Digital Archive') }}
                        </span>
                    </div>
                    <p class="text-base md:text-lg text-on-surface-variant italic opacity-80 max-w-xl mx-auto md:mx-0">
                        "{{ __('The world is a gallery, and we are but curators of the moments that move us.') }}"
                    </p>
                </div>

                {{-- Edit button --}}
                <div class="shrink-0 pb-1">
                    <button type="button" data-open-profile-modal
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-outline-variant hover:bg-surface-container-highest hover:border-primary/40 transition-all duration-300 font-label font-bold text-sm group">
                        <span class="material-symbols-outlined text-[18px] group-hover:text-primary transition-colors">edit</span>
                        {{ __('Edit Profile') }}
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- ┌─────────────────────────────────────────────────────┐ --}}
    {{--   SUCCESS FLASH                                        --}}
    {{-- └─────────────────────────────────────────────────────┘ --}}
    @if (session('success'))
        <div id="profileFlash"
             class="px-6 md:px-12 max-w-[1400px] mx-auto mt-6">
            <div class="flex items-center gap-3 px-6 py-4 bg-secondary/10 border border-secondary/25 rounded-2xl text-sm font-label">
                <span class="material-symbols-outlined text-secondary text-[20px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span class="text-on-surface font-bold">{{ session('success') }}</span>
                <button onclick="document.getElementById('profileFlash').remove()"
                        class="ml-auto text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        </div>
    @endif

    {{-- ┌─────────────────────────────────────────────────────┐ --}}
    {{--   STATS STRIP                                          --}}
    {{-- └─────────────────────────────────────────────────────┘ --}}
    <div class="px-6 md:px-12 max-w-[1400px] mx-auto mt-8 md:-mt-2">
        <div class="grid grid-cols-2 mt-2 md:grid-cols-4 gap-4">
            <div class="bg-surface-container p-8 rounded-xl flex flex-col gap-2 hover:bg-surface-container-high transition-all">
                <span class="text-on-surface-variant font-label text-sm uppercase tracking-widest">{{ __('Total Media') }}</span>
                <span class="text-4xl md:text-5xl font-black font-headline text-primary mt-1">{{ $totalMedia }}</span>
                <div class="h-0.5 w-8 bg-primary/40 rounded-full"></div>
            </div>
            <div class="bg-surface-container p-8 rounded-xl flex flex-col gap-2 hover:bg-surface-container-high transition-all">
                <span class="text-on-surface-variant font-label text-sm uppercase tracking-widest">{{ __('Mean Score') }}</span>
                <span class="text-4xl md:text-5xl font-black font-headline text-secondary mt-1">{{ $meanScore ? number_format($meanScore, 1) : '—' }}</span>
                <div class="h-0.5 w-8 bg-secondary/40 rounded-full"></div>
            </div>
            <div class="bg-surface-container p-8 rounded-xl flex flex-col gap-2 hover:bg-surface-container-high transition-all">
                <span class="text-on-surface-variant font-label text-sm uppercase tracking-widest">{{ __('Days Spent') }}</span>
                <span class="text-4xl md:text-5xl font-black font-headline text-tertiary mt-1">{{ $daysSpent }}</span>
                <div class="h-0.5 w-8 bg-tertiary/40 rounded-full"></div>
            </div>
            <div class="bg-surface-container p-8 rounded-xl flex flex-col gap-2 hover:bg-surface-container-high transition-all">
                <span class="text-on-surface-variant font-label text-sm uppercase tracking-widest">{{ __('Completed') }}</span>
                <span class="text-4xl md:text-5xl font-black font-headline text-on-surface mt-1">{{ $completed }}</span>
                <div class="h-0.5 w-8 bg-outline-variant rounded-full"></div>
            </div>
        </div>
    </div>

    {{-- ┌─────────────────────────────────────────────────────┐ --}}
    {{--   MAIN CONTENT                                         --}}
    {{-- └─────────────────────────────────────────────────────┘ --}}
    <div class="px-6 md:px-12 max-w-[1400px] mx-auto mt-14 pb-24 space-y-16">

        {{-- Distribution + Activity Row --}}
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">

            {{-- Collection Distribution --}}
            <div class="bg-surface-container-low rounded-2xl p-8 border border-outline-variant/10 flex flex-col gap-0">
                <h3 class="text-xl font-black font-headline tracking-tight mb-8">{{ __('Collection Distribution') }}</h3>
                <div class="space-y-7 flex-1">
                    @foreach ($distribution as $type => $data)
                    <div>
                        <div class="flex justify-between font-label text-[11px] font-black uppercase tracking-widest mb-2">
                            <span class="text-on-surface-variant">{{ ucfirst($type) }}</span>
                            <span class="text-{{ $data['color'] }}">{{ $data['percent'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-surface-container-highest rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $data['color'] }} rounded-full transition-all duration-700"
                                 style="width: {{ $data['percent'] }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-10 p-5 border border-dashed border-outline-variant/30 rounded-xl text-on-surface-variant font-label text-[11px] text-center leading-loose">
                    {{ __('Active tracking since') }}<br>
                    <span class="font-black text-on-surface">{{ $user->created_at->format('F Y') }}</span>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black font-headline tracking-tight">{{ __('Recent Activity') }}</h3>
                    <a href="{{ route('my-list') }}" class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest">{{ __('View all') }}</a>
                </div>
                <div class="space-y-3">
                    @forelse ($recentActivity as $entry)
                        @php
                            $isAnime   = $entry->type->usesEpisodes();
                            $color     = $isAnime ? 'primary' : 'secondary';
                            $icon      = match($entry->status->value) {
                                'completed'          => 'check_circle',
                                'dropped'            => 'cancel',
                                'on_hold', 'paused'  => 'pause_circle',
                                default              => 'update',
                            };
                            $fillStyle = in_array($entry->status->value, ['completed', 'dropped']) ? "font-variation-settings: 'FILL' 1;" : '';
                            $currentVal = $isAnime ? ($entry->current_episode ?? 0) : ($entry->current_chapter ?? 0);
                            $unit       = $isAnime ? 'Ep' : 'Ch';
                        @endphp
                        <div class="bg-surface-container rounded-2xl p-5 flex items-center gap-5 hover:translate-x-2 transition-transform duration-300 cursor-pointer border border-outline-variant/5">
                            <div class="w-11 h-16 rounded-xl overflow-hidden shrink-0 shadow-md">
                                @if ($entry->cover_url)
                                    <img class="w-full h-full object-cover" src="{{ $entry->cover_url }}" alt="{{ $entry->title }}" />
                                @else
                                    <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm text-on-surface-variant/40">image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="material-symbols-outlined text-{{ $color }} text-[18px]"
                                          style="{{ $fillStyle }}">{{ $icon }}</span>
                                    <p class="text-sm text-on-surface-variant truncate">
                                        <span class="text-on-surface font-bold">{{ $entry->title }}</span>
                                        <span class="mx-0.5">·</span>
                                        {{ $entry->status->label() }}
                                        @if($currentVal > 0) <span class="mx-0.5">·</span> {{ $unit }}. {{ $currentVal }} @endif
                                    </p>
                                </div>
                                <span class="text-[10px] text-outline font-bold uppercase tracking-widest">{{ $entry->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-14 bg-surface-container rounded-2xl text-center border border-dashed border-outline-variant/20">
                            <p class="text-on-surface-variant font-label text-sm">{{ __('No recent activity to show.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Curation Hall --}}
        <section>
            <div class="flex items-baseline justify-between mb-8">
                <h3 class="text-3xl font-black font-headline tracking-tight">{{ __('Curation Hall') }}</h3>
                <a class="text-[10px] font-black text-primary hover:underline uppercase tracking-widest" href="{{ route('my-list') }}">{{ __('View all favorites') }}</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8">
                @forelse ($favorites as $item)
                <div class="group cursor-pointer">
                    <div class="aspect-[2/3] rounded-2xl overflow-hidden relative shadow-xl transform transition-all duration-500 group-hover:-translate-y-2 group-hover:shadow-primary/10">
                        @if ($item->cover_url)
                            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 src="{{ $item->cover_url }}" alt="{{ $item->title }}" />
                        @else
                            <div class="w-full h-full bg-surface-container-highest flex items-center justify-center">
                                <span class="material-symbols-outlined text-5xl text-on-surface-variant/20">image</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-4">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-primary">★ {{ $item->rating }}/5</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-white/60 mt-0.5">{{ $item->status->label() }}</span>
                        </div>
                    </div>
                    <h4 class="font-black font-headline text-sm leading-tight mt-4 mb-1 truncate group-hover:text-primary transition-colors">{{ $item->title }}</h4>
                    <p class="text-on-surface-variant text-[10px] font-bold uppercase tracking-widest">{{ $item->type->label() }}</p>
                </div>
                @empty
                <div class="col-span-full py-16 bg-surface-container/30 rounded-3xl border border-dashed border-outline-variant/20 text-center">
                    <p class="text-on-surface-variant font-label text-sm">{{ __('Add ratings to your media to showcase them here.') }}</p>
                </div>
                @endforelse
            </div>
        </section>

    </div>
</div>
@endsection
