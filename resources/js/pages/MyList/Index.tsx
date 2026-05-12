import AppLayout from '@/layouts/AppLayout';
import { Head, router, Link } from '@inertiajs/react';
import { MyListPageProps } from '@/types/SharedProps';
import { MediaEntry, MediaStatus, MediaType } from '@/types/MediaEntry';
import { cn } from '@/lib/utils';
import { useState } from 'react';
import { EditEntryModal } from '@/components/media/EditEntryModal';
import { useToastStore } from '@/stores/useToastStore';

const STATUS_OPTIONS: { value: string; label: string }[] = [
    { value: '', label: 'All Status' },
    { value: MediaStatus.Watching, label: 'Watching' },
    { value: MediaStatus.Rewatching, label: 'Rewatching' },
    { value: MediaStatus.Reading, label: 'Reading' },
    { value: MediaStatus.Completed, label: 'Completed' },
    { value: MediaStatus.OnHold, label: 'On Hold' },
    { value: MediaStatus.Dropped, label: 'Dropped' },
    { value: MediaStatus.PlanToWatch, label: 'Plan to Watch' },
];

const TYPE_OPTIONS: { value: string; label: string }[] = [
    { value: '', label: 'All Types' },
    { value: MediaType.Anime, label: 'Anime' },
    { value: MediaType.Manga, label: 'Manga' },
    { value: MediaType.Manhwa, label: 'Manhwa' },
    { value: MediaType.Manhua, label: 'Manhua' },
    { value: MediaType.Novel, label: 'Novel' },
];

const STATUS_COLORS: Record<string, string> = {
    watching: 'bg-blue-500/20 text-blue-300',
    rewatching: 'bg-indigo-500/20 text-indigo-300',
    reading: 'bg-cyan-500/20 text-cyan-300',
    completed: 'bg-emerald-500/20 text-emerald-300',
    on_hold: 'bg-amber-500/20 text-amber-300',
    dropped: 'bg-red-500/20 text-red-300',
    plan_to_watch: 'bg-zinc-500/20 text-zinc-300',
};

