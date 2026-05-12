import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

export function AppLogo({ href = '/', className }: { href?: string; className?: string }) {
    return (
        <Link href={href} className={cn('flex items-center gap-3 group', className)}>
            <div className="flex aspect-square size-10 items-center justify-center rounded-xl bg-primary shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform duration-300">
                <span className="material-symbols-outlined text-on-primary text-2xl fill">auto_awesome</span>
            </div>
            <div className="flex flex-col">
                <span className="text-lg font-headline font-black leading-none tracking-tight text-on-surface">The Archive</span>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary">Digital Curator</span>
            </div>
        </Link>
    );
}
