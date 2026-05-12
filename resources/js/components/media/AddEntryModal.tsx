import { useForm } from '@inertiajs/react';
import { Dialog, Transition } from '@headlessui/react';
import { Fragment } from 'react';
import { MediaStatus, MediaType } from '@/types/MediaEntry';
import { cn } from '@/lib/utils';
import { useToastStore } from '@/stores/useToastStore';

export interface MALItem {
    mal_id: number;
    title: string;
    cover_url?: string;
    type?: string;
    score?: number;
}

interface Props {
    item: MALItem;
    onClose: () => void;
}

const STATUS_OPTIONS = [
    { value: MediaStatus.PlanToWatch, label: 'Plan to Watch' },
    { value: MediaStatus.Watching, label: 'Watching' },
    { value: MediaStatus.Reading, label: 'Reading' },
    { value: MediaStatus.Completed, label: 'Completed' },
    { value: MediaStatus.OnHold, label: 'On Hold' },
    { value: MediaStatus.Dropped, label: 'Dropped' },
];

function inferType(malType?: string): MediaType {
    if (!malType) return MediaType.Anime;
    const t = malType.toLowerCase();
    if (t === 'manga') return MediaType.Manga;
    if (t === 'manhwa') return MediaType.Manhwa;
    if (t === 'manhua') return MediaType.Manhua;
    if (t === 'novel' || t === 'light novel') return MediaType.Novel;
    return MediaType.Anime;
}

const inputClass = "w-full px-3 py-2.5 bg-surface-container-highest rounded-xl text-sm text-on-surface placeholder:text-on-surface-variant/40 outline-none focus:ring-2 focus:ring-primary/40 transition-shadow";

