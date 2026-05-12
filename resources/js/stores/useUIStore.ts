import { create } from 'zustand';

interface UIState {
    mobileDrawerOpen: boolean;
    setMobileDrawerOpen: (open: boolean) => void;
    toggleMobileDrawer: () => void;
}

export const useUIStore = create<UIState>((set) => ({
    mobileDrawerOpen: false,
    setMobileDrawerOpen: (open) => set({ mobileDrawerOpen: open }),
    toggleMobileDrawer: () => set((state) => ({ mobileDrawerOpen: !state.mobileDrawerOpen })),
}));
