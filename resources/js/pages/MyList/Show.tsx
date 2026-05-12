import AppLayout from '@/layouts/AppLayout';
import { Head, Link, router } from '@inertiajs/react';
import { MediaDetailPageProps } from '@/types/SharedProps';
import { MediaType } from '@/types/MediaEntry';
import { cn } from '@/lib/utils';
import { useState } from 'react';
import { EditEntryModal } from '@/components/media/EditEntryModal';
import { useToastStore } from '@/stores/useToastStore';

const STATUS_COLORS: Record<string, string> = {
    watching: 'bg-blue-500/20 text-blue-300 border-blue-500/20',
    rewatching: 'bg-indigo-500/20 text-indigo-300 border-indigo-500/20',
    reading: 'bg-cyan-500/20 text-cyan-300 border-cyan-500/20',
    completed: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/20',
    on_hold: 'bg-amber-500/20 text-amber-300 border-amber-500/20',
    dropped: 'bg-red-500/20 text-red-300 border-red-500/20',
    plan_to_watch: 'bg-zinc-500/20 text-zinc-300 border-zinc-500/20',
};

function StatBadge({ label, value }: { label: string; value: string | number | null | undefined }) {
    if (value === null || value === undefined || value === '') return null;
    return (
        <div className="flex flex-col items-center gap-1 px-5 py-3 bg-surface-container rounded-2xl">
            <span className="text-xs font-black text-on-surface-variant uppercase tracking-[0.15em]">{label}</span>
            <span className="text-lg font-black text-on-surface">{value}</span>
        </div>
    );
}

