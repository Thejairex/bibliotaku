import React from 'react';
import { Sidebar } from '@/components/layout/Sidebar';
import { Topbar } from '@/components/layout/Topbar';
import { MobileDrawer } from '@/components/layout/MobileDrawer';
import { useToastStore } from '@/stores/useToastStore';
import { cn } from '@/lib/utils';

export default function AppLayout({ children }: { children: React.ReactNode }) {
    const toasts = useToastStore((state) => state.toasts);
    const removeToast = useToastStore((state) => state.removeToast);

    return (
        <div className="min-h-screen bg-background flex text-on-surface font-body selection:bg-primary/30 antialiased">
            {/* Desktop Sidebar */}
            <Sidebar className="hidden lg:flex fixed inset-y-0 left-0 w-62" />

            {/* Mobile Drawer Overlay */}
            <MobileDrawer />

            {/* Mobile Topbar */}
            <Topbar />

            {/* Main Content Area */}
            <main className="flex-1 lg:ml-72 min-h-screen p-6 pt-24 lg:pt-12 md:p-12 transition-all duration-300">
                <div className="max-w-7xl mx-auto animate-fade-in">
                    {children}
                </div>
            </main>

            {/* Global Toasts */}
            <div className="fixed bottom-6 right-6 z-[110] flex flex-col gap-3">
                {toasts.map((toast) => (
                    <div
                        key={toast.id}
                        className={cn(
                            'px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 animate-fade-in min-w-[300px]',
                            toast.type === 'success' && 'bg-primary text-on-primary',
                            toast.type === 'error' && 'bg-error text-on-surface',
                            toast.type === 'info' && 'bg-surface-container-highest text-on-surface'
                        )}
                        onClick={() => removeToast(toast.id)}
                    >
                        <span className="material-symbols-outlined">
                            {toast.type === 'success' ? 'check_circle' : toast.type === 'error' ? 'error' : 'info'}
                        </span>
                        <span className="text-sm font-bold">{toast.message}</span>
                    </div>
                ))}
            </div>
        </div>
    );
}
