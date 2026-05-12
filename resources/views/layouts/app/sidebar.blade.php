<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-on-surface font-body antialiased selection:bg-primary/30">
        <div class="flex min-h-screen relative">
            {{-- Navigation Sidebar --}}
            <aside class="fixed inset-y-0 left-0 w-72 bg-surface-container border-r border-outline-variant/10 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto custom-scrollbar">
                <div class="flex flex-col h-full p-6">
                    {{-- Header / Logo --}}
                    <div class="mb-12">
                        <x-app-logo href="{{ route('dashboard') }}" wire:navigate />
                    </div>

                    {{-- Nav Groups --}}
                    <nav class="flex-1 space-y-8">
                        <div>
                            <span class="px-4 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 mb-4 block">
                                {{ __('Platform') }}
                            </span>
                            <div class="space-y-1">
                                <a href="{{ route('dashboard') }}" wire:navigate 
                                    @class([
                                        'flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all group',
                                        'bg-primary/10 text-primary' => request()->routeIs('dashboard'),
                                        'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' => !request()->routeIs('dashboard')
                                    ])>
                                    <span class="material-symbols-outlined text-[22px]">home</span>
                                    <span class="text-sm">{{ __('Home') }}</span>
                                </a>
                                
                                <a href="{{ route('search') }}" wire:navigate 
                                    @class([
                                        'flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all group',
                                        'bg-primary/10 text-primary' => request()->routeIs('search'),
                                        'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' => !request()->routeIs('search')
                                    ])>
                                    <span class="material-symbols-outlined text-[22px]">search</span>
                                    <span class="text-sm">{{ __('Search') }}</span>
                                </a>
                                
                                <a href="{{ route('my-list.index') }}" wire:navigate 
                                    @class([
                                        'flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all group',
                                        'bg-primary/10 text-primary' => request()->routeIs('my-list.*'),
                                        'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' => !request()->routeIs('my-list.*')
                                    ])>
                                    <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                                    <span class="text-sm">{{ __('My List') }}</span>
                                </a>
                            </div>
                        </div>

                        <div>
                            <span class="px-4 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 mb-4 block">
                                {{ __('Account') }}
                            </span>
                            <div class="space-y-1">
                                <a href="{{ route('profile.edit') }}" wire:navigate 
                                    @class([
                                        'flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all group',
                                        'bg-primary/10 text-primary' => request()->routeIs('profile.edit'),
                                        'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface' => !request()->routeIs('profile.edit')
                                    ])>
                                    <span class="material-symbols-outlined text-[22px]">person</span>
                                    <span class="text-sm">{{ __('Profile') }}</span>
                                </a>
                            </div>
                        </div>
                    </nav>

                    {{-- Footer --}}
                    <div class="mt-auto pt-6 border-t border-outline-variant/5">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 rounded-2xl font-bold text-error/70 hover:bg-error/10 hover:text-error transition-all group">
                                <span class="material-symbols-outlined text-[22px]">logout</span>
                                <span class="text-sm">{{ __('Log Out') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 lg:ml-72 min-h-screen p-6 md:p-12">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
