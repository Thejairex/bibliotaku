import AppLayout from '@/layouts/AppLayout';
import { Head, Link } from '@inertiajs/react';
import { DashboardPageProps } from '@/types/SharedProps';
import { MediaEntry } from '@/types/MediaEntry';
import { cn } from '@/lib/utils';

function getProgress(entry: MediaEntry): number {
    if (entry.type === 'anime') {
        if (!entry.total_episodes || entry.total_episodes === 0) return 0;
        return Math.round(((entry.current_episode ?? 0) / entry.total_episodes) * 100);
    }
    if (!entry.total_chapters || entry.total_chapters === 0) return 0;
    return Math.round(((entry.current_chapter ?? 0) / entry.total_chapters) * 100);
}

const STATUS_LABEL: Record<string, { text: string; color: string }> = {
    watching:   { text: 'VIENDO',  color: 'text-blue-400' },
    rewatching: { text: 'VIENDO',  color: 'text-blue-400' },
    reading:    { text: 'LEYENDO', color: 'text-orange-400' },
};

export default function Dashboard({ auth, stats, recent_entries, in_progress_entries }: DashboardPageProps) {
    const statCards = [
        { label: 'Watching', value: stats.watching, icon: 'visibility', color: 'text-primary' },
        { label: 'Reading', value: stats.reading, icon: 'menu_book', color: 'text-secondary' },
        { label: 'Completed', value: stats.completed, icon: 'check_circle', color: 'text-green-400' },
        { label: 'On Hold', value: stats.on_hold, icon: 'pause_circle', color: 'text-orange-400' },
        { label: 'Plan to Watch', value: stats.plan_to_watch, icon: 'schedule', color: 'text-on-surface-variant' },
    ];

    return (
        <AppLayout>
            <Head title="Dashboard" />

            <div className="space-y-12">
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <span className="text-primary font-black uppercase tracking-[0.3em] text-[10px] mb-2 block">
                            Overview
                        </span>
                        <h1 className="text-5xl font-headline font-black text-on-surface tracking-tight">
                            Welcome, {auth.user.name.split(' ')[0]}
                        </h1>
                        <p className="text-on-surface-variant mt-2 text-lg">
                            Your digital archive is up to date with <span className="text-on-surface font-bold">{stats.total_entries}</span> entries.
                        </p>
                    </div>

                    <Link
                        href="/search"
                        className="px-8 py-4 bg-primary text-on-primary rounded-2xl font-bold shadow-lg shadow-primary/20 hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2 w-fit"
                    >
                        <span className="material-symbols-outlined">add</span>
                        Discover New
                    </Link>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    {statCards.map((stat) => (
                        <div key={stat.label} className="p-6 bg-surface-container rounded-3xl border border-outline-variant/5 group hover:bg-surface-container-high transition-colors">
                            <div className={cn('size-10 rounded-xl bg-background flex items-center justify-center mb-4', stat.color)}>
                                <span className="material-symbols-outlined text-[20px]">{stat.icon}</span>
                            </div>
                            <h4 className="text-2xl font-headline font-black text-on-surface">{stat.value}</h4>
                            <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mt-1">{stat.label}</p>
                        </div>
                    ))}
                </div>

                {/* Main Dashboard Content */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Recent Activity */}
                    <div className="lg:col-span-2 space-y-6">
                        <div className="flex items-center justify-between px-2">
                            <h3 className="text-xl font-headline font-bold">Recent Activity</h3>
                            <Link href="/my-list" className="text-xs font-bold text-primary hover:underline uppercase tracking-widest">
                                View All
                            </Link>
                        </div>

                        <div className="bg-surface-container rounded-3xl border border-outline-variant/5 overflow-hidden">
                            {recent_entries.length > 0 ? (
                                <div className="divide-y divide-outline-variant/5">
                                    {recent_entries.map((entry) => (
                                        <Link
                                            key={entry.id}
                                            href={`/my-list/${entry.id}`}
                                            className="flex items-center gap-4 p-4 hover:bg-surface-container-high transition-all group"
                                        >
                                            <div className="size-14 rounded-xl overflow-hidden bg-background flex-shrink-0">
                                                {entry.cover_url ? (
                                                    <img src={entry.cover_url} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center text-on-surface-variant/30">
                                                        <span className="material-symbols-outlined">image</span>
                                                    </div>
                                                )}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <h5 className="font-bold text-on-surface truncate group-hover:text-primary transition-colors">
                                                    {entry.title}
                                                </h5>
                                                <div className="flex items-center gap-2 mt-1">
                                                    <span className="text-[10px] font-black uppercase tracking-tighter text-on-surface-variant">
                                                        {entry.status.replace('_', ' ')}
                                                    </span>
                                                    <span className="size-1 rounded-full bg-outline-variant/30" />
                                                    <span className="text-[10px] font-bold text-primary italic lowercase tracking-tight">
                                                        updated {new Date(entry.updated_at).toLocaleDateString()}
                                                    </span>
                                                </div>
                                            </div>
                                            <span className="material-symbols-outlined text-on-surface-variant opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0">
                                                chevron_right
                                            </span>
                                        </Link>
                                    ))}
                                </div>
                            ) : (
                                <div className="p-12 text-center">
                                    <span className="material-symbols-outlined text-4xl text-on-surface-variant/20 mb-3">history</span>
                                    <p className="text-on-surface-variant text-sm font-medium">No recent entries found.</p>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Right column */}
                    <div className="space-y-8">
                        {/* Completion Rate */}
                        <div className="space-y-6">
                            <h3 className="text-xl font-headline font-bold px-2">Completion Rate</h3>
                            <div className="bg-surface-container rounded-3xl border border-outline-variant/5 p-8 flex flex-col items-center justify-center text-center gap-4">
                                <div className="relative size-32 flex items-center justify-center">
                                    <svg className="size-full -rotate-90">
                                        <circle cx="64" cy="64" r="58" className="fill-none stroke-outline-variant/10" strokeWidth="12" />
                                        <circle
                                            cx="64"
                                            cy="64"
                                            r="58"
                                            className="fill-none stroke-primary"
                                            strokeWidth="12"
                                            strokeDasharray="364.42"
                                            strokeDashoffset={364.42 - (364.42 * (stats.completed / (stats.total_entries || 1)))}
                                            strokeLinecap="round"
                                        />
                                    </svg>
                                    <span className="absolute text-2xl font-black text-on-surface">
                                        {stats.total_entries > 0 ? Math.round((stats.completed / stats.total_entries) * 100) : 0}%
                                    </span>
                                </div>
                                <div>
                                    <p className="font-bold text-on-surface italic uppercase tracking-tighter text-sm">Archival Master</p>
                                    <p className="text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant mt-1">Consistency Level 4</p>
                                </div>
                            </div>
                        </div>

                        {/* In Progress */}
                        <div className="space-y-6">
                            <h3 className="text-xl font-headline font-bold px-2">In Progress</h3>
                            <div className="bg-surface-container rounded-3xl border border-outline-variant/5 p-6 flex flex-col gap-5">
                                {in_progress_entries.length > 0 ? (
                                    <>
                                        <div className="space-y-4">
                                            {in_progress_entries.map((entry) => {
                                                const progress = getProgress(entry);
                                                const circumference = 2 * Math.PI * 20;
                                                const offset = circumference - (circumference * progress) / 100;
                                                const statusMeta = STATUS_LABEL[entry.status] ?? { text: entry.status.toUpperCase(), color: 'text-on-surface-variant' };

                                                return (
                                                    <Link
                                                        key={entry.id}
                                                        href={`/my-list/${entry.id}`}
                                                        className="flex items-center gap-4 group"
                                                    >
                                                        {/* Progress ring */}
                                                        <div className="relative size-12 flex-shrink-0 flex items-center justify-center">
                                                            <svg className="size-full -rotate-90" viewBox="0 0 48 48">
                                                                <circle cx="24" cy="24" r="20" className="fill-none stroke-outline-variant/20" strokeWidth="4" />
                                                                <circle
                                                                    cx="24"
                                                                    cy="24"
                                                                    r="20"
                                                                    className="fill-none stroke-primary transition-all duration-500"
                                                                    strokeWidth="4"
                                                                    strokeDasharray={circumference}
                                                                    strokeDashoffset={offset}
                                                                    strokeLinecap="round"
                                                                />
                                                            </svg>
                                                            <span className="absolute text-[10px] font-black text-on-surface">
                                                                {progress}%
                                                            </span>
                                                        </div>

                                                        {/* Info */}
                                                        <div className="flex-1 min-w-0">
                                                            <p className="font-bold text-on-surface text-sm truncate group-hover:text-primary transition-colors">
                                                                {entry.title}
                                                            </p>
                                                            <p className={cn('text-[10px] font-black uppercase tracking-widest mt-0.5', statusMeta.color)}>
                                                                {statusMeta.text}
                                                            </p>
                                                        </div>
                                                    </Link>
                                                );
                                            })}
                                        </div>

                                        <Link
                                            href="/my-list"
                                            className="w-full py-3 rounded-full font-bold text-sm text-center text-on-primary bg-gradient-to-r from-primary to-primary-dim hover:opacity-90 transition-opacity shadow-lg shadow-primary/20"
                                        >
                                            View Collection
                                        </Link>
                                    </>
                                ) : (
                                    <div className="py-8 text-center">
                                        <span className="material-symbols-outlined text-3xl text-on-surface-variant/20 mb-2">play_circle</span>
                                        <p className="text-on-surface-variant text-xs font-medium">Nothing in progress yet.</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
