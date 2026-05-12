import { useSearchStore } from '@/stores/useSearchStore';
import { cn } from '@/lib/utils';

export function SearchModeToggle() {
    const { mode, setMode } = useSearchStore();

    return (
        <div className="flex p-1 bg-surface-container rounded-full w-fit border border-outline-variant/5 shadow-inner">
            <button
                onClick={() => setMode('local')}
                className={cn(
                    "px-6 py-2.5 rounded-full text-[11px] font-black uppercase tracking-widest transition-all flex items-center gap-2",
                    mode === 'local'
                        ? "bg-primary text-on-primary shadow-lg shadow-primary/20 scale-[1.02]"
                        : "text-on-surface-variant hover:text-on-surface"
                )}
            >
                <span className="material-symbols-outlined text-[16px]">inventory_2</span>
                Your Archive
            </button>
            <button
                onClick={() => setMode('mal')}
                className={cn(
                    "px-6 py-2.5 rounded-full text-[11px] font-black uppercase tracking-widest transition-all flex items-center gap-2",
                    mode === 'mal'
                        ? "bg-secondary text-on-secondary shadow-lg shadow-secondary/20 scale-[1.02]"
                        : "text-on-surface-variant hover:text-on-surface"
                )}
            >
                <span className="material-symbols-outlined text-[16px]">public</span>
                MyAnimeList
            </button>
        </div>
    );
}
