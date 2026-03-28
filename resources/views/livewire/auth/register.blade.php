<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Register') }} | {{ config('app.name', 'Bibliotaku') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;400;500;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-on-surface font-body min-h-screen flex items-center justify-center p-4 selection:bg-primary/30">
    <main class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-0 overflow-hidden rounded-xl shadow-premium bg-surface-container-low">

        <!-- Left Side: Visual/Branding -->
        <section class="hidden lg:flex relative flex-col justify-between p-12 overflow-hidden bg-surface">
            <div class="absolute inset-0 opacity-40">
                <img class="w-full h-full object-cover" alt="Cinematic Branding"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBzWZ7MG2c9CkKC42G5iW8JHtnlX6PzO7IZ52Zj29C1OGqUGcYozMGbZArjvJFizJrf3bJ37aGAg9roReOQEQ-LHgY3TjYKZzDdoHnekkht4I2-HIcU4TVWo30kCEOfS8AdZ9gp70sSAAjmY0o1TUyh2dcCbNAwJ6Q2sUnD2q-y46WhaIRniVd0ES0ITFeF9DCDsKCWhDwN10gqDrZJMIa7bt0voQQ8CpL0FxG-nOv90XEXVUEeBW96RTBXXQrWjum-j23px7Rps4c" />
                <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-background/20"></div>
            </div>
            <div class="relative z-10">
                <a href="{{ route('home') }}"
                    class="font-headline font-black text-4xl tracking-tighter text-primary text-glow hover:scale-105 transition-transform inline-block">
                    The Archive
                </a>
                <p class="font-headline text-on-surface-variant mt-4 max-w-xs text-lg leading-relaxed">
                    {{ __('Digital Curation for the Modern Collector.') }}
                </p>
            </div>
            <div class="relative z-10">
                <blockquote class="border-l-2 border-primary pl-6 space-y-4">
                    <p class="text-xl font-headline font-light italic text-on-surface leading-snug">
                        "The world is a gallery, and your collection is the most important exhibit."
                    </p>
                    <cite class="block text-sm font-label uppercase tracking-widest text-primary-dim">Digital Curator</cite>
                </blockquote>
            </div>
        </section>

        <!-- Right Side: Registration Form -->
        <section class="flex flex-col justify-center p-8 lg:p-16 bg-surface-container">
            <div class="max-w-md w-full mx-auto space-y-8">
                <header class="space-y-2">
                    <h2 class="font-headline text-3xl font-extrabold tracking-tight">{{ __('Create your account') }}</h2>
                    <p class="text-on-surface-variant font-body">{{ __('Join the elite circle of curators today.') }}</p>
                </header>

                <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="block text-sm font-label font-medium text-on-surface-variant px-1" for="name">{{ __('Full Name') }}</label>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg transition-colors group-focus-within:text-primary">person</span>
                            <input
                                class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 @error('name') ring-error @else focus:ring-primary/50 @enderror transition-all outline-none"
                                id="name" name="name" value="{{ old('name') }}" placeholder="Alexander Curator" type="text"
                                required autofocus />
                        </div>
                        @error('name')
                            <p class="text-error text-xs mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label class="block text-sm font-label font-medium text-on-surface-variant px-1"
                            for="email">{{ __('Email Address') }}</label>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg transition-colors group-focus-within:text-primary">mail</span>
                            <input
                                class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 @error('email') ring-error @else focus:ring-primary/50 @enderror transition-all outline-none"
                                id="email" name="email" value="{{ old('email') }}" placeholder="curator@archive.com"
                                type="email" required />
                        </div>
                        @error('email')
                            <p class="text-error text-xs mt-1 px-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-label font-medium text-on-surface-variant px-1"
                                for="password">{{ __('Password') }}</label>
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg transition-colors group-focus-within:text-primary">lock</span>
                                <input
                                    class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 @error('password') ring-error @else focus:ring-primary/50 @enderror transition-all outline-none"
                                    id="password" name="password" placeholder="••••••••" type="password" required />
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-label font-medium text-on-surface-variant px-1"
                                for="password_confirmation">{{ __('Confirm') }}</label>
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg transition-colors group-focus-within:text-primary">enhanced_encryption</span>
                                <input
                                    class="w-full bg-surface-container-low border-none rounded-xl py-4 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 transition-all outline-none"
                                    id="password_confirmation" name="password_confirmation" placeholder="••••••••" type="password"
                                    required />
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-error text-xs mt-1 px-1">{{ $message }}</p>
                    @enderror

                    <div class="pt-4">
                        <button
                            class="w-full hero-gradient text-on-primary font-headline font-bold py-4 rounded-full shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all duration-300"
                            type="submit">
                            {{ __('Create Account') }}
                        </button>
                    </div>
                </form>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-outline-variant/30"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase tracking-widest font-label">
                        <span class="bg-surface-container px-4 text-outline">{{ __('Or continue with') }}</span></div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="flex items-center justify-center gap-3 bg-surface-container-low hover:bg-surface-container-high py-3 rounded-xl transition-colors group border border-outline-variant/10 cursor-pointer">
                        <svg class="w-5 h-5 fill-on-surface-variant group-hover:fill-on-surface transition-colors"
                            viewBox="0 0 24 24">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z">
                            </path>
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z">
                            </path>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z">
                            </path>
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">Google</span>
                    </button>
                    <button
                        class="flex items-center justify-center gap-3 bg-surface-container-low hover:bg-surface-container-high py-3 rounded-xl transition-colors group border border-outline-variant/10 cursor-pointer">
                        <svg class="w-5 h-5 fill-on-surface-variant group-hover:fill-on-surface transition-colors"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.477 2 2 6.477 2 12c0 4.419 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.604-3.369-1.341-3.369-1.341-.454-1.152-1.11-1.459-1.11-1.459-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.087.635-1.337-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482C19.138 20.161 22 16.416 22 12c0-5.523-4.477-10-10-10z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">GitHub</span>
                    </button>
                </div>

                <footer class="text-center pt-4">
                    <p class="text-on-surface-variant text-sm">
                        {{ __('Already have an account?') }}
                        <a class="text-primary font-bold hover:text-primary-dim transition-colors ml-1"
                            href="{{ route('login') }}" wire:navigate>{{ __('Sign in') }}</a>
                    </p>
                </footer>
            </div>
        </section>
    </main>

    <!-- Contextual Footer -->
    <div
        class="fixed bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-6 opacity-40 hover:opacity-100 transition-opacity">
        <a class="text-[10px] uppercase tracking-[0.2em] font-label text-on-surface-variant hover:text-primary"
            href="#">{{ __('About') }}</a>
        <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
        <a class="text-[10px] uppercase tracking-[0.2em] font-label text-on-surface-variant hover:text-primary"
            href="#">{{ __('Privacy') }}</a>
        <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
        <a class="text-[10px] uppercase tracking-[0.2em] font-label text-on-surface-variant hover:text-primary"
            href="#">{{ __('Terms') }}</a>
    </div>
</body>

</html>
