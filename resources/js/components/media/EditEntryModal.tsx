import { useForm } from '@inertiajs/react';
import { Dialog, Transition } from '@headlessui/react';
import { Fragment } from 'react';
import { MediaEntry, MediaStatus, MediaType } from '@/types/MediaEntry';
import { cn } from '@/lib/utils';
import { useToastStore } from '@/stores/useToastStore';

interface Props {
    entry: MediaEntry;
    onClose: () => void;
}

const STATUS_OPTIONS = [
    { value: MediaStatus.Watching, label: 'Watching' },
    { value: MediaStatus.Rewatching, label: 'Rewatching' },
    { value: MediaStatus.Reading, label: 'Reading' },
    { value: MediaStatus.Completed, label: 'Completed' },
    { value: MediaStatus.OnHold, label: 'On Hold' },
    { value: MediaStatus.Dropped, label: 'Dropped' },
    { value: MediaStatus.PlanToWatch, label: 'Plan to Watch' },
];

const TYPE_OPTIONS = [
    { value: MediaType.Anime, label: 'Anime' },
    { value: MediaType.Manga, label: 'Manga' },
    { value: MediaType.Manhwa, label: 'Manhwa' },
    { value: MediaType.Manhua, label: 'Manhua' },
    { value: MediaType.Novel, label: 'Novel' },
];

function Field({ label, error, children }: { label: string; error?: string; children: React.ReactNode }) {
    return (
        <div className="space-y-1.5">
            <label className="text-xs font-black text-on-surface-variant uppercase tracking-[0.12em]">{label}</label>
            {children}
            {error && <p className="text-red-400 text-xs">{error}</p>}
        </div>
    );
}

const inputClass = "w-full px-3 py-2.5 bg-surface-container-highest rounded-xl text-sm text-on-surface placeholder:text-on-surface-variant/40 outline-none focus:ring-2 focus:ring-primary/40 transition-shadow";

