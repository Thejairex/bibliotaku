<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-6 pb-8 pt-4 bg-neutral-950/80 backdrop-blur-xl rounded-t-xl z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.6)] font-label text-[10px] uppercase tracking-widest">
    <a class="flex flex-col items-center justify-center transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-primary/20 text-primary rounded-full px-6 py-2' : 'text-on-surface-variant' }} active:scale-95"
        href="{{ route('dashboard') }}">
        <span class="material-symbols-outlined" {{ request()->routeIs('dashboard') ? 'style="font-variation-settings:\'FILL\' 1;"' : '' }}>home</span>
        <span class="mt-1">{{ __('Home') }}</span>
    </a>
    <a class="flex flex-col items-center justify-center transition-all duration-300 {{ request()->routeIs('my-list') ? 'bg-primary/20 text-primary rounded-full px-6 py-2' : 'text-on-surface-variant' }} active:scale-95"
        href="{{ route('my-list') }}">
        <span class="material-symbols-outlined" {{ request()->routeIs('my-list') ? 'style="font-variation-settings:\'FILL\' 1;"' : '' }}>library_books</span>
        <span class="mt-1">{{ __('List') }}</span>
    </a>
    <button id="openAddModalMobile" class="flex flex-col items-center justify-center text-primary active:scale-95 transition-transform">
        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">add_circle</span>
        <span class="mt-1">{{ __('Add') }}</span>
    </button>
    <a class="flex flex-col items-center justify-center transition-all duration-300 {{ request()->routeIs('profile.edit') ? 'bg-primary/20 text-primary rounded-full px-6 py-2' : 'text-on-surface-variant' }} active:scale-95"
        href="{{ route('profile.edit') }}">
        <span class="material-symbols-outlined" {{ request()->routeIs('profile.edit') ? 'style="font-variation-settings:\'FILL\' 1;"' : '' }}>person</span>
        <span class="mt-1">{{ __('Profile') }}</span>
    </a>
</nav>

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
