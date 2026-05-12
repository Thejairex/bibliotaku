import { useSearchStore } from '@/stores/useSearchStore';
import { MediaCard } from './MediaCard';

export function MediaGrid() {
    const { results, searched, query, loading, mode } = useSearchStore();

    if (loading && results.length === 0) {
        return (
            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 px-2">
                {[...Array(12)].map((_, i) => (
                    <div key={i} className="aspect-[2/3] rounded-2xl bg-surface-container-high animate-pulse" />
                ))}
            </div>
        );
    }

    if (searched && results.length === 0) {
        return (
            <div className="py-24 text-center">
                <div className="size-24 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-6">
                    <span className="material-symbols-outlined text-5xl text-on-surface-variant/20">search_off</span>
                </div>
                <h4 className="text-xl font-headline font-black text-on-surface">No results found</h4>
                <p className="text-on-surface-variant mt-2 max-w-md mx-auto">
                    We couldn't find anything matching <span className="text-on-surface italic">"{query}"</span> in {mode === 'local' ? 'your archive' : 'MyAnimeList'}.
                </p>
            </div>
        );
    }

    if (!searched && query.length < 2) {
        return (
            <div className="py-24 text-center flex flex-col items-center gap-6">
                <div className={cn(
                    "size-24 rounded-[2rem] flex items-center justify-center transition-colors duration-500",
                    mode === 'local' ? 'bg-primary/10' : 'bg-secondary/10'
                )}>
                    <span className={cn(
                        "material-symbols-outlined text-5xl",
                        mode === 'local' ? 'text-primary/40' : 'text-secondary/40'
                    )}>
                        {mode === 'local' ? 'travel_explore' : 'explore'}
                    </span>
                </div>
                <h3 className="text-3xl font-headline font-black text-on-surface">
                    {mode === 'local' ? 'Explore Archive' : 'Global Discovery'}
                </h3>
                <p className="text-on-surface-variant max-w-md leading-relaxed">
                    {mode === 'local' 
                        ? 'Instantly find any entry in your collection. Filter by title, type, or status.' 
                        : 'Search the entire MyAnimeList database and add new titles to your archive.'}
                </p>
            </div>
        );
    }

    return (
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 px-2">
            {results.map((item: any, index: number) => (
                <MediaCard key={item.id || item.mal_id || index} item={item} mode={mode} />
            ))}
        </div>
    );
}

function cn(...classes: any[]) {
    return classes.filter(Boolean).join(' ');
}
