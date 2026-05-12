import { useForm, Head, Link } from '@inertiajs/react';
import AuthLayout from '@/layouts/AuthLayout';
import { cn } from '@/lib/utils';

interface Props {
    token: string;
    email: string;
}

export default function ResetPassword({ token, email }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/reset-password');
    };

    return (
        <AuthLayout title="Reset Password">
            <Head title="Reset Password" />

            <form onSubmit={submit} className="flex flex-col gap-5">
                <div className="flex flex-col gap-2">
                    <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="email">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        required
                        autoComplete="email"
                        className={cn(
                            'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface outline-none transition-all',
                            'focus:ring-2 focus:ring-primary/40',
                            errors.email && 'ring-2 ring-error',
                        )}
                    />
                    {errors.email && <p className="text-error text-xs px-2">{errors.email}</p>}
                </div>

                <div className="flex flex-col gap-2">
                    <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="password">
                        New Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        placeholder="••••••••"
                        required
                        autoFocus
                        autoComplete="new-password"
                        className={cn(
                            'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all',
                            'focus:ring-2 focus:ring-primary/40',
                            errors.password && 'ring-2 ring-error',
                        )}
                    />
                    {errors.password && <p className="text-error text-xs px-2">{errors.password}</p>}
                </div>

                <div className="flex flex-col gap-2">
                    <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="password_confirmation">
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        placeholder="••••••••"
                        required
                        autoComplete="new-password"
                        className={cn(
                            'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all',
                            'focus:ring-2 focus:ring-primary/40',
                        )}
                    />
                </div>

                <button
                    type="submit"
                    disabled={processing}
                    className="mt-2 w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-headline font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-60"
                >
                    {processing ? (
                        <span className="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin" />
                    ) : 'Reset Password'}
                </button>
            </form>
        </AuthLayout>
    );
}
