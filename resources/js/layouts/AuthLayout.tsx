import React from 'react';
import { Link } from '@inertiajs/react';

interface AuthLayoutProps {
    children: React.ReactNode;
    title?: string;
}

export default function AuthLayout({ children, title }: AuthLayoutProps) {
    return (
        <div className="min-h-screen bg-background text-on-surface font-body selection:bg-primary/30 flex flex-col">
            {/* Cinematic background */}
            <div
                className="fixed inset-0 z-0"
                style={{
                    backgroundImage: `linear-gradient(rgba(14,14,14,0.88), rgba(14,14,14,0.97)),
                        url('https://lh3.googleusercontent.com/aida-public/AB6AXuCyVTL18CYremyiw3fCOIwoqaGFbXe9ns6uwIuEZDOkBgAt1-ZMnqR5Ij7PdWUnvykzrCjWRaZhNVs3NhIVfXgDFmM_FLCKQ2ma9fTS1GqA_URxso2uek9wDCv206QOwWZNXGSrth9xYXVvlY2jtoEtq0IvAtMeHa8uw0YxIaVK8EF2zaUcZB9c9XYCZbaz6ZKcO65UcGeU2nxOqGI6vHQPEnVtlYIT823NU8oaM_VP5cxmz2d3LY8ipKgyBjS0LbBLrs40CyXO5p0')`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                }}
            />

            <main className="relative z-10 flex-grow flex items-center justify-center px-6 py-12">
                <div className="w-full max-w-md">
                    {/* Brand */}
                    <div className="text-center mb-10">
                        <Link
                            href="/"
                            className="font-headline font-black text-4xl tracking-tighter text-primary hover:scale-105 transition-transform inline-block mb-1"
                        >
                            The Archive
                        </Link>
                        <p className="font-label text-sm uppercase tracking-[0.2em] text-on-surface-variant font-medium">
                            Digital Curator
                        </p>
                    </div>

                    {/* Card */}
                    <div className="bg-surface-container/70 backdrop-blur-2xl rounded-3xl p-8 md:p-10 shadow-2xl shadow-black/60 border border-outline-variant/10">
                        {title && (
                            <h1 className="font-headline text-2xl font-extrabold tracking-tight text-on-surface mb-6">
                                {title}
                            </h1>
                        )}
                        {children}
                    </div>
                </div>
            </main>

            {/* Footer */}
            <footer className="relative z-10 pb-8 flex justify-center gap-6 opacity-40 hover:opacity-100 transition-opacity">
                {['Privacy', 'Terms', 'API'].map((item) => (
                    <a
                        key={item}
                        href="#"
                        className="text-[10px] uppercase tracking-[0.2em] font-label text-on-surface-variant hover:text-primary transition-colors"
                    >
                        {item}
                    </a>
                ))}
            </footer>
        </div>
    );
}
