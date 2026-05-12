import SettingsLayout from '@/layouts/SettingsLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { useToastStore } from '@/stores/useToastStore';
import { useState } from 'react';
import { cn } from '@/lib/utils';

interface SecurityProps {
    confirmsPassword?: boolean;
    enabledTwoFactor: boolean;
}

const inputClass = "w-full px-4 py-3 bg-surface-container-highest rounded-xl text-sm text-on-surface placeholder:text-on-surface-variant/40 outline-none focus:ring-2 focus:ring-primary/40 transition-all";

export default function SecuritySettings({ enabledTwoFactor }: SecurityProps) {
    const { addToast } = useToastStore();
    const [enabling, setEnabling] = useState(false);
    const [qrCode, setQrCode] = useState<string | null>(null);
    const [recoveryCodes, setRecoveryCodes] = useState<string[]>([]);
    
    // Forms for Fortify actions
    const enable2faForm = useForm({});
    const disable2faForm = useForm({});
    const confirm2faForm = useForm({ code: '' });

    const enableTwoFactorAuthentication = () => {
        setEnabling(true);
        enable2faForm.post(route('two-factor.enable'), {
            preserveScroll: true,
            onSuccess: () => {
                showQrCode();
                showRecoveryCodes();
                addToast('info', 'Please scan the QR code to complete setup.');
            },
            onFinish: () => setEnabling(false),
        });
    };

    const disableTwoFactorAuthentication = () => {
        disable2faForm.delete(route('two-factor.disable'), {
            preserveScroll: true,
            onSuccess: () => addToast('success', 'Two-factor authentication disabled.'),
        });
    };

    const showQrCode = () => {
        fetch(route('two-factor.qr-code'))
            .then(res => res.json())
            .then(data => setQrCode(data.svg));
    };

    const showRecoveryCodes = () => {
        fetch(route('two-factor.recovery-codes'))
            .then(res => res.json())
            .then(data => setRecoveryCodes(data));
    };

    return (
        <SettingsLayout 
            title="Security" 
            description="Manage your account's security settings and authentication methods."
        >
            <Head title="Security Settings" />

            <div className="space-y-12">
                <section className="space-y-6">
                    <div className="flex items-center justify-between">
                        <h3 className="text-sm font-black text-on-surface uppercase tracking-[0.1em] border-l-2 border-primary pl-3">Two-Factor Authentication</h3>
                        <span className={cn(
                            "text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md",
                            enabledTwoFactor ? "bg-emerald-500/20 text-emerald-400" : "bg-zinc-500/20 text-zinc-400"
                        )}>
                            {enabledTwoFactor ? 'Enabled' : 'Disabled'}
                        </span>
                    </div>

                    <div className="bg-surface-container-highest/30 rounded-3xl p-8 border border-outline-variant/10">
                        {!enabledTwoFactor ? (
                            <div className="space-y-6">
                                <p className="text-sm text-on-surface-variant leading-relaxed max-w-2xl">
                                    Add an extra layer of security to your account by using two-factor authentication. 
                                    When enabled, you'll be prompted for a secure, random token during authentication.
                                </p>
                                <button
                                    onClick={enableTwoFactorAuthentication}
                                    disabled={enabling || enable2faForm.processing}
                                    className="px-8 py-3.5 bg-primary text-on-primary rounded-2xl font-bold text-sm shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50"
                                >
                                    Enable 2FA
                                </button>
                            </div>
                        ) : (
                            <div className="space-y-8">
                                <div className="flex items-center gap-4 text-emerald-400">
                                    <span className="material-symbols-outlined text-3xl">verified_user</span>
                                    <div>
                                        <h4 className="font-bold text-on-surface">You have enabled two-factor authentication.</h4>
                                        <p className="text-xs text-on-surface-variant mt-1">Your account is now more secure.</p>
                                    </div>
                                </div>

                                <div className="flex flex-wrap gap-4">
                                    <button
                                        onClick={disableTwoFactorAuthentication}
                                        disabled={disable2faForm.processing}
                                        className="px-6 py-3 bg-red-500/10 text-red-400 rounded-xl font-bold text-sm hover:bg-red-500/20 transition-all disabled:opacity-50"
                                    >
                                        Disable 2FA
                                    </button>
                                </div>
                            </div>
                        )}

                        {/* QR Code Section (Only if just enabled/confirming) */}
                        {qrCode && (
                            <div className="mt-10 p-8 bg-white rounded-[2rem] flex flex-col items-center gap-6 animate-in fade-in zoom-in duration-500">
                                <div dangerouslySetInnerHTML={{ __html: qrCode }} className="w-48 h-48" />
                                <p className="text-zinc-900 text-xs font-bold text-center max-w-xs">
                                    Scan this QR code using your preferred authenticator app (like Google Authenticator or Authy).
                                </p>
                            </div>
                        )}

                        {/* Recovery Codes */}
                        {recoveryCodes.length > 0 && (
                            <div className="mt-10 space-y-4">
                                <h4 className="text-xs font-black text-on-surface-variant uppercase tracking-widest px-1">Recovery Codes</h4>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 p-6 bg-black/40 rounded-2xl font-mono text-xs text-primary border border-primary/20">
                                    {recoveryCodes.map(code => <div key={code}>{code}</div>)}
                                </div>
                                <p className="text-[10px] text-on-surface-variant italic">Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two-factor authentication device is lost.</p>
                            </div>
                        )}
                    </div>
                </section>
            </div>
        </SettingsLayout>
    );
}
