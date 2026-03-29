@extends('layouts.app')

@section('title', config('app.name', 'The Archive') . ' — ' . __('My List'))

@section('content')
    <section class="max-w-7xl mx-auto mt-8">

        {{-- Flash Success --}}
        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 px-6 py-4 bg-secondary/10 border border-secondary/20 rounded-xl text-secondary text-sm font-medium">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Livewire Media List --}}
        <livewire:media-list />

    </section>
@endsection
