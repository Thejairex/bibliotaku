import { useUIStore } from '@/stores/useUIStore';
import { AppLogo } from './AppLogo';

export function Topbar() {
    const toggleMobileDrawer = useUIStore((state) => state.toggleMobileDrawer);

    return (
        <header className="lg:hidden fixed top-0 inset-x-0 h-20 bg-surface-container/80 backdrop-blur-xl border-b border-outline-variant/5 px-6 flex items-center justify-between z-40">
            <AppLogo className="scale-90 origin-left" />
            
            <button
                onClick={toggleMobileDrawer}
                className="p-3 rounded-2xl bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-all active:scale-95"
            >
                <span className="material-symbols-outlined">menu</span>
            </button>
        </header>
    );
}
