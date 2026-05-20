import AppLayout from '@/layouts/AppLayout';
import { MediaType } from '@/types/MediaEntry';
import { CategoryTypeMap, ParseResponse, ParsedEntry } from '@/types/MediaImport';
import { useToastStore } from '@/stores/useToastStore';
import { Head, Link, router } from '@inertiajs/react';
import { useMemo, useRef, useState } from 'react';

const TYPE_OPTIONS: { value: MediaType; label: string }[] = [
    { value: MediaType.Manga, label: 'Manga' },
    { value: MediaType.Manhwa, label: 'Manhwa' },
    { value: MediaType.Manhua, label: 'Manhua' },
    { value: MediaType.Novel, label: 'Novela' },
    { value: MediaType.Anime, label: 'Anime' },
];

const DUP_BADGE: Record<string, string> = {
    new: 'bg-emerald-500/20 text-emerald-300',
    update: 'bg-amber-500/20 text-amber-300',
    skip: 'bg-zinc-500/20 text-zinc-400',
};

const DUP_LABEL: Record<string, string> = {
    new: 'Nuevo',
    update: 'Actualizar',
    skip: 'Sin cambios',
};

function getCsrfToken(): string {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el?.getAttribute('content') ?? '';
}

export default function ImportPage() {
    const { addToast } = useToastStore();
    const fileInputRef = useRef<HTMLInputElement>(null);

    const [parsing, setParsing] = useState(false);
    const [submitting, setSubmitting] = useState(false);
    const [parsed, setParsed] = useState<ParseResponse | null>(null);
    const [fallbackType, setFallbackType] = useState<MediaType>(MediaType.Manga);
    const [mapping, setMapping] = useState<CategoryTypeMap>({});

    async function handleFile(file: File) {
        setParsing(true);
        setParsed(null);
        try {
            const fd = new FormData();
            fd.append('file', file);
            const res = await fetch('/my-list/import/parse', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
            });
            if (!res.ok) {
                const body = await res.json().catch(() => ({ message: 'Error al procesar el archivo.' }));
                addToast('error', body.message ?? 'Error al procesar el archivo.');
                return;
            }
            const data: ParseResponse = await res.json();
            setParsed(data);
            const initial: CategoryTypeMap = {};
            data.categories.forEach((c) => {
                initial[c.id] = guessTypeFromName(c.name) ?? fallbackType;
            });
            setMapping(initial);
        } catch (e) {
            addToast('error', 'No se pudo enviar el archivo.');
        } finally {
            setParsing(false);
        }
    }

    function onFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (file) {
            handleFile(file);
        }
    }

    function onDrop(e: React.DragEvent<HTMLDivElement>) {
        e.preventDefault();
        const file = e.dataTransfer.files?.[0];
        if (file) {
            handleFile(file);
        }
    }

    function submit() {
        if (!parsed) return;
        setSubmitting(true);
        router.post(
            '/my-list/import',
            {
                entries: parsed.entries,
                mapping,
                fallback_type: fallbackType,
            },
            {
                onError: () => {
                    addToast('error', 'No se pudo completar la importación.');
                    setSubmitting(false);
                },
                onFinish: () => setSubmitting(false),
            },
        );
    }

    const aggregateByType = useMemo(() => {
        if (!parsed) return {};
        const counts: Record<string, number> = {};
        parsed.entries.forEach((e) => {
            const type = resolveTypeForEntry(e, mapping, fallbackType);
            counts[type] = (counts[type] ?? 0) + 1;
        });
        return counts;
    }, [parsed, mapping, fallbackType]);

    return (
        <AppLayout>
            <Head title="Importar JSON" />

            <div className="space-y-8 animate-fade-in">
                <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h1 className="text-4xl font-headline font-black tracking-tighter text-on-surface">Importar biblioteca</h1>
                        <p className="text-on-surface-variant mt-1">
                            Sube un backup JSON con la estructura de Mihon/Tachiyomi (<code>backupManga</code>).
                        </p>
                    </div>
                    <Link
                        href="/my-list"
                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-surface-container text-on-surface rounded-xl font-bold text-sm hover:bg-surface-container-highest transition-colors"
                    >
                        <span className="material-symbols-outlined text-[18px]">arrow_back</span>
                        Volver
                    </Link>
                </div>

                {!parsed && (
                    <div
                        onDragOver={(e) => e.preventDefault()}
                        onDrop={onDrop}
                        onClick={() => fileInputRef.current?.click()}
                        className="bg-surface-container-low rounded-3xl p-16 text-center cursor-pointer hover:bg-surface-container transition-colors"
                    >
                        <span className="material-symbols-outlined text-5xl text-on-surface-variant/40">upload_file</span>
                        <p className="mt-4 font-bold text-on-surface">
                            {parsing ? 'Procesando archivo…' : 'Arrastra tu archivo .json o haz clic para seleccionar'}
                        </p>
                        <p className="text-xs text-on-surface-variant mt-1">Tamaño máximo: 5 MB</p>
                        <input
                            ref={fileInputRef}
                            type="file"
                            accept="application/json,.json"
                            onChange={onFileChange}
                            className="hidden"
                        />
                    </div>
                )}

                {parsed && (
                    <div className="space-y-6">
                        <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <StatTile label="Total" value={parsed.stats.total} />
                            <StatTile label="Nuevas" value={parsed.stats.new} tone="emerald" />
                            <StatTile label="A actualizar" value={parsed.stats.update} tone="amber" />
                            <StatTile label="Sin cambios" value={parsed.stats.skip} tone="zinc" />
                        </div>

                        <div className="bg-surface-container-low rounded-3xl p-6 space-y-4">
                            <div>
                                <h2 className="font-headline font-black text-on-surface">Tipo por defecto</h2>
                                <p className="text-xs text-on-surface-variant mt-1">
                                    Se usa cuando una entrada no tiene categoría mapeada.
                                </p>
                            </div>
                            <select
                                value={fallbackType}
                                onChange={(e) => setFallbackType(e.target.value as MediaType)}
                                className="px-4 py-2.5 bg-surface-container rounded-xl text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                            >
                                {TYPE_OPTIONS.map((o) => (
                                    <option key={o.value} value={o.value}>
                                        {o.label}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {parsed.categories.length > 0 && (
                            <div className="bg-surface-container-low rounded-3xl p-6 space-y-4">
                                <div>
                                    <h2 className="font-headline font-black text-on-surface">Mapeo de categorías</h2>
                                    <p className="text-xs text-on-surface-variant mt-1">
                                        Asocia cada categoría detectada en el backup con un tipo de media.
                                    </p>
                                </div>
                                <div className="space-y-2">
                                    {parsed.categories.map((cat) => (
                                        <div
                                            key={cat.id}
                                            className="flex items-center justify-between gap-3 bg-surface-container rounded-xl px-4 py-3"
                                        >
                                            <div className="min-w-0">
                                                <p className="text-sm font-bold text-on-surface truncate">{cat.name}</p>
                                                <p className="text-[11px] text-on-surface-variant">{cat.count} entradas</p>
                                            </div>
                                            <select
                                                value={mapping[cat.id] ?? fallbackType}
                                                onChange={(e) =>
                                                    setMapping((m) => ({ ...m, [cat.id]: e.target.value as MediaType }))
                                                }
                                                className="px-3 py-2 bg-surface-container-highest rounded-lg text-sm text-on-surface outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                                            >
                                                {TYPE_OPTIONS.map((o) => (
                                                    <option key={o.value} value={o.value}>
                                                        {o.label}
                                                    </option>
                                                ))}
                                            </select>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        <div className="bg-surface-container-low rounded-3xl p-6 space-y-4">
                            <div className="flex items-center justify-between">
                                <h2 className="font-headline font-black text-on-surface">Resumen por tipo</h2>
                                <span className="text-xs text-on-surface-variant">{parsed.entries.length} entradas</span>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                {Object.entries(aggregateByType).map(([type, count]) => (
                                    <span
                                        key={type}
                                        className="text-[11px] font-black uppercase tracking-[0.15em] px-3 py-1.5 rounded-lg bg-primary/15 text-primary"
                                    >
                                        {type} · {count}
                                    </span>
                                ))}
                            </div>
                        </div>

                        <div className="bg-surface-container-low rounded-3xl p-6 space-y-3">
                            <h2 className="font-headline font-black text-on-surface">Vista previa</h2>
                            <div className="max-h-[500px] overflow-y-auto space-y-2 pr-1">
                                {parsed.entries.map((entry) => (
                                    <EntryRow key={entry.index} entry={entry} />
                                ))}
                            </div>
                        </div>

                        <div className="flex items-center justify-end gap-3">
                            <button
                                onClick={() => {
                                    setParsed(null);
                                    setMapping({});
                                }}
                                className="px-5 py-2.5 rounded-xl text-sm text-on-surface-variant hover:bg-surface-container transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                onClick={submit}
                                disabled={submitting}
                                className="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-on-primary rounded-xl font-bold text-sm hover:bg-primary/90 transition-colors disabled:opacity-60"
                            >
                                <span className="material-symbols-outlined text-[18px]">cloud_upload</span>
                                {submitting ? 'Importando…' : 'Confirmar importación'}
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}

function StatTile({ label, value, tone }: { label: string; value: number; tone?: 'emerald' | 'amber' | 'zinc' }) {
    const toneCls =
        tone === 'emerald'
            ? 'text-emerald-300'
            : tone === 'amber'
              ? 'text-amber-300'
              : tone === 'zinc'
                ? 'text-zinc-400'
                : 'text-on-surface';
    return (
        <div className="bg-surface-container-low rounded-2xl p-4">
            <p className="text-[11px] font-black uppercase tracking-[0.15em] text-on-surface-variant">{label}</p>
            <p className={`mt-1 text-3xl font-headline font-black ${toneCls}`}>{value}</p>
        </div>
    );
}

function EntryRow({ entry }: { entry: ParsedEntry }) {
    return (
        <div className="flex items-center gap-3 bg-surface-container rounded-xl p-3">
            <div className="w-10 h-14 shrink-0 bg-surface-container-highest rounded-lg overflow-hidden">
                {entry.cover_url ? (
                    <img src={entry.cover_url} alt={entry.title} className="w-full h-full object-cover" loading="lazy" />
                ) : (
                    <div className="w-full h-full flex items-center justify-center">
                        <span className="material-symbols-outlined text-on-surface-variant/30 text-[18px]">image</span>
                    </div>
                )}
            </div>
            <div className="flex-1 min-w-0">
                <p className="text-sm font-bold text-on-surface truncate">{entry.title}</p>
                <p className="text-[11px] text-on-surface-variant">
                    Cap. {entry.current_chapter}
                    {entry.total_chapters ? ` / ${entry.total_chapters}` : ''} · {entry.inferred_status.replace('_', ' ')}
                </p>
            </div>
            <span
                className={`shrink-0 text-[9px] font-black uppercase tracking-[0.15em] px-2 py-1 rounded-lg ${DUP_BADGE[entry.dup_status]}`}
            >
                {DUP_LABEL[entry.dup_status]}
            </span>
        </div>
    );
}

function resolveTypeForEntry(entry: ParsedEntry, mapping: CategoryTypeMap, fallback: MediaType): MediaType {
    for (const cid of entry.category_ids) {
        if (mapping[cid]) return mapping[cid];
    }
    return fallback;
}

function guessTypeFromName(name: string): MediaType | null {
    const lower = name.toLowerCase();
    if (lower.includes('manhwa') || lower.includes('manwha')) return MediaType.Manhwa;
    if (lower.includes('manhua')) return MediaType.Manhua;
    if (lower.includes('novel')) return MediaType.Novel;
    if (lower.includes('anime')) return MediaType.Anime;
    if (lower.includes('manga')) return MediaType.Manga;
    return null;
}
