import { useSearchStore } from '@/stores/useSearchStore';
import { cn } from '@/lib/utils';

export function SearchBar() {
    const { query, setQuery, loading, mode } = useSearchStore();

    return (
        <div className="relative group max-w-2xl">
            <span className={cn(
                "material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-2xl transition-all duration-300 z-10",
                mode === 'local' ? 'text-primary' : 'text-secondary',
                "opacity-40 group-focus-within:opacity-100"
            )}>
                search
            </span>
            
            <input
                type="text"
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                autoFocus
                placeholder={mode === 'local' ? 'Search your collection...' : 'Discover on MyAnimeList...'}
                className={cn(
                    "w-full bg-background/60 backdrop-blur-xl border border-outline-variant/20 rounded-[1.5rem] py-5 pl-16 pr-16 text-lg transition-all outline-none",
                    "text-on-surface placeholder:text-on-surface-variant/50",
                    mode === 'local' 
                        ? "focus:border-primary/40 focus:ring-4 focus:ring-primary/5 focus:shadow-[0_0_40px_-15px_rgba(186,158,255,0.3)]"
                        : "focus:border-secondary/40 focus:ring-4 focus:ring-secondary/5 focus:shadow-[0_0_40px_-15px_rgba(144,147,255,0.3)]"
                )}
            />

            {/* Spinner / Clear */}
            <div className="absolute right-6 top-1/2 -translate-y-1/2 flex items-center gap-3">
                {loading && (
                    <div className={cn(
                        "size-5 border-2 rounded-full animate-spin",
                        mode === 'local' ? "border-primary/20 border-t-primary" : "border-secondary/20 border-t-secondary"
                    )} />
                )}
                
                {query && !loading && (
                    <button 
                        onClick={() => setQuery('')}
                        className="text-on-surface-variant hover:text-on-surface transition-colors"
                    >
                        <span className="material-symbols-outlined text-xl">close</span>
                    </button>
                )}
            </div>
        </div>
    );
}
