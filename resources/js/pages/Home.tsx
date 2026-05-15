import { Head, Link, usePage } from '@inertiajs/react';
import { SharedPageProps } from '@/types/SharedProps';

export default function Home() {
    const { auth } = usePage().props as unknown as SharedPageProps;
    const isAuthenticated = !!auth?.user;

    return (
        <>
            <Head title="Home" />

            {/* Navbar */}
            <nav className="fixed top-0 w-full z-40 bg-surface/70 backdrop-blur-2xl flex justify-between items-center px-8 h-20 font-headline font-medium">
                <div className="flex items-center gap-8">
                    <span className="text-xl font-black text-primary">Bibliotaku</span>
                    <div className="hidden md:flex items-center gap-6">
                        <a href="#" className="text-primary hover:text-primary-dim transition-colors text-sm">Home</a>
                        <a href="#features" className="text-on-surface-variant hover:text-primary transition-colors text-sm">Features</a>
                    </div>
                </div>

                <div className="flex items-center gap-4">
                    {isAuthenticated ? (
                        <Link
                            href="/dashboard"
                            className="bg-gradient-to-br from-primary to-primary-dim text-on-primary font-label font-bold px-6 py-2.5 rounded-full text-sm hover:shadow-lg hover:shadow-primary/20 transition-all active:scale-95"
                        >
                            Go to Dashboard
                        </Link>
                    ) : (
                        <>
                            <Link
                                href="/login"
                                className="text-on-surface-variant hover:text-on-surface font-label text-sm font-medium transition-colors"
                            >
                                Sign In
                            </Link>
                            <Link
                                href="/register"
                                className="bg-gradient-to-br from-primary to-primary-dim text-on-primary font-label font-bold px-6 py-2.5 rounded-full text-sm hover:shadow-lg hover:shadow-primary/20 transition-all active:scale-95"
                            >
                                Get Started
                            </Link>
                        </>
                    )}
                </div>
            </nav>

            <main className="pt-20">
                {/* Hero */}
                <section className="relative min-h-[90vh] flex items-center px-8 md:px-24 overflow-hidden">
                    {/* Background blobs */}
                    <div className="absolute inset-0 z-0 pointer-events-none">
                        <div className="absolute top-1/4 right-1/4 w-[600px] h-[600px] bg-primary/10 rounded-full blur-[120px] animate-pulse" />
                        <div className="absolute bottom-0 right-0 w-[400px] h-[400px] bg-secondary/8 rounded-full blur-[100px]" />
                        <div className="absolute top-0 left-1/3 w-[300px] h-[300px] bg-tertiary/5 rounded-full blur-[80px]" />
                        <div className="absolute inset-0 bg-gradient-to-r from-background via-background/90 to-background/30" />
                        <div className="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent" />
                    </div>

                    <div className="relative z-10 max-w-3xl space-y-8">
                        <div className="space-y-4">
                            <span className="inline-block px-4 py-1 rounded-full bg-primary/10 text-primary font-label text-xs font-bold tracking-widest uppercase">
                                Digital Curator
                            </span>
                            <h1 className="font-headline text-6xl md:text-8xl font-black tracking-tighter leading-none">
                                CURATE YOUR <br />
                                <span className="text-transparent bg-clip-text bg-gradient-to-br from-primary to-primary-dim">
                                    IMAGINATION.
                                </span>
                            </h1>
                            <p className="text-on-surface-variant text-lg md:text-xl font-body max-w-xl leading-relaxed">
                                Track anime, manga, manhwa, manhua, and novels in one high-editorial experience. Bibliotaku turns your collection into a gallery-grade archive.
                            </p>
                        </div>

                        <div className="flex flex-col sm:flex-row gap-4 pt-4">
                            <Link
                                href="/register"
                                className="bg-gradient-to-br from-primary to-primary-dim px-10 py-5 rounded-full font-label font-bold text-on-primary shadow-xl shadow-primary/20 hover:scale-105 hover:shadow-primary/40 transition-all active:scale-95 text-center"
                            >
                                Start Your Journey
                            </Link>
                            <Link
                                href="/login"
                                className="bg-surface-container-high hover:bg-surface-container-highest px-10 py-5 rounded-full font-label font-bold text-on-surface transition-all active:scale-95 text-center"
                            >
                                Sign In
                            </Link>
                        </div>
                    </div>

                    {/* Decorative floating cards on the right */}
                    <div className="absolute right-8 md:right-24 top-1/2 -translate-y-1/2 hidden lg:flex gap-4 items-end opacity-70">
                        {[
                            { h: 'h-56', delay: 'delay-0', label: 'Anime' },
                            { h: 'h-72', delay: 'delay-75', label: 'Manga' },
                            { h: 'h-48', delay: 'delay-150', label: 'Novel' },
                        ].map(({ h, delay, label }) => (
                            <div
                                key={label}
                                className={`w-32 ${h} bg-surface-container rounded-xl flex-shrink-0 flex flex-col justify-end p-4 shadow-2xl hover:-translate-y-3 transition-transform duration-500 ${delay}`}
                            >
                                <div className="w-full h-3/4 bg-gradient-to-br from-primary/20 to-primary-dim/30 rounded-lg mb-3 flex items-center justify-center">
                                    <span className="material-symbols-outlined text-primary text-3xl">
                                        {label === 'Anime' ? 'play_circle' : label === 'Manga' ? 'library_books' : 'menu_book'}
                                    </span>
                                </div>
                                <p className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-widest">{label}</p>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Features Bento Grid */}
                <section id="features" className="px-8 md:px-24 py-32 space-y-16">
                    <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div className="space-y-2">
                            <span className="text-primary font-label text-sm font-bold tracking-widest uppercase">Key Features</span>
                            <h2 className="font-headline text-4xl md:text-5xl font-bold tracking-tight">The Modern Standard.</h2>
                        </div>
                        <p className="text-on-surface-variant max-w-sm text-sm leading-relaxed">
                            Designed for collectors who value aesthetics as much as content. Minimal noise, maximum impact.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-12 gap-6 md:h-[600px]">
                        {/* Track Your Anime */}
                        <div className="md:col-span-7 bg-surface-container rounded-xl overflow-hidden group flex flex-col relative">
                            <div className="p-10 space-y-4 relative z-10">
                                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span className="material-symbols-outlined text-primary">play_circle</span>
                                </div>
                                <h3 className="font-headline text-3xl font-bold">Track Your Anime</h3>
                                <p className="text-on-surface-variant max-w-xs">
                                    Episode tracking with beautiful visual progress indicators. Know exactly where you left off.
                                </p>
                            </div>
                            <div className="mt-auto flex-1 flex items-end justify-end px-6 pb-0 overflow-hidden">
                                <div className="relative w-3/4 h-48 transform translate-x-8 translate-y-8 group-hover:translate-x-4 group-hover:translate-y-4 transition-transform duration-500">
                                    <div className="absolute inset-0 bg-gradient-to-br from-primary/20 via-primary-dim/30 to-surface-container-highest rounded-tl-2xl" />
                                    <div className="absolute inset-4 flex items-center justify-center">
                                        <span className="material-symbols-outlined text-primary/60 text-8xl">slow_motion_video</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Manage Reading */}
                        <div className="md:col-span-5 bg-surface-container-high rounded-xl p-10 flex flex-col justify-between group">
                            <div className="space-y-4">
                                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span className="material-symbols-outlined text-primary">library_books</span>
                                </div>
                                <h3 className="font-headline text-3xl font-bold">Manage Your Reading</h3>
                                <p className="text-on-surface-variant text-sm">
                                    Your digital shelf for Manga, Manhwa, Manhua, and Light Novels. Organized by chapter, volume, or status.
                                </p>
                            </div>
                            <div className="mt-8 flex gap-4 overflow-hidden">
                                {[
                                    { from: 'from-purple-900/40', to: 'to-violet-800/40', delay: 'group-hover:-translate-y-2 duration-300' },
                                    { from: 'from-violet-800/40', to: 'to-indigo-900/40', delay: 'group-hover:-translate-y-4 duration-500 delay-75' },
                                    { from: 'from-indigo-900/40', to: 'to-purple-900/40', delay: 'group-hover:-translate-y-6 duration-700 delay-150' },
                                ].map((item, i) => (
                                    <div
                                        key={i}
                                        className={`w-28 h-40 bg-surface rounded-xl flex-shrink-0 flex items-center justify-center shadow-lg transition-transform ${item.delay}`}
                                    >
                                        <div className={`w-full h-full rounded-xl bg-gradient-to-br ${item.from} ${item.to} flex items-center justify-center`}>
                                            <span className="material-symbols-outlined text-primary/60 text-3xl">menu_book</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Universal Hub */}
                        <div className="md:col-span-12 bg-surface-container/60 rounded-xl p-10 flex flex-col md:flex-row items-center gap-12 group">
                            <div className="flex-1 space-y-6">
                                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                                    <span className="material-symbols-outlined text-primary">hub</span>
                                </div>
                                <h3 className="font-headline text-4xl font-bold">MAL Integration</h3>
                                <p className="text-on-surface-variant text-lg leading-relaxed">
                                    Search and import directly from MyAnimeList. Find your next series, add it to your archive, and start tracking instantly.
                                </p>
                                <div className="flex flex-wrap gap-4">
                                    {['MAL SEARCH', 'AUTO METADATA', 'COVER ART'].map((tag) => (
                                        <div key={tag} className="bg-surface-container-highest px-6 py-2 rounded-full text-xs font-bold text-primary">
                                            {tag}
                                        </div>
                                    ))}
                                </div>
                            </div>
                            <div className="flex-1 relative h-48 w-full flex items-center justify-center">
                                <div className="absolute w-48 h-48 bg-primary/15 rounded-full blur-[80px] animate-pulse" />
                                <span className="material-symbols-outlined text-8xl text-primary group-hover:rotate-180 transition-transform duration-1000">
                                    sync
                                </span>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Stats strip */}
                <section className="px-8 md:px-24 py-16">
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
                        {[
                            { label: 'Media Types', value: '5', icon: 'category' },
                            { label: 'Status Options', value: '6', icon: 'checklist' },
                            { label: 'MAL Integration', value: '✓', icon: 'check_circle' },
                            { label: 'Free to Use', value: '∞', icon: 'all_inclusive' },
                        ].map(({ label, value, icon }) => (
                            <div key={label} className="bg-surface-container rounded-xl p-8 flex flex-col items-center gap-3 text-center">
                                <span className="material-symbols-outlined text-primary text-3xl">{icon}</span>
                                <span className="font-headline text-4xl font-black text-on-surface">{value}</span>
                                <span className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-widest">{label}</span>
                            </div>
                        ))}
                    </div>
                </section>

                {/* Final CTA */}
                <section className="px-8 md:px-24 py-32 text-center relative overflow-hidden">
                    <div className="absolute inset-0 z-0 pointer-events-none">
                        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px]" />
                    </div>
                    <div className="relative z-10 space-y-8">
                        <h2 className="font-headline text-5xl md:text-7xl font-black tracking-tight max-w-4xl mx-auto">
                            READY TO CURATE YOUR COLLECTION?
                        </h2>
                        <p className="text-on-surface-variant text-xl max-w-2xl mx-auto">
                            Join collectors who have upgraded their digital experience.
                        </p>
                        <div className="pt-8">
                            <Link
                                href="/register"
                                className="inline-block bg-gradient-to-br from-primary to-primary-dim px-16 py-6 rounded-full font-label font-black text-on-primary text-xl shadow-2xl shadow-primary/30 hover:scale-105 hover:shadow-primary/50 transition-all active:scale-95"
                            >
                                Start Your Journey
                            </Link>
                        </div>
                    </div>
                </section>
            </main>

            {/* Footer */}
            <footer className="w-full py-12 px-8 bg-surface-container-low">
                <div className="flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto">
                    <div className="flex flex-col items-center md:items-start">
                        <span className="text-lg font-black text-primary mb-2 font-headline">Bibliotaku</span>
                        <p className="font-body text-xs text-on-surface-variant">
                            © {new Date().getFullYear()} Bibliotaku. Digital Curation for the Modern Collector.
                        </p>
                    </div>
                    <div className="flex gap-8 mt-8 md:mt-0">
                        {['About', 'Privacy', 'Terms'].map((link) => (
                            <a
                                key={link}
                                href="#"
                                className="font-body text-xs text-on-surface-variant hover:text-on-surface transition-colors"
                            >
                                {link}
                            </a>
                        ))}
                    </div>
                </div>
            </footer>

            {/* Mobile bottom nav */}
            <nav className="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-6 pb-8 pt-4 bg-surface/80 backdrop-blur-xl rounded-t-xl z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.6)]">
                <div className="flex flex-col items-center justify-center bg-primary/20 text-primary rounded-full px-6 py-2 transition-transform active:scale-95">
                    <span className="material-symbols-outlined">home</span>
                    <span className="font-body text-[10px] uppercase tracking-widest mt-1">Home</span>
                </div>
                <Link href="/login" className="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform">
                    <span className="material-symbols-outlined">login</span>
                    <span className="font-body text-[10px] uppercase tracking-widest mt-1">Sign In</span>
                </Link>
                <Link href="/register" className="flex flex-col items-center justify-center text-on-surface-variant active:scale-95 transition-transform">
                    <span className="material-symbols-outlined">person_add</span>
                    <span className="font-body text-[10px] uppercase tracking-widest mt-1">Register</span>
                </Link>
            </nav>
        </>
    );
}
