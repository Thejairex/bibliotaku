<aside class="hidden md:flex flex-col h-full py-8 px-4 w-64 fixed left-0 top-0 rounded-r-xl bg-neutral-950/70 backdrop-blur-xl shadow-2xl shadow-black/40 z-50">
    <div class="mb-12 px-4">
        <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter text-primary font-headline hover:scale-105 transition-transform inline-block">
            The Archive
        </a>
        <p class="text-xs text-on-surface-variant font-medium tracking-widest uppercase mt-1">{{ __('Digital Curator') }}</p>
    </div>
    <nav class="flex-1 space-y-2">
        <a class="flex items-center gap-4 px-4 py-3 rounded-full transition-all duration-300 font-headline font-bold text-sm tracking-wide 
            {{ request()->routeIs('dashboard') ? 'text-primary border-r-4 border-primary bg-neutral-900/80 shadow-lg shadow-black/20' : 'text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50' }}"
            href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" {{ request()->routeIs('dashboard') ? 'style="font-variation-settings:\'FILL\' 1;"' : '' }}>home</span>
            {{ __('Home') }}
        </a>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full transition-all duration-300 font-headline font-bold text-sm tracking-wide 
            {{ request()->routeIs('my-list') ? 'text-primary border-r-4 border-primary bg-neutral-900/80 shadow-lg shadow-black/20' : 'text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50' }}"
            href="{{ route('my-list') }}">
            <span class="material-symbols-outlined" {{ request()->routeIs('my-list') ? 'style="font-variation-settings:\'FILL\' 1;"' : '' }}>library_books</span>
            {{ __('My List') }}
        </a>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full transition-all duration-300 font-headline font-bold text-sm tracking-wide text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50" href="#">
            <span class="material-symbols-outlined">search</span>
            {{ __('Search') }}
        </a>
        <a class="flex items-center gap-4 px-4 py-3 rounded-full transition-all duration-300 font-headline font-bold text-sm tracking-wide 
            {{ request()->routeIs('profile') ? 'text-primary border-r-4 border-primary bg-neutral-900/80 shadow-lg shadow-black/20' : 'text-on-surface-variant hover:text-on-surface hover:bg-neutral-800/50' }}"
            href="{{ route('profile') }}">
            <span class="material-symbols-outlined" {{ request()->routeIs('profile') ? 'style="font-variation-settings:\'FILL\' 1;"' : '' }}>person</span>
            {{ __('Profile') }}
        </a>
    </nav>
    <div class="mt-auto px-4">
        <a class="flex items-center gap-4 py-3 transition-all duration-300 font-headline font-bold text-sm tracking-wide 
            {{ request()->routeIs('profile.edit') ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface' }}"
            href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined">settings</span>
            {{ __('Settings') }}
        </a>
    </div>
</aside>