function EntryCard({ entry, onEdit }: { entry: MediaEntry; onEdit: (e: MediaEntry) => void }) {
    const { addToast } = useToastStore();

    function handleDelete() {
        if (!confirm(`Remove "${entry.title}" from your archive?`)) return;
        router.delete(`/my-list/${entry.id}`, {
            preserveScroll: true,
            onSuccess: () => addToast('success', 'Entry removed from your archive.'),
            onError: () => addToast('error', 'Failed to remove entry.'),
        });
    }

    const isAnime = entry.type === MediaType.Anime;
    const progress = isAnime
        ? `Ep. ${entry.current_episode ?? 0}${entry.total_episodes ? `/${entry.total_episodes}` : ''}`
        : `Ch. ${entry.current_chapter ?? 0}${entry.total_chapters ? `/${entry.total_chapters}` : ''}`;

    return (
        <div className="group relative bg-surface-container rounded-2xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl border border-outline-variant/5 flex gap-0">
            {/* Poster */}
            <Link href={`/my-list/${entry.id}`} className="shrink-0 w-20 sm:w-24 aspect-[2/3] relative overflow-hidden block">
                {entry.cover_url ? (
                    <img
                        src={entry.cover_url}
                        alt={entry.title}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        loading="lazy"
                    />
                ) : (
                    <div className="w-full h-full bg-surface-container-highest flex items-center justify-center">
                        <span className="material-symbols-outlined text-2xl text-on-surface-variant/30">image</span>
                    </div>
                )}
            </Link>

            {/* Info */}
            <div className="flex-1 p-4 flex flex-col justify-between min-w-0">
                <div>
                    <div className="flex items-start justify-between gap-2 mb-1.5">
                        <Link href={`/my-list/${entry.id}`} className="font-headline font-bold text-sm text-on-surface hover:text-primary transition-colors truncate block">
                            {entry.title}
                        </Link>
                        <span className={cn('shrink-0 text-[9px] font-black uppercase tracking-[0.15em] px-2 py-1 rounded-lg', STATUS_COLORS[entry.status] ?? 'bg-zinc-500/20 text-zinc-300')}>
                            {entry.status.replace('_', ' ')}
                        </span>
                    </div>
                    {entry.original_title && (
                        <p className="text-[10px] text-on-surface-variant truncate mb-2">{entry.original_title}</p>
                    )}
                    <span className="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.15em]">{entry.type}</span>
                </div>

                <div className="flex items-center justify-between mt-3">
                    <div className="flex items-center gap-3">
                        <span className="text-[11px] text-on-surface-variant font-medium">{progress}</span>
                        {entry.rating && (
                            <div className="flex items-center gap-1 text-primary">
                                <span className="material-symbols-outlined text-[13px]" style={{ fontVariationSettings: "'FILL' 1" }}>star</span>
                                <span className="text-[11px] font-black">{entry.rating}</span>
                            </div>
                        )}
                    </div>

                    {/* Actions — visible on hover */}
                    <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <button
                            onClick={() => onEdit(entry)}
                            className="p-1.5 rounded-lg hover:bg-primary/10 text-on-surface-variant hover:text-primary transition-colors"
                            title="Edit"
                        >
                            <span className="material-symbols-outlined text-[16px]">edit</span>
                        </button>
                        <button
                            onClick={handleDelete}
                            className="p-1.5 rounded-lg hover:bg-red-500/10 text-on-surface-variant hover:text-red-400 transition-colors"
                            title="Remove"
                        >
                            <span className="material-symbols-outlined text-[16px]">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default function MyListIndex({ entries, filters }: MyListPageProps) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [editingEntry, setEditingEntry] = useState<MediaEntry | null>(null);

    function applyFilter(key: string, value: string) {
        router.get('/my-list', { ...filters, [key]: value || undefined, page: undefined }, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }

    function applySearch(e: React.FormEvent) {
        e.preventDefault();
        router.get('/my-list', { ...filters, search: search || undefined, page: undefined }, {
            preserveState: true,
            replace: true,
        });
    }

    function goToPage(page: number) {
        router.get('/my-list', { ...filters, page }, { preserveScroll: true });
    }

    return (
        <AppLayout>
            <Head title="My List" />

            <div className="space-y-8 animate-fade-in">
                {/* Header */}
                <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h1 className="text-4xl font-headline font-black tracking-tighter text-on-surface">
                            My Archive
                        </h1>
                        <p className="text-on-surface-variant mt-1">
                            {entries.total} {entries.total === 1 ? 'entry' : 'entries'} in your collection
                        </p>
                    </div>
                    <Link
                        href="/search"
                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-xl font-bold text-sm hover:bg-primary/90 transition-colors"
                    >
                        <span className="material-symbols-outlined text-[18px]">add</span>
                        Add Entry
                    </Link>
                </div>

                {/* Filters Bar */}
                <div className="flex flex-col sm:flex-row gap-3 flex-wrap">
                    {/* Search */}
                    <form onSubmit={applySearch} className="flex gap-2 flex-1 min-w-[200px]">
                        <div className="relative flex-1">
                            <span className="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant pointer-events-none">search</span>
                            <input
                                type="text"
                                value={search}
                                onChange={e => setSearch(e.target.value)}
                                placeholder="Search your archive..."
                                className="w-full pl-10 pr-4 py-2.5 bg-surface-container rounded-xl text-sm text-on-surface placeholder:text-on-surface-variant/50 outline-none focus:ring-2 focus:ring-primary/30"
                            />
                        </div>
                        <button type="submit" className="px-4 py-2.5 bg-surface-container rounded-xl text-sm text-on-surface hover:bg-surface-container-highest transition-colors">
                            Go
                        </button>
                    </form>

                    {/* Status filter */}
                    <select
                        value={filters.status ?? ''}
                        onChange={e => applyFilter('status', e.target.value)}
                        className="px-4 py-2.5 bg-surface-container rounded-xl text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                    >
                        {STATUS_OPTIONS.map(o => (
                            <option key={o.value} value={o.value}>{o.label}</option>
                        ))}
                    </select>

                    {/* Type filter */}
                    <select
                        value={filters.type ?? ''}
                        onChange={e => applyFilter('type', e.target.value)}
                        className="px-4 py-2.5 bg-surface-container rounded-xl text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                    >
                        {TYPE_OPTIONS.map(o => (
                            <option key={o.value} value={o.value}>{o.label}</option>
                        ))}
                    </select>

                    {/* Clear filters */}
                    {(filters.status || filters.type || filters.search) && (
                        <button
                            onClick={() => router.get('/my-list', {}, { replace: true })}
                            className="px-4 py-2.5 rounded-xl text-sm text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors flex items-center gap-2"
                        >
                            <span className="material-symbols-outlined text-[16px]">close</span>
                            Clear
                        </button>
                    )}
                </div>

                {/* Entry List */}
                {entries.data.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-24 text-center gap-4">
                        <span className="material-symbols-outlined text-6xl text-on-surface-variant/20">inbox</span>
                        <p className="text-on-surface-variant text-lg font-medium">
                            {filters.status || filters.type || filters.search
                                ? 'No entries match your filters.'
                                : 'Your archive is empty. Start by searching for anime or manga.'}
                        </p>
                        {!(filters.status || filters.type || filters.search) && (
                            <Link href="/search" className="text-primary font-bold hover:underline text-sm">
                                Explore now →
                            </Link>
                        )}
                    </div>
                ) : (
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-3">
                        {entries.data.map(entry => (
                            <EntryCard key={entry.id} entry={entry} onEdit={setEditingEntry} />
                        ))}
                    </div>
                )}

                {/* Pagination */}
                {entries.last_page > 1 && (
                    <div className="flex items-center justify-center gap-2 pt-4">
                        <button
                            disabled={entries.current_page === 1}
                            onClick={() => goToPage(entries.current_page - 1)}
                            className="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                        >
                            <span className="material-symbols-outlined text-[20px]">chevron_left</span>
                        </button>

                        {Array.from({ length: entries.last_page }, (_, i) => i + 1)
                            .filter(p => p === 1 || p === entries.last_page || Math.abs(p - entries.current_page) <= 2)
                            .reduce<(number | '...')[]>((acc, p, idx, arr) => {
                                if (idx > 0 && (arr[idx - 1] as number) < p - 1) acc.push('...');
                                acc.push(p);
                                return acc;
                            }, [])
                            .map((p, i) =>
                                p === '...' ? (
                                    <span key={`dots-${i}`} className="px-2 text-on-surface-variant text-sm">…</span>
                                ) : (
                                    <button
                                        key={p}
                                        onClick={() => goToPage(p as number)}
                                        className={cn(
                                            'w-9 h-9 rounded-xl text-sm font-bold transition-colors',
                                            entries.current_page === p
                                                ? 'bg-primary text-on-primary'
                                                : 'text-on-surface-variant hover:bg-surface-container'
                                        )}
                                    >
                                        {p}
                                    </button>
                                )
                            )}

                        <button
                            disabled={entries.current_page === entries.last_page}
                            onClick={() => goToPage(entries.current_page + 1)}
                            className="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
                        >
                            <span className="material-symbols-outlined text-[20px]">chevron_right</span>
                        </button>
                    </div>
                )}
            </div>

            {/* Edit Modal */}
            {editingEntry && (
                <EditEntryModal
                    entry={editingEntry}
                    onClose={() => setEditingEntry(null)}
                />
            )}
        </AppLayout>
    );
}
