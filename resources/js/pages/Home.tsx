import { useEffect } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { SharedPageProps } from '@/types/SharedProps';

export default function Home() {
    const { auth } = usePage().props as unknown as SharedPageProps;
    const isAuthenticated = !!auth?.user;

    useEffect(() => {
        const handleMouseMove = (e: MouseEvent) => {
            const cards = document.querySelectorAll<HTMLElement>('.card-float, .card-float-delayed');
            const x = (window.innerWidth / 2 - e.pageX) / 50;
            const y = (window.innerHeight / 2 - e.pageY) / 50;
            cards.forEach((card) => {
                card.style.transform = `translate(${x}px, ${y}px)`;
            });
        };
        document.addEventListener('mousemove', handleMouseMove);
        return () => document.removeEventListener('mousemove', handleMouseMove);
    }, []);

    return (
        <>
            <Head title="Bibliotaku — Tu bitácora otaku" />

            <style>{`
                .violet-gradient {
                    background: linear-gradient(135deg, #ba9eff 0%, #d3bfff 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }
                .violet-btn-gradient {
                    background: linear-gradient(135deg, #ba9eff 0%, #d3bfff 100%);
                }
                .radial-glow {
                    position: absolute;
                    width: 600px;
                    height: 600px;
                    background: radial-gradient(circle, rgba(186,158,255,0.1) 0%, rgba(186,158,255,0) 70%);
                    filter: blur(80px);
                    z-index: -1;
                    pointer-events: none;
                }
                .card-float {
                    animation: cardFloat 6s ease-in-out infinite;
                }
                @keyframes cardFloat {
                    0%, 100% { transform: translateY(0px) rotate(-2deg); }
                    50%       { transform: translateY(-20px) rotate(1deg); }
                }
                .card-float-delayed {
                    animation: cardFloatDelayed 8s ease-in-out infinite;
                }
                @keyframes cardFloatDelayed {
                    0%, 100% { transform: translateY(0px) rotate(3deg); }
                    50%       { transform: translateY(-15px) rotate(-1deg); }
                }
            `}</style>

            {/* Ambient atmosphere */}
            <div className="radial-glow" style={{ top: '-200px', left: '-100px' }} />
            <div className="radial-glow" style={{ bottom: '-100px', right: '-100px' }} />

            {/* Navbar */}
            <header className="fixed top-0 left-0 right-0 bg-background/80 backdrop-blur-xl z-50">
                <div className="flex justify-between items-center px-8 md:px-16 py-4 max-w-[1200px] mx-auto">
                <div className="font-headline text-2xl font-extrabold text-primary">Bibliotaku</div>
                <nav className="hidden md:flex gap-10 items-center">
                    <a href="#" className="text-primary font-bold border-b-2 border-primary pb-1 font-headline text-xl transition-colors duration-300">
                        Home
                    </a>
                    <a href="#features" className="text-on-surface-variant font-medium font-headline text-xl hover:text-primary transition-colors duration-300">
                        Features
                    </a>
                </nav>
                <div className="flex items-center gap-6">
                    {isAuthenticated ? (
                        <Link
                            href="/dashboard"
                            className="violet-btn-gradient text-on-primary font-bold px-6 py-2 rounded-full font-body text-base shadow-lg active:scale-95 transition-transform"
                        >
                            Ir al dashboard
                        </Link>
                    ) : (
                        <>
                            <Link
                                href="/login"
                                className="text-on-surface-variant font-medium font-body text-base opacity-80 hover:opacity-100 transition-opacity"
                            >
                                Sign In
                            </Link>
                            <Link
                                href="/register"
                                className="violet-btn-gradient text-on-primary font-bold px-6 py-2 rounded-full font-body text-base shadow-lg active:scale-95 transition-transform"
                            >
                                Get Started
                            </Link>
                        </>
                    )}
                </div>
                </div>
            </header>

            {/* Hero */}
            <main className="relative pt-28 pb-10 px-4 md:px-16 max-w-[1200px] mx-auto md:min-h-screen flex flex-col md:flex-row items-center gap-10 overflow-x-hidden md:overflow-visible">
                {/* Left: copy */}
                <div className="flex-1 space-y-6 z-10 text-center md:text-left w-full">
                    <div className="inline-block px-4 py-1 rounded-full bg-secondary-container/30 border border-primary/20">
                        <span className="font-label text-xs text-primary tracking-widest uppercase">
                            TU BITÁCORA OTAKU
                        </span>
                    </div>
                    <h1 className="font-headline text-[58px] md:text-[96px] font-extrabold leading-[0.9] tracking-tighter text-on-surface">
                        EVERY STORY,<br />
                        <span className="violet-gradient">LOGGED.</span>
                    </h1>
                    <p className="font-body text-base md:text-lg text-on-surface-variant max-w-xl mx-auto md:mx-0">
                        Anime, manga, manhwa, manhua y novelas. Una sola bitácora que recuerda dónde quedaste — y todo lo que ya terminaste.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-3 pt-2 justify-center md:justify-start">
                        {isAuthenticated ? (
                            <Link
                                href="/dashboard"
                                className="violet-btn-gradient text-on-primary font-bold px-8 py-3.5 rounded-full font-headline text-lg shadow-xl hover:brightness-110 active:scale-95 transition-all text-center"
                            >
                                Volver a mi bitácora
                            </Link>
                        ) : (
                            <Link
                                href="/register"
                                className="violet-btn-gradient text-on-primary font-bold px-8 py-3.5 rounded-full font-headline text-lg shadow-xl hover:brightness-110 active:scale-95 transition-all text-center"
                            >
                                Abrir mi bitácora
                            </Link>
                        )}
                        <Link
                            href="/login"
                            className="bg-surface-container-high text-on-surface font-semibold px-8 py-3.5 rounded-full font-headline text-lg hover:bg-surface-variant active:scale-95 transition-all text-center"
                        >
                            Iniciar sesión
                        </Link>
                    </div>

                    {/* Mobile: horizontal scroll cards */}
                    <div className="md:hidden flex gap-3 overflow-x-auto pb-3 -mx-4 px-4 pt-2" style={{ scrollbarWidth: 'none' }}>
                        {[
                            {
                                src: 'https://lh3.googleusercontent.com/aida-public/AB6AXuB6KtBfyQ6nRaIYAd7EWh15XzhmId_pqRbXIl9Z6nug4zLF-Oq81ExMDWsCQy9-67zethWnNEPeD_sfocXave8_qJmSdxMKxucwwGw8UsSsmAy_O8Nnaob2GjS9VU2pDMNtjKGIWFppJHwzmsiCwrd4M29s_Mgi-GoCg20co1u75nGvu5jTjIIaxUgeWVzy11LlrmcdlEwfAE7OgU93TPqX3Xhr14fCS2La3fhamrG0ImGxuFQkqz4ijtrkJhNyYWN6bMui5GAUsNQA',
                                title: 'Solo Leveling', progress: 'Cap. 147 / 179', pct: 'w-[85%]', completed: false,
                            },
                            {
                                src: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBtNj-RkbtPczDvilQtjoOpWCJXjItS5FnU0Jgi9ONEkdiBZHSS57P_Rxf0pKRiIRm8WgxmsFdwb5Al8mkU-xA3L-kE1lf3bfmUVzWMJiBzCKJpQkz3_tL_grcs2tQ4jutBI2WJf-uMva7xxjb6R1dSY5ohOIdbC9pSAMbjxI_GZwO7_roTLeHk5tpE7j1aTyJIumfv02aQ9OkQOV0upY95_2nW5cm1gLrlTbVxmSXOnOB6xZ0oCnpIP39C8fR5ooMdlPP0pJl8UTDO',
                                title: 'The Beginning After The End', progress: 'Vol. 4 / 10', pct: 'w-[40%]', completed: false,
                            },
                            {
                                src: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZavnhYrGS-USA8NeFik7SHXjHKAY9j1BCK1S64zRl3MqkNAzilgnopNyrwtXbXnROp7p6NOnyAZIW3vQXRaZnLFThE7_-ErAnQ1sIWqjx86LwS1AoqD8e_8aSV84r5vyVS9MMJr_FtXBpifJKoV4HftkUd5QN37jUL1sr8DSV6_eTuhXe2I9tWAaam99WGXb1qqBqmRV0T-Or-GHTG2DfpgKVnh17gKlGfz3hq7uccg0tA12zMgaypdKx4vX23QGoy1eL0NW_9NNm',
                                title: 'Cyberpunk Edgerunners', progress: 'Ep. 10 / 10', pct: 'w-full', completed: true,
                            },
                        ].map((card) => (
                            <div key={card.title} className="flex-shrink-0 w-44 bg-surface-container-lowest rounded-xl p-2 shadow-lg">
                                <img src={card.src} alt={card.title} className="w-full h-32 object-cover rounded-lg mb-2" />
                                <h4 className="font-headline text-sm text-on-surface truncate">{card.title}</h4>
                                <div className="w-full bg-surface-container-highest h-1.5 rounded-full overflow-hidden mt-1.5">
                                    <div className={`h-full violet-btn-gradient ${card.pct}`} />
                                </div>
                                <div className="flex justify-between items-center mt-1">
                                    <p className="font-label text-[10px] text-on-surface-variant">{card.progress}</p>
                                    {card.completed && (
                                        <span className="material-symbols-outlined text-primary text-sm" style={{ fontVariationSettings: "'FILL' 1" }}>check_circle</span>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Right: floating cards — desktop only */}
                <div className="hidden md:flex flex-1 relative w-full h-[600px] justify-center items-center">
                    {/* Card 1 — Solo Leveling */}
                    <div className="card-float-delayed absolute left-[5%] top-[10%] w-[260px] bg-surface-container-lowest p-2 rounded-xl shadow-[0px_40px_80px_rgba(0,0,0,0.5)] z-0">
                        <img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6KtBfyQ6nRaIYAd7EWh15XzhmId_pqRbXIl9Z6nug4zLF-Oq81ExMDWsCQy9-67zethWnNEPeD_sfocXave8_qJmSdxMKxucwwGw8UsSsmAy_O8Nnaob2GjS9VU2pDMNtjKGIWFppJHwzmsiCwrd4M29s_Mgi-GoCg20co1u75nGvu5jTjIIaxUgeWVzy11LlrmcdlEwfAE7OgU93TPqX3Xhr14fCS2La3fhamrG0ImGxuFQkqz4ijtrkJhNyYWN6bMui5GAUsNQA"
                            alt="Solo Leveling"
                            className="w-full h-48 object-cover rounded-lg mb-2"
                        />
                        <div className="px-1 space-y-1">
                            <h4 className="font-headline text-xl text-on-surface truncate">Solo Leveling</h4>
                            <div className="w-full bg-surface-container-highest h-2 rounded-full overflow-hidden">
                                <div className="h-full violet-btn-gradient w-[85%]" />
                            </div>
                            <p className="font-label text-xs text-on-surface-variant">Cap. 147 / 179</p>
                        </div>
                    </div>

                    {/* Card 2 — The Beginning After The End */}
                    <div className="card-float absolute right-[10%] top-[25%] w-[280px] bg-surface-container-lowest p-2 rounded-xl shadow-[0px_60px_100px_rgba(0,0,0,0.7)] z-20">
                        <img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBtNj-RkbtPczDvilQtjoOpWCJXjItS5FnU0Jgi9ONEkdiBZHSS57P_Rxf0pKRiIRm8WgxmsFdwb5Al8mkU-xA3L-kE1lf3bfmUVzWMJiBzCKJpQkz3_tL_grcs2tQ4jutBI2WJf-uMva7xxjb6R1dSY5ohOIdbC9pSAMbjxI_GZwO7_roTLeHk5tpE7j1aTyJIumfv02aQ9OkQOV0upY95_2nW5cm1gLrlTbVxmSXOnOB6xZ0oCnpIP39C8fR5ooMdlPP0pJl8UTDO"
                            alt="The Beginning After The End"
                            className="w-full h-56 object-cover rounded-lg mb-2"
                        />
                        <div className="px-1 space-y-1">
                            <h4 className="font-headline text-xl text-on-surface truncate">The Beginning After The End</h4>
                            <div className="w-full bg-surface-container-highest h-2 rounded-full overflow-hidden">
                                <div className="h-full violet-btn-gradient w-[40%]" />
                            </div>
                            <p className="font-label text-xs text-on-surface-variant">Vol. 4 / 10</p>
                        </div>
                    </div>

                    {/* Card 3 — Cyberpunk Edgerunners */}
                    <div className="card-float-delayed absolute left-[20%] bottom-[5%] w-[240px] bg-surface-container-lowest p-2 rounded-xl shadow-[0px_40px_90px_rgba(0,0,0,0.6)] z-30">
                        <img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDZavnhYrGS-USA8NeFik7SHXjHKAY9j1BCK1S64zRl3MqkNAzilgnopNyrwtXbXnROp7p6NOnyAZIW3vQXRaZnLFThE7_-ErAnQ1sIWqjx86LwS1AoqD8e_8aSV84r5vyVS9MMJr_FtXBpifJKoV4HftkUd5QN37jUL1sr8DSV6_eTuhXe2I9tWAaam99WGXb1qqBqmRV0T-Or-GHTG2DfpgKVnh17gKlGfz3hq7uccg0tA12zMgaypdKx4vX23QGoy1eL0NW_9NNm"
                            alt="Cyberpunk Edgerunners"
                            className="w-full h-40 object-cover rounded-lg mb-2"
                        />
                        <div className="px-1 space-y-1">
                            <h4 className="font-headline text-xl text-on-surface truncate">Cyberpunk Edgerunners</h4>
                            <div className="w-full bg-surface-container-highest h-2 rounded-full overflow-hidden">
                                <div className="h-full violet-btn-gradient w-full" />
                            </div>
                            <div className="flex justify-between items-center">
                                <p className="font-label text-xs text-on-surface-variant">Ep. 10 / 10</p>
                                <span
                                    className="material-symbols-outlined text-primary text-lg"
                                    style={{ fontVariationSettings: "'FILL' 1" }}
                                >
                                    check_circle
                                </span>
                            </div>
                        </div>
                    </div>

                    {/* Atmospheric halo */}
                    <div className="absolute w-[120%] h-[120%] bg-primary/5 rounded-full blur-[120px] -z-10 animate-pulse" />
                </div>
            </main>

            {/* Footer */}
            <footer className="w-full py-10 bg-surface-container-lowest mt-10">
                <div className="flex flex-col md:flex-row justify-between items-center px-6 md:px-16 w-full max-w-[1200px] mx-auto gap-6 md:gap-4">
                    <div className="text-center md:text-left">
                        <div className="font-headline text-3xl font-bold text-on-surface mb-2">Bibliotaku</div>
                        <p className="font-body text-base text-on-surface-variant opacity-80">
                            © {new Date().getFullYear()} Bibliotaku. A Cinematic Editorial Diary.
                        </p>
                    </div>
                    <nav className="flex flex-wrap justify-center md:justify-end gap-x-6 gap-y-2">
                        {['Privacy Policy', 'Terms of Service', 'Contact'].map((link) => (
                            <a
                                key={link}
                                href="#"
                                className="text-on-surface-variant hover:text-on-surface transition-colors font-body text-base"
                            >
                                {link}
                            </a>
                        ))}
                    </nav>
                </div>
            </footer>
        </>
    );
}
