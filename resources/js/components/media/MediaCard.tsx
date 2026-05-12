import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import { SearchMode } from '@/stores/useSearchStore';
import { useState } from 'react';
import { AddEntryModal, MALItem } from '@/components/media/AddEntryModal';

interface MediaCardProps {
    item: any;
    mode: SearchMode;
}

export function MediaCard({ item, mode }: MediaCardProps) {
    const isLocal = mode === 'local';
    const [addingItem, setAddingItem] = useState<MALItem | null>(null);

    function handleAddClick() {
        setAddingItem({
            mal_id: item.mal_id,
            title: item.title,
            cover_url: item.cover_url,
            type: item.type,
            score: item.score,
        });
    }

    return (
        <>
            <div className="group relative bg-surface-container rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl border border-outline-variant/5">
                {/* Poster Area */}
                <div className="aspect-[2/3] overflow-hidden relative">
                    {item.cover_url ? (
                        <img
                            src={item.cover_url}
                            alt={item.title}
                            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            loading="lazy"
                        />
                    ) : (
                        <div className="w-full h-full bg-surface-container-highest flex items-center justify-center">
                            <span className="material-symbols-outlined text-4xl text-on-surface-variant/30">image</span>
                        </div>
                    )}

                    {/* Overlay on Hover */}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300" />

                    {/* Badge (Status or Score) */}
                    <div className="absolute top-3 left-3 z-10">
                        {isLocal ? (
                            <span className="bg-black/60 backdrop-blur-md text-[9px] font-black uppercase tracking-[0.2em] text-white px-2.5 py-1.5 rounded-lg border border-white/5">
                                {item.status.replace('_', ' ')}
                            </span>
                        ) : item.score && (
                            <span className="bg-secondary/90 backdrop-blur-md text-[9px] font-black text-on-secondary px-2.5 py-1.5 rounded-lg flex items-center gap-1 shadow-lg">
                                <span className="material-symbols-outlined text-[12px]" style={{ fontVariationSettings: "'FILL' 1" }}>star</span>
                                {item.score}
                            </span>
                        )}
                    </div>

                    {/* Add to Archive action */}
                    {!isLocal && (
                        <div className="absolute inset-x-4 bottom-4 translate-y-full group-hover:translate-y-0 transition-all duration-500 z-10">
                            <button
                                onClick={handleAddClick}
                                className="w-full py-3.5 bg-secondary text-on-secondary rounded-xl font-bold text-[10px] uppercase tracking-[0.2em] shadow-2xl flex items-center justify-center gap-2 hover:bg-white hover:text-black transition-all active:scale-95"
                            >
                                <span className="material-symbols-outlined text-[16px]">add_circle</span>
                                Add to Archive
                            </button>
                        </div>
                    )}
                </div>

                {/* Content Area */}
                <div className="p-4">
                    <h4 className="font-headline font-bold text-sm text-on-surface truncate group-hover:text-primary transition-colors mb-1.5">
                        {isLocal ? (
                            <Link href={`/my-list/${item.id}`}>
                                {item.title}
                            </Link>
                        ) : item.title}
                    </h4>

                    <div className="flex items-center justify-between">
                        <span className="text-[10px] font-black text-on-surface-variant uppercase tracking-[0.15em]">
                            {item.type}
                        </span>

                        {isLocal && item.rating && (
                            <div className="flex items-center gap-1 text-primary">
                                <span className="material-symbols-outlined text-[14px]" style={{ fontVariationSettings: "'FILL' 1" }}>star</span>
                                <span className="text-[10px] font-black">{item.rating}</span>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Add Entry Modal */}
            {addingItem && (
                <AddEntryModal item={addingItem} onClose={() => setAddingItem(null)} />
            )}
        </>
    );
}
