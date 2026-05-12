import { Fragment } from 'react';
import { Menu, Transition } from '@headlessui/react';
import { Link, usePage } from '@inertiajs/react';
import { SharedPageProps } from '@/types/SharedProps';
import { cn } from '@/lib/utils';

export function UserMenu({ className }: { className?: string }) {
    const { auth } = usePage<SharedPageProps>().props;
    const user = auth.user;

    // Helper for initials
    const initials = user.name
        .split(' ')
        .map((n) => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);

    return (
        <Menu as="div" className={cn('relative', className)}>
            <Menu.Button className="flex items-center gap-3 p-2 rounded-2xl hover:bg-surface-container-high transition-all group outline-none">
                <div className="size-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-xs border border-primary/20">
                    {initials}
                </div>
                <div className="hidden lg:flex flex-col text-left">
                    <span className="text-sm font-bold text-on-surface truncate max-w-[120px]">{user.name}</span>
                    <span className="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">Premium Member</span>
                </div>
                <span className="material-symbols-outlined text-on-surface-variant group-hover:text-on-surface transition-colors">
                    unfold_more
                </span>
            </Menu.Button>

            <Transition
                as={Fragment}
                enter="transition ease-out duration-100"
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
            >
                <Menu.Items className="absolute bottom-full left-0 mb-4 w-56 rounded-2xl bg-surface-container-highest border border-outline-variant/10 shadow-2xl p-2 outline-none">
                    <div className="px-3 py-2 mb-2">
                        <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/50">Account</p>
                    </div>
                    
                    <Menu.Item>
                        {({ active }) => (
                            <Link
                                href="/settings/profile"
                                className={cn(
                                    'flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-bold transition-all',
                                    active ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:text-on-surface'
                                )}
                            >
                                <span className="material-symbols-outlined text-[20px]">manage_accounts</span>
                                Settings
                            </Link>
                        )}
                    </Menu.Item>

                    <div className="my-2 border-t border-outline-variant/5" />

                    <Menu.Item>
                        {({ active }) => (
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                className={cn(
                                    'flex w-full items-center gap-3 px-3 py-2 rounded-xl text-sm font-bold transition-all',
                                    active ? 'bg-error/10 text-error' : 'text-error/70'
                                )}
                            >
                                <span className="material-symbols-outlined text-[20px]">logout</span>
                                Log Out
                            </Link>
                        )}
                    </Menu.Item>
                </Menu.Items>
            </Transition>
        </Menu>
    );
}