export function EditEntryModal({ entry, onClose }: Props) {
    const { addToast } = useToastStore();
    const isAnime = entry.type === MediaType.Anime;

    const { data, setData, patch, processing, errors } = useForm({
        title: entry.title,
        original_title: entry.original_title ?? '',
        type: entry.type,
        status: entry.status,
        cover_url: entry.cover_url ?? '',
        current_episode: entry.current_episode ?? 0,
        total_episodes: entry.total_episodes ?? 0,
        current_chapter: entry.current_chapter ?? 0,
        total_chapters: entry.total_chapters ?? 0,
        current_volume: entry.current_volume ?? 0,
        total_volumes: entry.total_volumes ?? 0,
        rating: entry.rating ?? '',
        notes: entry.notes ?? '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        patch(`/my-list/${entry.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                addToast('success', 'Entry updated successfully!');
                onClose();
            },
        });
    }

    const usesEpisodes = data.type === MediaType.Anime;

    return (
        <Transition show as={Fragment}>
            <Dialog onClose={onClose} className="relative z-50">
                {/* Backdrop */}
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-200" enterFrom="opacity-0" enterTo="opacity-100"
                    leave="ease-in duration-150" leaveFrom="opacity-100" leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black/60 backdrop-blur-sm" />
                </Transition.Child>

                {/* Panel */}
                <div className="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-200" enterFrom="opacity-0 scale-95" enterTo="opacity-100 scale-100"
                        leave="ease-in duration-150" leaveFrom="opacity-100 scale-100" leaveTo="opacity-0 scale-95"
                    >
                        <Dialog.Panel className="w-full max-w-lg bg-[#161616] rounded-3xl shadow-2xl border border-outline-variant/10 overflow-hidden">
                            {/* Header */}
                            <div className="flex items-center justify-between px-6 pt-6 pb-4 border-b border-outline-variant/10">
                                <Dialog.Title className="font-headline font-black text-lg text-on-surface tracking-tight">
                                    Edit Entry
                                </Dialog.Title>
                                <button onClick={onClose} className="p-2 rounded-xl hover:bg-surface-container text-on-surface-variant transition-colors">
                                    <span className="material-symbols-outlined text-[20px]">close</span>
                                </button>
                            </div>

                            {/* Form */}
                            <form onSubmit={submit} className="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                                <Field label="Title" error={errors.title}>
                                    <input type="text" value={data.title} onChange={e => setData('title', e.target.value)} className={inputClass} required />
                                </Field>

                                <Field label="Original Title" error={errors.original_title}>
                                    <input type="text" value={data.original_title} onChange={e => setData('original_title', e.target.value)} className={inputClass} placeholder="Optional" />
                                </Field>

                                <div className="grid grid-cols-2 gap-3">
                                    <Field label="Type" error={errors.type}>
                                        <select value={data.type} onChange={e => setData('type', e.target.value as MediaType)} className={cn(inputClass, 'cursor-pointer')}>
                                            {TYPE_OPTIONS.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
                                        </select>
                                    </Field>
                                    <Field label="Status" error={errors.status}>
                                        <select value={data.status} onChange={e => setData('status', e.target.value as MediaStatus)} className={cn(inputClass, 'cursor-pointer')}>
                                            {STATUS_OPTIONS.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}
                                        </select>
                                    </Field>
                                </div>

                                {usesEpisodes ? (
                                    <div className="grid grid-cols-2 gap-3">
                                        <Field label="Current Episode" error={errors.current_episode}>
                                            <input type="number" min={0} value={data.current_episode} onChange={e => setData('current_episode', Number(e.target.value))} className={inputClass} />
                                        </Field>
                                        <Field label="Total Episodes" error={errors.total_episodes}>
                                            <input type="number" min={0} value={data.total_episodes} onChange={e => setData('total_episodes', Number(e.target.value))} className={inputClass} />
                                        </Field>
                                    </div>
                                ) : (
                                    <>
                                        <div className="grid grid-cols-2 gap-3">
                                            <Field label="Current Chapter" error={errors.current_chapter}>
                                                <input type="number" min={0} value={data.current_chapter} onChange={e => setData('current_chapter', Number(e.target.value))} className={inputClass} />
                                            </Field>
                                            <Field label="Total Chapters" error={errors.total_chapters}>
                                                <input type="number" min={0} value={data.total_chapters} onChange={e => setData('total_chapters', Number(e.target.value))} className={inputClass} />
                                            </Field>
                                        </div>
                                        <div className="grid grid-cols-2 gap-3">
                                            <Field label="Current Volume" error={errors.current_volume}>
                                                <input type="number" min={0} value={data.current_volume} onChange={e => setData('current_volume', Number(e.target.value))} className={inputClass} />
                                            </Field>
                                            <Field label="Total Volumes" error={errors.total_volumes}>
                                                <input type="number" min={0} value={data.total_volumes} onChange={e => setData('total_volumes', Number(e.target.value))} className={inputClass} />
                                            </Field>
                                        </div>
                                    </>
                                )}

                                <Field label="Rating (1–5)" error={errors.rating}>
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
                                </Field>

                                <Field label="Notes" error={errors.notes}>
                                    <textarea
                                        value={data.notes}
                                        onChange={e => setData('notes', e.target.value)}
                                        rows={3}
                                        placeholder="Your thoughts..."
                                        className={cn(inputClass, 'resize-none')}
                                    />
                                </Field>

                                <Field label="Cover URL" error={errors.cover_url}>
                                    <input type="url" value={data.cover_url} onChange={e => setData('cover_url', e.target.value)} className={inputClass} placeholder="https://..." />
                                </Field>
                            </form>

                            {/* Footer */}
                            <div className="flex justify-end gap-3 px-6 py-4 border-t border-outline-variant/10">
                                <button onClick={onClose} className="px-5 py-2.5 rounded-xl text-sm text-on-surface-variant hover:bg-surface-container transition-colors font-medium">
                                    Cancel
                                </button>
                                <button
                                    onClick={submit}
                                    disabled={processing}
                                    className="px-6 py-2.5 bg-primary text-on-primary rounded-xl text-sm font-bold hover:bg-primary/90 disabled:opacity-50 transition-all"
                                >
                                    {processing ? 'Saving…' : 'Save Changes'}
                                </button>
                            </div>
                        </Dialog.Panel>
                    </Transition.Child>
                </div>
            </Dialog>
        </Transition>
    );
}