export default function Show({ entry, malData }: MediaDetailPageProps & { malData?: any }) {
    const { addToast } = useToastStore();
    const [editing, setEditing] = useState(false);

    const isAnime = entry.type === MediaType.Anime;
    const malUrl = entry.mal_id
        ? `https://myanimelist.net/${isAnime ? 'anime' : 'manga'}/${entry.mal_id}`
        : null;

    const progress = isAnime
        ? `${entry.current_episode ?? 0}${entry.total_episodes ? ` / ${entry.total_episodes}` : ''}`
        : `${entry.current_chapter ?? 0}${entry.total_chapters ? ` / ${entry.total_chapters}` : ''}`;

    const progressLabel = isAnime ? 'Episodes' : 'Chapters';

    function handleDelete() {
        if (!confirm(`Remove "${entry.title}" from your archive?`)) return;
        router.delete(`/my-list/${entry.id}`, {
            onSuccess: () => addToast('success', 'Entry removed.'),
            onError: () => addToast('error', 'Failed to remove entry.'),
        });
    }

    const synopsis = malData?.synopsis ?? entry.notes;

    return (
        <AppLayout>
            <Head title={entry.title} />

            <div className="max-w-4xl mx-auto space-y-8 animate-fade-in">
                {/* Back */}
                <Link href="/my-list" className="inline-flex items-center gap-2 text-on-surface-variant hover:text-on-surface text-sm font-medium transition-colors">
                    <span className="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to My List
                </Link>

                {/* Hero */}
                <div className="relative overflow-hidden rounded-3xl bg-surface-container border border-outline-variant/5">
                    {/* Background blur from cover */}
                    {entry.cover_url && (
                        <div className="absolute inset-0">
                            <img src={entry.cover_url} alt="" className="w-full h-full object-cover blur-3xl scale-110 opacity-10" aria-hidden />
                        </div>
                    )}

                    <div className="relative z-10 p-6 md:p-10 flex flex-col sm:flex-row gap-8">
                        {/* Cover */}
                        <div className="shrink-0 w-36 sm:w-44 self-start">
                            {entry.cover_url ? (
                                <img
                                    src={entry.cover_url}
                                    alt={entry.title}
                                    className="w-full aspect-[2/3] object-cover rounded-2xl shadow-2xl"
                                />
                            ) : (
                                <div className="w-full aspect-[2/3] bg-surface-container-highest rounded-2xl flex items-center justify-center">
                                    <span className="material-symbols-outlined text-4xl text-on-surface-variant/30">image</span>
                                </div>
                            )}
                        </div>

                        {/* Info */}
                        <div className="flex-1 space-y-4">
                            <div>
                                <div className="flex flex-wrap items-center gap-2 mb-2">
                                    <span className={cn(
                                        'text-[9px] font-black uppercase tracking-[0.2em] px-2.5 py-1.5 rounded-lg border',
                                        STATUS_COLORS[entry.status] ?? 'bg-zinc-500/20 text-zinc-300 border-zinc-500/20'
                                    )}>
                                        {entry.status.replace('_', ' ')}
                                    </span>
                                    <span className="text-[9px] font-black uppercase tracking-[0.2em] px-2.5 py-1.5 rounded-lg border border-outline-variant/20 text-on-surface-variant">
                                        {entry.type}
                                    </span>
                                </div>

                                <h1 className="text-3xl md:text-4xl font-headline font-black tracking-tighter text-on-surface leading-tight">
                                    {entry.title}
                                </h1>
                                {entry.original_title && (
                                    <p className="text-on-surface-variant mt-1 text-sm">{entry.original_title}</p>
                                )}
                            </div>

                            {/* Stats row */}
                            <div className="flex flex-wrap gap-3">
                                <StatBadge label={progressLabel} value={progress} />
                                {!isAnime && entry.current_volume !== null && (
                                    <StatBadge
                                        label="Volumes"
                                        value={`${entry.current_volume ?? 0}${entry.total_volumes ? ` / ${entry.total_volumes}` : ''}`}
                                    />
                                )}
                                {entry.rating && (
                                    <div className="flex flex-col items-center gap-1 px-5 py-3 bg-surface-container rounded-2xl">
                                        <span className="text-xs font-black text-on-surface-variant uppercase tracking-[0.15em]">Rating</span>
                                        <div className="flex items-center gap-1 text-primary">
                                            <span className="material-symbols-outlined text-[18px]" style={{ fontVariationSettings: "'FILL' 1" }}>star</span>
                                            <span className="text-lg font-black">{entry.rating}<span className="text-sm text-on-surface-variant">/5</span></span>
                                        </div>
                                    </div>
                                )}
                                {malData?.score && (
                                    <StatBadge label="MAL Score" value={malData.score} />
                                )}
                                {malData?.episodes && (
                                    <StatBadge label="MAL Episodes" value={malData.episodes} />
                                )}
                            </div>

                            {/* Actions */}
                            <div className="flex flex-wrap gap-3 pt-2">
                                <button
                                    onClick={() => setEditing(true)}
                                    className="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary rounded-xl font-bold text-sm hover:bg-primary/90 transition-colors"
                                >
                                    <span className="material-symbols-outlined text-[16px]">edit</span>
                                    Edit Entry
                                </button>
                                {malUrl && (
                                    <a
                                        href={malUrl}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-surface-container-highest text-on-surface rounded-xl font-bold text-sm hover:bg-outline-variant/20 transition-colors"
                                    >
                                        <span className="material-symbols-outlined text-[16px]">open_in_new</span>
                                        View on MAL
                                    </a>
                                )}
                                <button
                                    onClick={handleDelete}
                                    className="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500/10 text-red-400 rounded-xl font-bold text-sm hover:bg-red-500/20 transition-colors"
                                >
                                    <span className="material-symbols-outlined text-[16px]">delete</span>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Synopsis / Notes */}
                {synopsis && (
                    <div className="bg-surface-container rounded-3xl p-6 md:p-8 border border-outline-variant/5 space-y-3">
                        <h2 className="font-headline font-black text-lg text-on-surface tracking-tight">
                            {malData?.synopsis ? 'Synopsis' : 'Notes'}
                        </h2>
                        <p className="text-on-surface-variant leading-relaxed text-sm whitespace-pre-line">
                            {synopsis}
                        </p>
                    </div>
                )}

                {/* MAL Genres */}
                {malData?.genres && malData.genres.length > 0 && (
                    <div className="bg-surface-container rounded-3xl p-6 md:p-8 border border-outline-variant/5 space-y-3">
                        <h2 className="font-headline font-black text-lg text-on-surface tracking-tight">Genres</h2>
                        <div className="flex flex-wrap gap-2">
                            {malData.genres.map((g: { mal_id: number; name: string }) => (
                                <span key={g.mal_id} className="px-3 py-1.5 bg-primary/10 text-primary text-[11px] font-black uppercase tracking-[0.12em] rounded-lg">
                                    {g.name}
                                </span>
                            ))}
                        </div>
                    </div>
                )}

                {/* Notes (if MAL synopsis also shown) */}
                {entry.notes && malData?.synopsis && (
                    <div className="bg-surface-container rounded-3xl p-6 md:p-8 border border-outline-variant/5 space-y-3">
                        <h2 className="font-headline font-black text-lg text-on-surface tracking-tight">My Notes</h2>
                        <p className="text-on-surface-variant leading-relaxed text-sm whitespace-pre-line">{entry.notes}</p>
                    </div>
                )}
            </div>

            {editing && <EditEntryModal entry={entry} onClose={() => setEditing(false)} />}
        </AppLayout>
    );
}
