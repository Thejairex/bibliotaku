<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bibliotaku') }} | {{ __('Curate Your Imagination') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-body selection:bg-primary/30 bg-background text-on-surface">
    <!-- TopNavBar -->
    <nav class="fixed top-0 w-full z-40 glass-header flex justify-between items-center px-8 h-20 font-headline font-medium">
        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-xl font-black text-primary tracking-tighter hover:scale-105 transition-transform">
                The Archive
            </a>
            <div class="hidden md:flex items-center gap-6">
                <a class="text-primary hover:text-primary/80 transition-colors" href="{{ route('home') }}">Home</a>
                <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">Explore</a>
                <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">Community</a>
            </div>
        </div>

        <div class="flex items-center gap-6">
            @auth
                <!-- Authenticated View -->
                <div class="relative group hidden sm:block">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-on-surface-variant text-sm">search</span>
                    </div>
                    <input
                        class="bg-surface-container/50 border-none rounded-full pl-10 pr-4 py-2 text-sm w-64 focus:ring-2 focus:ring-primary/50 transition-all outline-none"
                        placeholder="Search the collection..." type="text" />
                </div>
                <div class="flex items-center gap-4">
                    <button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">notifications</button>
                    <button class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors">grid_view</button>
                    <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center overflow-hidden border border-outline-variant/20 hover:border-primary/50 transition-all">
                        <img alt="{{ auth()->user()->name }}" class="w-full h-full object-cover"
                            src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" />
                    </a>
                </div>
            @else
                <!-- Guest View -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('login') }}" class="text-on-surface-variant hover:text-on-surface font-label font-bold text-sm px-4 py-2 transition-colors">
                        Log In
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="gradient-cta px-6 py-2.5 rounded-full font-label font-bold text-on-primary text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95">
                            Get Started
                        </a>
                    @endif
                </div>
            @endauth
        </div>
    </nav>

    <main class="pt-20">
        <!-- Cinematic Hero Section -->
        <section class="relative min-h-[921px] flex items-center px-8 md:px-24 overflow-hidden">
            <!-- Background Art -->
            <div class="absolute inset-0 z-0">
                <img alt="Cinematic Anime Art" class="w-full h-full object-cover opacity-40"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCHe8dSQYPRNxsj2bSXJ-cIS9Z5nb3geNw8CEGNVpaotqyv6Vq6Ps-sXrk9wS4ZU3tHopdVUUQLHyVX8m9_k_sNoU7k189pR6_g5xzDXxa7Jr1fF0laEtT1wss8oAMcdi3_5bJf9mkp50ddnFPJgTnCx8Q_DF98_LfPrC5sf4X0kvm52OcpfJ9sUQNHMlk9_nMw3L0ATlIF7XZCqk1qIcjimFM-FPzogEF-t1AZV2TO3XWktum9KTogFa3UvmoRkUcxdR09kBBGW2U" />
                <div class="absolute inset-0 bg-gradient-to-r from-background via-background/80 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
            </div>
            <div class="relative z-10 max-w-3xl space-y-8">
                <div class="space-y-4">
                    <span class="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary font-label text-xs font-bold tracking-widest uppercase">Digital Curator v2.0</span>
                    <h1 class="font-headline text-6xl md:text-8xl font-black tracking-tighter leading-none">
                        CURATE YOUR <br />
                        <span class="text-transparent bg-clip-text gradient-cta">IMAGINATION.</span>
                    </h1>
                    <p class="text-on-surface-variant text-lg md:text-xl font-body max-w-xl leading-relaxed">
                        Experience your anime and manga collection through a high-editorial lens. The Archive turns
                        tracking into a gallery-grade experience.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="gradient-cta px-10 py-5 rounded-full font-label font-bold text-on-primary shadow-xl shadow-primary/20 hover:scale-105 transition-transform active:scale-95 text-center">
                            Go to My Archive
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="gradient-cta px-10 py-5 rounded-full font-label font-bold text-on-primary shadow-xl shadow-primary/20 hover:scale-105 transition-transform active:scale-95 text-center">
                            Start Your Journey
                        </a>
                        <a href="#" class="bg-surface-container-high hover:bg-surface-container-highest px-10 py-5 rounded-full font-label font-bold text-on-surface transition-all active:scale-95 text-center">
                            Explore Public Archives
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Features Bento Grid -->
        <section class="px-8 md:px-24 py-32 space-y-16">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-2">
                    <span class="text-primary font-label text-sm font-bold tracking-widest uppercase">Key Features</span>
                    <h2 class="font-headline text-4xl md:text-5xl font-bold tracking-tight">The Modern Standard.</h2>
                </div>
                <p class="text-on-surface-variant max-w-sm text-sm">
                    Designed for collectors who value aesthetics as much as the content. Minimal noise, maximum impact.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-auto md:h-[600px]">
                <!-- Track Your Anime Card -->
                <div class="md:col-span-7 bg-surface-container rounded-xl overflow-hidden group flex flex-col relative">
                    <div class="p-10 space-y-4 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-violet-400">play_circle</span>
                        </div>
                        <h3 class="font-headline text-3xl font-bold">Track Your Anime</h3>
                        <p class="text-on-surface-variant max-w-xs">Automated episode tracking with beautiful visual
                            progress indicators and seasonal calendars.</p>
                    </div>
                    <div class="mt-auto relative w-full h-full overflow-hidden">
                        <img alt="Anime card preview"
                            class="absolute bottom-0 right-0 w-3/4 h-full object-cover rounded-tl-xl transform translate-x-8 translate-y-8 group-hover:translate-x-4 group-hover:translate-y-4 transition-transform duration-500 shadow-2xl"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNTNc9R7elaoRGve5kNypBBuYdmmAkh8cozC-DxCQIc6kX-fSX-T1nrM0LejQph3ucocR6-okHssbsBzk5tOzCFb5mczRxupjnX4XpRSZym0pfgmFpVdg0IM46MHXZ5CrgG_jHjZVcFQKrAOYZhtWo8fY-8wTfBORo02VK1VT6UsJVRWw4vPDvFF2sa6dIA9RIW0VygPIAEpm6e899nvxkzDBYm14gJXJApvxYH8-OSZYp3rvzujksShFc5GVdIMzLFYmESeAf5GY" />
                    </div>
                </div>
                <!-- Manage Reading Card -->
                <div class="md:col-span-5 bg-surface-container-high rounded-xl p-10 flex flex-col justify-between group">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-violet-400">library_books</span>
                        </div>
                        <h3 class="font-headline text-3xl font-bold">Manage Your Reading</h3>
                        <p class="text-on-surface-variant">Your digital shelf for Manga, Manhwa, and Light Novels.
                            Organize by volume, chapter, or status.</p>
                    </div>
                    <div class="mt-8 flex gap-4 overflow-hidden">
                        <div class="w-32 h-44 bg-surface rounded-lg flex-shrink-0 border border-outline-variant/10 shadow-lg overflow-hidden group-hover:-translate-y-2 transition-transform duration-300">
                            <img alt="Manga cover" class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDht_zHJ8LQ6n-WgEETW2hd2OHgS5dLLxenbtHtDls5Z94Qb7l3xEnH0UWIz57Uo1ouzf-m1tdQbKCLdM-DLW8C3Z5lTfcyivtNrOGHGfBL1esJjvzzMsezHhhBLuc-CPot_si7Hr6xTl8vzxuoFUKm17RXlc8iGvUMDprV0a_EWf1BjnT_YvfNzqpESSVtVnOVhG9Gg-kkKPPEJviJ1ftsNoj4wR7bV_9XB6HRJ4YKAjaql5zw2nOLeI-B_7cPR_4UNZ3ynIf4Tmc" />
                        </div>
                        <div class="w-32 h-44 bg-surface rounded-lg flex-shrink-0 border border-outline-variant/10 shadow-lg overflow-hidden group-hover:-translate-y-4 transition-transform duration-500 delay-75">
                            <img alt="Manga cover 2" class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDdy5ceaN508N3Fnsa3aePU_f6Dt4fXE5m1TWgpd_SavyvcATfsms276z5WtTkKf5TfCfi_j-R9RmHqnc7JFrLRQrhns2tGRlReDeBdx46X3oWf2scm6xAQtDhWjguHVvCGS1beZXbzecD0J32knNlszQNkEhBBUkfKQYO_mI_1OUFXhu6AC2JkxWK-gmAPZQGeG7aQ8BzqSG9WxbArQ7v8bFlwzGoq4PJMFjSe-B44UD2CU49I-6f4lGMtFmuTkVfdZU-XLLcYRNo" />
                        </div>
                        <div class="w-32 h-44 bg-surface rounded-lg flex-shrink-0 border border-outline-variant/10 shadow-lg overflow-hidden group-hover:-translate-y-6 transition-transform duration-700 delay-150">
                            <img alt="Manga cover 3" class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDusxXntuGb51rmkLqs7PylmoNADEB-D7DH51f0GRKb4u3YkQDdY6a2wKInX8OSeo7jMLYx88lTOLQYRoSCEIkM4A-aS2CQ8ZnuN3VU2mWOWPNW1mBdA3Ar_9jGGbcFeP8ha7-uBxqPlrsduGdzNjZ4NrIG0Z2P7-1kYEc7KPSadKhFolAe9sz_khs8VUuDrYjQnWjahpwMjfvsOpZeCHOuM7NQYUmjAOqy34Fw-NBMzlVN38FdoS4GQQslNZiixiosNMPK8hc9jHA" />
                        </div>
                    </div>
                </div>
                <!-- Universal Hub -->
                <div class="md:col-span-12 bg-neutral-900/40 rounded-xl p-10 flex flex-col md:flex-row items-center gap-12 group">
                    <div class="flex-1 space-y-6">
                        <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-violet-400">hub</span>
                        </div>
                        <h3 class="font-headline text-4xl font-bold">Universal Hub</h3>
                        <p class="text-on-surface-variant text-lg">Connect with external tracking services seamlessly.
                            Import your existing lists from MyAnimeList or AniList in one click. Experience
                            cross-platform synchronization that actually works.</p>
                        <div class="flex flex-wrap gap-4">
                            <div class="bg-surface-container-highest px-6 py-2 rounded-full text-xs font-bold text-violet-300 border border-violet-500/20">MAL SYNC</div>
                            <div class="bg-surface-container-highest px-6 py-2 rounded-full text-xs font-bold text-violet-300 border border-violet-500/20">ANILIST API</div>
                            <div class="bg-surface-container-highest px-6 py-2 rounded-full text-xs font-bold text-violet-300 border border-violet-500/20">TRAKT CONNECT</div>
                        </div>
                    </div>
                    <div class="flex-1 relative h-64 w-full md:h-full flex items-center justify-center">
                        <div class="absolute w-48 h-48 bg-primary/20 rounded-full blur-[100px] animate-pulse"></div>
                        <span class="material-symbols-outlined text-8xl text-primary transform group-hover:rotate-180 transition-transform duration-1000">sync</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="px-8 md:px-24 py-32 text-center relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px]"></div>
            </div>
            <div class="relative z-10 space-y-8">
                <h2 class="font-headline text-5xl md:text-7xl font-black tracking-tight max-w-4xl mx-auto uppercase">READY TO CURATE YOUR COLLECTION?</h2>
                <p class="text-on-surface-variant text-xl max-w-2xl mx-auto">Join thousands of collectors who have upgraded their digital experience.</p>
                <div class="pt-8">
                    @auth
                        <a href="{{ route('dashboard') }}" class="gradient-cta px-16 py-6 rounded-full font-label font-black text-on-primary text-xl shadow-2xl shadow-primary/40 hover:scale-105 transition-transform active:scale-95 inline-block">
                            Go to My Archive
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="gradient-cta px-16 py-6 rounded-full font-label font-black text-on-primary text-xl shadow-2xl shadow-primary/40 hover:scale-105 transition-transform active:scale-95 inline-block">
                            Start Your Journey
                        </a>
                    @endauth
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="w-full py-12 px-8 mt-auto border-t border-neutral-900 bg-neutral-950">
        <div class="flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto">
            <div class="flex flex-col items-center md:items-start">
                <span class="text-lg font-black text-primary mb-4 tracking-tighter">The Archive</span>
                <p class="font-body text-xs text-on-surface-variant">© {{ date('Y') }} The Archive. Digital Curation for the Modern Collector.</p>
            </div>
            <div class="flex gap-8 mt-8 md:mt-0">
                <a class="font-body text-xs text-on-surface-variant hover:text-primary transition-colors" href="#">About</a>
                <a class="font-body text-xs text-on-surface-variant hover:text-primary transition-colors" href="#">Privacy</a>
                <a class="font-body text-xs text-on-surface-variant hover:text-primary transition-colors" href="#">Terms</a>
                <a class="font-body text-xs text-on-surface-variant hover:text-primary transition-colors" href="#">API</a>
            </div>
        </div>
    </footer>

    <!-- BottomNavBar for Mobile -->
    <nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-6 pb-8 pt-4 bg-neutral-950/80 backdrop-blur-xl rounded-t-[3rem] z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.6)]">
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('home') ? 'bg-primary/20 text-primary' : 'text-on-surface-variant' }} rounded-full px-6 py-2 transition-transform active:scale-95">
            <span class="material-symbols-outlined">home</span>
            <span class="font-label text-[10px] uppercase tracking-widest mt-1">Home</span>
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-primary/20 text-primary' : 'text-on-surface-variant' }} transition-transform active:scale-95 px-6 py-2 rounded-full">
                <span class="material-symbols-outlined">format_list_bulleted</span>
                <span class="font-label text-[10px] uppercase tracking-widest mt-1">List</span>
            </a>
            <div class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform">
                <span class="material-symbols-outlined text-3xl">add_circle</span>
            </div>
            <div class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform">
                <span class="material-symbols-outlined">person</span>
                <span class="font-label text-[10px] uppercase tracking-widest mt-1">Profile</span>
            </div>
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform">
                <span class="material-symbols-outlined">login</span>
                <span class="font-label text-[10px] uppercase tracking-widest mt-1">Login</span>
            </a>
            <a href="{{ route('register') }}" class="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform">
                <span class="material-symbols-outlined">person_add</span>
                <span class="font-label text-[10px] uppercase tracking-widest mt-1">Join</span>
            </a>
        @endauth
    </nav>
</body>

</html>