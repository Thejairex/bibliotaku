<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Log In') }} | {{ config('app.name', 'Bibliotaku') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-cinematic {
            background-image: linear-gradient(rgba(14, 14, 14, 0.85), rgba(14, 14, 14, 0.95)),
                url(https://lh3.googleusercontent.com/aida-public/AB6AXuCyVTL18CYremyiw3fCOIwoqaGFbXe9ns6uwIuEZDOkBgAt1-ZMnqR5Ij7PdWUnvykzrCjWRaZhNVs3NhIVfXgDFmM_FLCKQ2ma9fTS1GqA_URxso2uek9wDCv206QOwWZNXGSrth9xYXVvlY2jtoEtq0IvAtMeHa8uw0YxIaVK8EF2zaUcZB9c9XYCZbaz6ZKcO65UcGeU2nxOqGI6vHQPEnVtlYIT823NU8oaM_VP5cxmz2d3LY8ipKgyBjS0LbBLrs40CyXO5p0);
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-background text-on-surface font-body min-h-screen flex flex-col">

    <!-- Background Layer -->
    <div class="fixed inset-0 bg-cinematic z-0"></div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="relative z-10 max-w-md mx-auto mt-4 px-6">
            <p class="text-sm text-primary bg-primary/10 border border-primary/20 rounded-xl px-4 py-3">
                {{ session('status') }}
            </p>
        </div>
    @endif

    <!-- Main Content Container -->
    <main class="relative z-10 flex-grow flex items-center justify-center px-6 py-12">

        <!-- Login Card -->
        <div class="w-full max-w-md bg-surface-container/70 backdrop-blur-2xl rounded-xl p-10 md:p-14 shadow-2xl shadow-black/60 flex flex-col gap-10">

            <!-- Brand Header -->
            <div class="text-center">
                <a href="{{ route('home') }}" wire:navigate
                    class="font-headline font-black text-4xl tracking-tighter text-primary hover:scale-105 transition-transform inline-block mb-2">
                    The Archive
                </a>
                <p class="font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant font-medium">
                    {{ __('Digital Curator') }}
                </p>
            </div>

            <!-- Form Section -->
            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                @csrf

                <!-- Email / Username -->
                <div class="flex flex-col gap-2">
                    <label class="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" for="email">
                        {{ __('Email / Username') }}
                    </label>
                    <div class="relative group">
                        <input
                            class="w-full bg-surface-container-low border-none rounded-full px-6 py-4 text-on-surface placeholder:text-outline focus:ring-2 @error('email') ring-error @else focus:ring-primary/40 @enderror transition-all outline-none"
                            id="email" name="email" value="{{ old('email') }}"
                            placeholder="{{ __('Enter your credentials') }}" type="email"
                            required autofocus autocomplete="email" />
                    </div>
                    @error('email')
                        <p class="text-error text-xs mt-1 px-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider" for="password">
                            {{ __('Password') }}
                        </label>
                        @if (Route::has('password.request'))
                            <a class="font-label text-[10px] uppercase font-bold text-primary hover:text-primary-dim transition-colors tracking-widest"
                                href="{{ route('password.request') }}" wire:navigate>
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <input
                            class="w-full bg-surface-container-low border-none rounded-full px-6 py-4 text-on-surface placeholder:text-outline focus:ring-2 @error('password') ring-error @else focus:ring-primary/40 @enderror transition-all outline-none"
                            id="password" name="password" placeholder="••••••••"
                            type="password" required autocomplete="current-password" />
                    </div>
                    @error('password')
                        <p class="text-error text-xs mt-1 px-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <label class="flex items-center gap-3 px-1 cursor-pointer group">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded accent-primary cursor-pointer"
                        {{ old('remember') ? 'checked' : '' }} />
                    <span class="font-label text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">
                        {{ __('Remember me') }}
                    </span>
                </label>

                <!-- Sign In Button -->
                <button
                    class="mt-4 w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-headline font-bold py-4 rounded-full shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all flex items-center justify-center gap-2 group"
                    type="submit">
                    <span>{{ __('Sign In') }}</span>
                    <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-outline-variant/20"></div>
                <span class="flex-shrink mx-4 text-xs font-bold text-outline uppercase tracking-widest">OR</span>
                <div class="flex-grow border-t border-outline-variant/20"></div>
            </div>

            <!-- Alternate Action -->
            @if (Route::has('register'))
                <div class="text-center -mt-4">
                    <p class="text-on-surface-variant font-label text-sm">
                        {{ __("Don't have an account?") }}
                        <a class="text-secondary font-bold hover:text-primary transition-colors ml-1"
                            href="{{ route('register') }}" wire:navigate>
                            {{ __('Sign up instead') }}
                        </a>
                    </p>
                </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full py-8 px-8 border-t border-outline-variant/10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant/60">
                © {{ date('Y') }} {{ config('app.name', 'The Archive') }}. {{ __('Digital Curation for the Modern Collector.') }}
            </p>
            <div class="flex gap-6">
                <a class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors" href="#">{{ __('Privacy') }}</a>
                <a class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors" href="#">{{ __('Terms') }}</a>
                <a class="font-label text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-primary transition-colors" href="#">{{ __('API') }}</a>
            </div>
        </div>
    </footer>

</body>

</html>
