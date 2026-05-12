@props([
    'sidebar' => false,
])

<a {{ $attributes->merge(['class' => 'flex items-center gap-3 group']) }}>
    <div class="flex aspect-square size-10 items-center justify-center rounded-xl bg-primary shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform duration-300">
        <x-app-logo-icon class="size-6 fill-current text-on-primary" />
    </div>
    <div class="flex flex-col">
        <span class="text-lg font-headline font-black leading-none tracking-tight text-on-surface">The Archive</span>
        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary">Digital Curator</span>
    </div>
</a>