export function AddEntryModal({ item, onClose }: Props) {
    const { addToast } = useToastStore();
    const detectedType = inferType(item.type);
    const usesEpisodes = detectedType === MediaType.Anime;

    const { data, setData, post, processing, errors } = useForm({
        title: item.title,
        original_title: '',
        type: detectedType,
        status: MediaStatus.PlanToWatch,
        cover_url: item.cover_url ?? '',
        mal_id: item.mal_id,
        current_episode: 0,
        total_episodes: 0,
        current_chapter: 0,
        total_chapters: 0,
        current_volume: 0,
        total_volumes: 0,
        rating: '' as number | '',
        notes: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/my-list', {
            preserveScroll: true,
            onSuccess: () => {
                addToast('success', `"${item.title}" added to your archive!`);
                onClose();
            },
            onError: (errs) => {
                if (errs.mal_id) {
                    addToast('error', 'This entry is already in your archive.');
                    onClose();
                }
            },
        });
    }

    return (
        <Transition show as={Fragment}>
            <Dialog onClose={onClose} className="relative z-50">
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-200" enterFrom="opacity-0" enterTo="opacity-100"
                    leave="ease-in duration-150" leaveFrom="opacity-100" leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black/60 backdrop-blur-sm" />
                </Transition.Child>

                <div className="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-200" enterFrom="opacity-0 scale-95" enterTo="opacity-100 scale-100"
                        leave="ease-in duration-150" leaveFrom="opacity-100 scale-100" leaveTo="opacity-0 scale-95"
                    >
                        <Dialog.Panel className="w-full max-w-md bg-[#161616] rounded-3xl shadow-2xl border border-outline-variant/10 overflow-hidden">
                            {/* Header with cover */}
                            <div className="relative">
                                {item.cover_url && (
                                    <div className="h-32 overflow-hidden">
                                        <img src={item.cover_url} alt={item.title} className="w-full h-full object-cover object-top" />
                                        <div className="absolute inset-0 bg-gradient-to-b from-transparent to-[#161616]" />
                                    </div>
                                )}
                                <div className={cn('px-6 pb-4', item.cover_url ? 'pt-2' : 'pt-6')}>
                                    <div className="flex items-start justify-between gap-3">
                                        <div>
                                            <Dialog.Title className="font-headline font-black text-lg text-on-surface tracking-tight leading-tight">
                                                {item.title}
                                            </Dialog.Title>
                                            <p className="text-[11px] text-on-surface-variant mt-1 uppercase font-black tracking-[0.15em]">
                                                Add to Archive
                                            </p>
                                        </div>
                                        <button onClick={onClose} className="shrink-0 p-2 rounded-xl hover:bg-surface-container text-on-surface-variant transition-colors">
                                            <span className="material-symbols-outlined text-[20px]">close</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <form onSubmit={submit} className="px-6 pb-6 space-y-4">
                                {/* Status */}
                                <div className="space-y-1.5">
                                    <label className="text-xs font-black text-on-surface-variant uppercase tracking-[0.12em]">Status</label>
                                    <div className="grid grid-cols-2 gap-2">
                                        {STATUS_OPTIONS.map(o => (
                                            <button
                                                key={o.value}
                                                type="button"
                                                onClick={() => setData('status', o.value)}
                                                className={cn(
                                                    'py-2.5 rounded-xl text-xs font-bold transition-all text-center',
                                                    data.status === o.value
                                                        ? 'bg-primary text-on-primary'
                                                        : 'bg-surface-container-highest text-on-surface-variant hover:bg-primary/10'
                                                )}
                                            >
                                                {o.label}
                                            </button>
                                        ))}
                                    </div>
                                    {errors.status && <p className="text-red-400 text-xs">{errors.status}</p>}
                                </div>

                                {/* Progress */}
                                {usesEpisodes ? (
                                    <div className="grid grid-cols-2 gap-3">
                                        <div className="space-y-1.5">
                                            <label className="text-xs font-black text-on-surface-variant uppercase tracking-[0.12em]">Episode</label>
                                            <input type="number" min={0} value={data.current_episode} onChange={e => setData('current_episode', Number(e.target.value))} className={inputClass} />
                                        </div>
                                        <div className="space-y-1.5">
                                            <label className="text-xs font-black text-on-surface-variant uppercase tracking-[0.12em]">Total Eps.</label>
                                            <input type="number" min={0} value={data.total_episodes} onChange={e => setData('total_episodes', Number(e.target.value))} className={inputClass} />
                                        </div>
                                    </div>
                                ) : (
                                    <div className="grid grid-cols-2 gap-3">
                                        <div className="space-y-1.5">
                                            <label className="text-xs font-black text-on-surface-variant uppercase tracking-[0.12em]">Chapter</label>
                                            <input type="number" min={0} value={data.current_chapter} onChange={e => setData('current_chapter', Number(e.target.value))} className={inputClass} />
                                        </div>
                                        <div className="space-y-1.5">
                                            <label className="text-xs font-black text-on-surface-variant uppercase tracking-[0.12em]">Total Ch.</label>
                                            <input type="number" min={0} value={data.total_chapters} onChange={e => setData('total_chapters', Number(e.target.value))} className={inputClass} />
                                        </div>
                                    </div>
                                )}

                                {/* Rating */}
                                <div className="space-y-1.5">
                                    <label className="text-xs font-black text-on-surface-variant uppercase tracking-[0.12em]">Rating (optional)</label>
                                    <div className="flex gap-2">
                                        {[1, 2, 3, 4, 5].map(star => (
                                            <button
                                                key={star}
                                                type="button"
                                                onClick={() => setData('rating', data.rating === star ? '' : star)}
                                                className={cn(
                                                    'flex-1 py-2 rounded-xl text-sm font-bold transition-all',
                                                    data.rating === star
                                                        ? 'bg-primary text-on-primary'
                                                        : 'bg-surface-container-highest text-on-surface-variant hover:bg-primary/10'
                                                )}
                                            >
                                                {star}
                                            </button>
                                        ))}
                                    </div>
                                </div>

                                <div className="pt-2 flex gap-3">
                                    <button type="button" onClick={onClose} className="flex-1 py-3 rounded-xl text-sm text-on-surface-variant hover:bg-surface-container transition-colors font-medium">
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="flex-1 py-3 bg-primary text-on-primary rounded-xl text-sm font-bold hover:bg-primary/90 disabled:opacity-50 transition-all flex items-center justify-center gap-2"
                                    >
                                        <span className="material-symbols-outlined text-[16px]">add_circle</span>
                                        {processing ? 'Adding…' : 'Add to Archive'}
                                    </button>
                                </div>
                            </form>
                        </Dialog.Panel>
                    </Transition.Child>
                </div>
            </Dialog>
        </Transition>
    );
}
