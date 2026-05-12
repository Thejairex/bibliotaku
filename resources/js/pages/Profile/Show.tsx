import AppLayout from '@/layouts/AppLayout';
import { Head, Link } from '@inertiajs/react';
import { ProfilePageProps } from '@/types/SharedProps';
import { MediaStatus, MediaType } from '@/types/MediaEntry';
import { cn } from '@/lib/utils';

// Helper to format days
const formatDays = (days: number) => {
    return days.toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 });
};

export default function ProfileShow({ profileUser, stats, distribution, recentActivity, favorites }: any) {
    return (
        <AppLayout>
            <Head title={`${profileUser.name}'s Profile`} />

            <div className="max-w-6xl mx-auto space-y-10 animate-fade-in pb-20">
                {/* Header / Hero Section */}
                <div className="relative h-64 md:h-80 rounded-[3rem] overflow-hidden bg-surface-container border border-outline-variant/5">
                    {/* Background decoration */}
                    <div className="absolute inset-0 bg-gradient-to-br from-primary/20 via-transparent to-secondary/10" />
                    <div className="absolute -right-20 -top-20 size-80 bg-primary/10 blur-[100px] rounded-full" />
                    
                    <div className="absolute inset-x-0 bottom-0 p-8 md:p-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
                        <div className="flex flex-col md:flex-row items-center md:items-end gap-6 text-center md:text-left">
                            {/* Avatar */}
                            <div className="size-24 md:size-32 rounded-[2.5rem] bg-surface-container-highest border-4 border-[#0e0e0e] overflow-hidden shadow-2xl relative z-10">
                                {profileUser.avatar ? (
                                    <img src={profileUser.avatar} alt={profileUser.name} className="w-full h-full object-cover" />
                                ) : (
                                    <div className="w-full h-full flex items-center justify-center text-4xl font-black text-primary bg-primary/10">
                                        {profileUser.name.charAt(0)}
                                    </div>
                                )}
                            </div>
                            
                            <div className="space-y-1">
                                <h1 className="text-4xl md:text-5xl font-headline font-black tracking-tighter text-on-surface">
                                    {profileUser.name}
                                </h1>
                                <p className="text-on-surface-variant font-medium flex items-center justify-center md:justify-start gap-2">
                                    <span className="material-symbols-outlined text-[18px]">calendar_today</span>
                                    Joined {new Date(profileUser.created_at).toLocaleDateString(undefined, { month: 'long', year: 'numeric' })}
                                </p>
                            </div>
                        </div>

                        <Link 
                            href="/settings/profile"
                            className="px-6 py-3 bg-white/5 backdrop-blur-md border border-white/10 text-white rounded-2xl font-bold text-sm hover:bg-white/10 transition-all flex items-center gap-2 self-center md:self-end"
                        >
                            <span className="material-symbols-outlined text-[18px]">edit</span>
                            Edit Profile
                        </Link>
                    </div>
                </div>

                {/* Main Content Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {/* Left Column: Stats & Distribution */}
                    <div className="lg:col-span-4 space-y-8">
                        {/* Summary Stats */}
                        <div className="grid grid-cols-2 gap-4">
                            <div className="p-6 bg-surface-container rounded-[2rem] border border-outline-variant/5 text-center space-y-1">
                                <span className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest block">Total Entries</span>
                                <span className="text-2xl font-black text-on-surface">{stats.totalMedia}</span>
                            </div>
                            <div className="p-6 bg-surface-container rounded-[2rem] border border-outline-variant/5 text-center space-y-1">
                                <span className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest block">Mean Score</span>
                                <span className="text-2xl font-black text-primary">{stats.meanScore}</span>
                            </div>
                            <div className="p-6 bg-surface-container rounded-[2rem] border border-outline-variant/5 text-center space-y-1">
                                <span className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest block">Completed</span>
                                <span className="text-2xl font-black text-secondary">{stats.completed}</span>
                            </div>
                            <div className="p-6 bg-surface-container rounded-[2rem] border border-outline-variant/5 text-center space-y-1">
                                <span className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest block">Days Spent</span>
                                <span className="text-2xl font-black text-on-surface">{formatDays(stats.daysSpent)}</span>
                            </div>
                        </div>

                        {/* Collection Distribution */}
                        <div className="p-8 bg-surface-container rounded-[2.5rem] border border-outline-variant/5 space-y-6">
                            <h3 className="font-headline font-black text-lg text-on-surface flex items-center gap-2">
                                <span className="material-symbols-outlined text-primary">pie_chart</span>
                                Distribution
                            </h3>
                            
                            <div className="space-y-5">
                                {distribution.map((item: any) => (
                                    <div key={item.color} className="space-y-2">
                                        <div className="flex justify-between items-end">
                                            <span className="text-xs font-black text-on-surface uppercase tracking-widest">{item.count} {item.color.includes('ba9eff') ? 'Anime' : (item.color.includes('9093ff') ? 'Manga' : 'Novels')}</span>
                                            <span className="text-[10px] font-black text-on-surface-variant">{item.percent}%</span>
                                        </div>
                                        <div className="h-3 w-full bg-surface-container-highest rounded-full overflow-hidden">
                                            <div 
                                                className="h-full transition-all duration-1000 ease-out"
                                                style={{ width: `${item.percent}%`, backgroundColor: item.color }}
                                            />
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Right Column: Favorites & Activity */}
                    <div className="lg:col-span-8 space-y-10">
                        {/* Favorites / Showcase */}
                        <section className="space-y-6">
                            <h3 className="font-headline font-black text-2xl text-on-surface tracking-tight px-2 flex items-center gap-3">
                                <span className="material-symbols-outlined text-secondary fill">grade</span>
                                Favorites
                            </h3>
                            
                            {favorites.length === 0 ? (
                                <div className="p-12 bg-surface-container-lowest rounded-[2.5rem] border border-dashed border-outline-variant/10 text-center">
                                    <p className="text-on-surface-variant text-sm font-medium italic">No favorites to show yet.</p>
                                </div>
                            ) : (
                                <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                                    {favorites.map((entry: any) => (
                                        <Link 
                                            key={entry.id} 
                                            href={`/my-list/${entry.id}`}
                                            className="group space-y-3"
                                        >
                                            <div className="aspect-[2/3] rounded-2xl overflow-hidden bg-surface-container border border-outline-variant/5 relative shadow-lg">
                                                {entry.cover_url ? (
                                                    <img src={entry.cover_url} alt={entry.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center bg-surface-container-highest" />
                                                )}
                                                <div className="absolute top-2 right-2 size-7 rounded-lg bg-black/60 backdrop-blur-md flex items-center justify-center border border-white/5">
                                                    <span className="text-[10px] font-black text-primary">{entry.rating}</span>
                                                </div>
                                            </div>
                                            <p className="text-[11px] font-bold text-on-surface text-center truncate px-1 group-hover:text-primary transition-colors">
                                                {entry.title}
                                            </p>
                                        </Link>
                                    ))}
                                </div>
                            )}
                        </section>

                        {/* Recent Activity */}
                        <section className="space-y-6">
                            <h3 className="font-headline font-black text-2xl text-on-surface tracking-tight px-2 flex items-center gap-3">
                                <span className="material-symbols-outlined text-primary">history</span>
                                Activity
                            </h3>
                            
                            <div className="space-y-3">
                                {recentActivity.map((entry: any) => (
                                    <Link 
                                        key={entry.id} 
                                        href={`/my-list/${entry.id}`}
                                        className="flex items-center gap-4 p-4 bg-surface-container hover:bg-surface-container-highest rounded-2xl border border-outline-variant/5 transition-all group"
                                    >
                                        <div className="size-12 rounded-xl overflow-hidden shrink-0 bg-surface-container-highest">
                                            {entry.cover_url && <img src={entry.cover_url} alt="" className="w-full h-full object-cover" />}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <h4 className="font-bold text-sm text-on-surface truncate group-hover:text-primary transition-colors">
                                                {entry.title}
                                            </h4>
                                            <p className="text-[11px] text-on-surface-variant font-medium mt-0.5">
                                                {entry.status.replace('_', ' ')} • {new Date(entry.updated_at).toLocaleDateString()}
                                            </p>
                                        </div>
                                        <span className="material-symbols-outlined text-on-surface-variant group-hover:translate-x-1 transition-transform">chevron_right</span>
                                    </Link>
                                ))}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
