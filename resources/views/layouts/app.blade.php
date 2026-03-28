<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'The Archive'))</title>

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
        
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .glass-header {
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
    @stack('styles')
</head>

<body class="bg-surface text-on-surface font-body selection:bg-primary/30 min-h-screen">

    {{-- Sidebar (Desktop) --}}
    <x-sidebar />

    {{-- TopBar --}}
    <x-topbar />

    {{-- Main Content --}}
    <main class="pt-24 pb-32 md:pb-12 px-6 md:pl-72 min-h-screen transition-all duration-300">
        @yield('content')
    </main>

    {{-- BottomNav (Mobile) --}}
    <x-bottom-nav />

    {{-- Add Media Modal (Global) --}}
    <x-add-entry-modal />
    <x-edit-entry-modal />
    @if(request()->routeIs('profile'))
        <x-edit-profile-modal />
    @endif

    @livewireScripts
    @stack('scripts')
</body>
</html>
