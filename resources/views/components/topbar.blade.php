<header class="fixed top-0 w-full z-40 glass-header flex justify-between items-center px-8 h-20 md:pl-72 transition-all duration-500">
    <div class="flex items-center flex-1 max-w-xl">
        <livewire:global-search />
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
