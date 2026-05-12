import SettingsLayout from '@/layouts/SettingsLayout';
import { Head } from '@inertiajs/react';

export default function AppearanceSettings() {
    return (
        <SettingsLayout 
            title="Appearance" 
            description="Customize the look and feel of Bibliotaku."
        >
            <Head title="Appearance Settings" />

            <div className="space-y-12">
                <section className="space-y-6">
                    <h3 className="text-sm font-black text-on-surface uppercase tracking-[0.1em] border-l-2 border-primary pl-3">Theme Selection</h3>
                    
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {/* Dark Theme (Current) */}
                        <div className="relative p-6 rounded-3xl bg-[#0e0e0e] border-2 border-primary shadow-xl shadow-primary/10 overflow-hidden group">
                            <div className="flex items-center justify-between mb-8">
                                <div className="size-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                                    <span className="material-symbols-outlined">dark_mode</span>
                                </div>
                                <span className="bg-primary text-on-primary text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md">Active</span>
                            </div>
                            <h4 className="font-bold text-white">Obsidian Dark</h4>
                            <p className="text-xs text-zinc-500 mt-1">Our signature deep black theme designed for late-night viewing.</p>
                            
                            {/* Visual Preview */}
                            <div className="mt-6 flex gap-2">
                                <div className="h-2 w-12 rounded bg-primary/40" />
                                <div className="h-2 w-8 rounded bg-secondary/40" />
                            </div>
                        </div>

                        {/* Light Theme (Coming Soon) */}
                        <div className="relative p-6 rounded-3xl bg-zinc-800/50 border-2 border-transparent border-dashed opacity-50 grayscale cursor-not-allowed overflow-hidden">
                            <div className="flex items-center justify-between mb-8">
                                <div className="size-10 rounded-xl bg-zinc-700 flex items-center justify-center text-zinc-500">
                                    <span className="material-symbols-outlined">light_mode</span>
                                </div>
                            </div>
                            <h4 className="font-bold text-zinc-400">Pure Light</h4>
                            <p className="text-xs text-zinc-500 mt-1">A clean, high-contrast light theme. Coming in a future update.</p>
                        </div>
                    </div>
                </section>

                <hr className="border-outline-variant/5" />

                <section className="space-y-6">
                    <h3 className="text-sm font-black text-on-surface uppercase tracking-[0.1em] border-l-2 border-primary pl-3">Font Settings</h3>
                    <div className="p-6 bg-surface-container-highest/50 rounded-2xl border border-outline-variant/10">
                        <div className="flex items-center justify-between">
                            <div>
                                <h4 className="font-bold text-on-surface">System Fonts</h4>
                                <p className="text-xs text-on-surface-variant mt-1">Using Manrope for headlines and Inter for body text.</p>
                            </div>
                            <span className="material-symbols-outlined text-primary">font_download</span>
                        </div>
                    </div>
                </section>
            </div>
        </SettingsLayout>
    );
}
