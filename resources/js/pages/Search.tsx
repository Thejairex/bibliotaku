import AppLayout from '@/layouts/AppLayout';
import { Head, router } from '@inertiajs/react';
import { SearchPageProps } from '@/types/SharedProps';
import { SearchBar } from '@/components/search/SearchBar';
import { SearchModeToggle } from '@/components/search/SearchModeToggle';
import { useSearchStore, SearchMode } from '@/stores/useSearchStore';
import { useEffect } from 'react';
import { MediaGrid } from '@/components/media/MediaGrid';

export default function Search({ query: initialQuery, mode: initialMode }: SearchPageProps) {
    const { query, mode, setQuery, setMode, setResults, setLoading, setSearched, setError } = useSearchStore();

    // Sync URL params to store on first load
    useEffect(() => {
        if (initialQuery) setQuery(initialQuery);
        if (initialMode) setMode(initialMode as SearchMode);
    }, []);

    // Perform search whenever query or mode changes
    useEffect(() => {
        if (query.length < 2) {
            setResults([]);
            setSearched(false);
            return;
        }

        const controller = new AbortController();
        
        const performSearch = async () => {
            setLoading(true);
            setError(null);

            try {
                // Update URL without reloading
                router.get(window.location.pathname, { q: query, mode }, { 
                    preserveState: true, 
                    preserveScroll: true,
                    replace: true 
                });

                if (mode === 'local') {
                    const response = await fetch(`/search/query?q=${encodeURIComponent(query)}&limit=24`, {
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                        signal: controller.signal,
                    });
                    const data = await response.json();
                    setResults(data.results || []);
                } else {
                    // Search via Jikan (MAL)
                    const response = await fetch(`https://api.jikan.moe/v4/anime?q=${query}&limit=24&sfw=true`);
                    const data = await response.json();
                    setResults(data.data.map((item: any) => ({
                        mal_id: item.mal_id,
                        title: item.title,
                        cover_url: item.images?.jpg?.image_url,
                        type: item.type,
                        score: item.score,
                    })));
                }
                setSearched(true);
            } catch (err) {
                setError('Failed to fetch results. Please try again.');
            } finally {
                setLoading(false);
            }
        };

        const timeoutId = setTimeout(performSearch, 500);
        return () => {
            clearTimeout(timeoutId);
            controller.abort();
        };
    }, [query, mode]);

    return (
        <AppLayout>
            <Head title="Search" />

            <div className="space-y-8 md:space-y-12 animate-fade-in overflow-x-hidden">
                {/* Hero Search Section */}
                <div className="relative overflow-hidden rounded-3xl md:rounded-[2.5rem] bg-surface-container border border-outline-variant/5">
                    <div className="relative z-10 px-5 py-10 sm:px-8 md:px-14 md:py-16">
                        <div className="max-w-3xl">
                            <span className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-[11px] font-black uppercase tracking-[0.2em] mb-6">
                                <span className="material-symbols-outlined text-[14px]">travel_explore</span>
                                Global Explorer
                            </span>
                            <h1 className="text-3xl sm:text-5xl md:text-7xl font-headline font-black tracking-tight md:tracking-tighter text-on-surface mb-6 break-words">
                                Find your next <span className="text-primary italic">obsession.</span>
                            </h1>
                            <p className="text-on-surface-variant text-base md:text-xl leading-relaxed mb-10 max-w-2xl font-medium">
                                {mode === 'local'
                                    ? 'Search through your curated digital archive. Instantly filter by title, status or rating.'
                                    : 'Dive into the MyAnimeList database. Discover over 20,000 anime and manga titles.'}
                            </p>

                            <SearchBar />
                        </div>
                    </div>

                    {/* Visual flourish */}
                    <div className="absolute right-0 top-0 w-1/3 h-full bg-gradient-to-l from-primary/10 to-transparent pointer-events-none" />
                    <div className="absolute -right-24 -top-24 size-96 bg-primary/5 blur-[120px] rounded-full pointer-events-none" />
                </div>

                {/* Results Section */}
                <div className="space-y-8">
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6 md:px-4">
                        <div className="min-w-0">
                            <h3 className="text-2xl font-headline font-black text-on-surface tracking-tight">
                                Search Results
                                {query && <span className="text-on-surface-variant font-medium text-base ml-3 italic break-all">"{query}"</span>}
                            </h3>
                        </div>

                        <SearchModeToggle />
                    </div>

                    <MediaGrid />
                </div>
            </div>
        </AppLayout>
    );
}
