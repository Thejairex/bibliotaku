import { useState } from 'react';
import { useForm, Head, Link } from '@inertiajs/react';
import AuthLayout from '@/layouts/AuthLayout';
import { cn } from '@/lib/utils';

export default function TwoFactorChallenge() {
    const [useRecovery, setUseRecovery] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        code: '',
        recovery_code: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/two-factor-challenge');
    };

    return (
        <AuthLayout title="Two-Factor Authentication">
            <Head title="Two-Factor Challenge" />

            <p className="text-on-surface-variant text-sm mb-6 -mt-4">
                {useRecovery
                    ? 'Please confirm access to your account by entering one of your emergency recovery codes.'
                    : 'Please confirm access to your account by entering the authentication code provided by your authenticator application.'}
            </p>

            <form onSubmit={submit} className="flex flex-col gap-5">
                {useRecovery ? (
                    <div className="flex flex-col gap-2">
                        <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="recovery_code">
                            Recovery Code
                        </label>
                        <input
                            id="recovery_code"
                            type="text"
                            value={data.recovery_code}
                            onChange={(e) => setData('recovery_code', e.target.value)}
                            placeholder="xxxxxx-xxxxxx"
                            autoComplete="one-time-code"
                            className={cn(
                                'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all font-mono tracking-widest',
                                'focus:ring-2 focus:ring-primary/40',
                                errors.recovery_code && 'ring-2 ring-error',
                            )}
                        />
                        {errors.recovery_code && <p className="text-error text-xs px-2">{errors.recovery_code}</p>}
                    </div>
                ) : (
                    <div className="flex flex-col gap-2">
                        <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="code">
                            Authentication Code
                        </label>
                        <input
                            id="code"
                            type="text"
                            inputMode="numeric"
                            value={data.code}
                            onChange={(e) => setData('code', e.target.value)}
                            placeholder="000 000"
                            autoComplete="one-time-code"
                            autoFocus
                            className={cn(
                                'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all font-mono text-center text-2xl tracking-[0.5em]',
                                'focus:ring-2 focus:ring-primary/40',
                                errors.code && 'ring-2 ring-error',
                            )}
                        />
                        {errors.code && <p className="text-error text-xs px-2">{errors.code}</p>}
                    </div>
                )}

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all disabled:opacity-60"
                >
                    {processing ? (
                        <span className="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin inline-block" />
                    ) : 'Verify'}
                </button>
            </form>

            <button
                onClick={() => setUseRecovery(!useRecovery)}
                className="mt-4 w-full text-sm text-on-surface-variant hover:text-primary transition-colors text-center"
            >
                {useRecovery ? 'Use an authentication code instead' : 'Use a recovery code instead'}
            </button>
        </AuthLayout>
    );
}
