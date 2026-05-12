import AppLayout from '@/layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

interface SettingsLayoutProps {
    children: React.ReactNode;
    title: string;
    description: string;
}

export default function SettingsLayout({ children, title, description }: SettingsLayoutProps) {
    const navItems = [
        { label: 'Profile', href: '/settings/profile', icon: 'person' },
        { label: 'Appearance', href: '/settings/appearance', icon: 'palette' },
        { label: 'Security', href: '/settings/security', icon: 'security' },
    ];

    const currentPath = window.location.pathname;

    return (
        <AppLayout>
            <div className="max-w-5xl mx-auto animate-fade-in">
                <div className="flex flex-col md:flex-row gap-10">
                    {/* Sidebar Navigation */}
                    <aside className="w-full md:w-64 shrink-0 space-y-1">
                        <div className="px-4 mb-6">
                            <h1 className="text-2xl font-headline font-black text-on-surface tracking-tight">Settings</h1>
                            <p className="text-xs text-on-surface-variant font-medium mt-1">Manage your account and app preferences</p>
                        </div>
                        
                        <nav className="space-y-1">
                            {navItems.map((item) => {
                                const isActive = currentPath === item.href;
                                return (
                                    <Link
                                        key={item.href}
                                        href={item.href}
                                        className={cn(
                                            "flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-300 font-bold text-sm",
                                            isActive 
                                                ? "bg-primary text-on-primary shadow-lg shadow-primary/20" 
                                                : "text-on-surface-variant hover:bg-surface-container hover:text-on-surface"
                                        )}
                                    >
                                        <span className="material-symbols-outlined text-[20px]">{item.icon}</span>
                                        {item.label}
                                    </Link>
                                );
                            })}
                        </nav>
                    </aside>

                    {/* Content Area */}
                    <main className="flex-1 space-y-8">
                        <div className="bg-surface-container rounded-[2rem] border border-outline-variant/5 overflow-hidden">
                            <div className="p-6 md:p-10 border-b border-outline-variant/5">
                                <h2 className="text-xl font-headline font-black text-on-surface">{title}</h2>
                                <p className="text-sm text-on-surface-variant mt-1">{description}</p>
                            </div>
                            
                            <div className="p-6 md:p-10">
                                {children}
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </AppLayout>
    );
}
