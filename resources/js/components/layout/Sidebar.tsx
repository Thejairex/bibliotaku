import { Link, usePage } from '@inertiajs/react';
import { cn } from '@/lib/utils';
import { AppLogo } from './AppLogo';

interface NavItemProps {
    href: string;
    icon: string;
    label: string;
    active?: boolean;
}

function NavItem({ href, icon, label, active }: NavItemProps) {
    return (
        <Link
            href={href}
            // @ts-ignore
            preserveScroll
            className={cn(
                'flex items-center gap-3 px-4 py-3 rounded-2xl font-bold transition-all group',
                active
                    ? 'bg-primary/10 text-primary'
                    : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
            )}
        >
            <span className="material-symbols-outlined text-[22px]">{icon}</span>
            <span className="text-sm">{label}</span>
        </Link>
    );
}

import { UserMenu } from './UserMenu';

export function Sidebar({ className }: { className?: string }) {
    const { url } = usePage();

    const navGroups = [
        {
            label: 'Platform',
            items: [
                { href: '/dashboard', icon: 'home', label: 'Home' },
                { href: '/search', icon: 'search', label: 'Search' },
                { href: '/my-list', icon: 'inventory_2', label: 'My List' },
            ],
        },
        {
            label: 'Account',
            items: [
                { href: '/settings/profile', icon: 'person', label: 'Profile' },
            ],
        },
    ];

    return (
        <aside className={cn('flex flex-col h-full p-6 bg-surface-container border-r border-outline-variant/10', className)}>
            <div className="mb-12">
                <AppLogo href="/dashboard" />
            </div>

            <nav className="flex-1 space-y-8">
                {navGroups.map((group) => (
                    <div key={group.label}>
                        <span className="px-4 text-[10px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 mb-4 block">
                            {group.label}
                        </span>
                        <div className="space-y-1">
                            {group.items.map((item) => (
                                <NavItem
                                    key={item.href}
                                    href={item.href}
                                    icon={item.icon}
                                    label={item.label}
                                    active={url.startsWith(item.href)}
                                />
                            ))}
                        </div>
                    </div>
                ))}
            </nav>

            <div className="mt-auto pt-6 border-t border-outline-variant/5 space-y-4">
                <UserMenu />
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    className="flex w-full items-center gap-3 px-4 py-3 rounded-2xl font-bold text-error/70 hover:bg-error/10 hover:text-error transition-all group"
                >
                    <span className="material-symbols-outlined text-[22px]">logout</span>
                    <span className="text-sm">Log Out</span>
                </Link>
            </div>
        </aside>
    );
}
