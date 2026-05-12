import { useForm, Head, Link } from '@inertiajs/react';
import AuthLayout from '@/layouts/AuthLayout';
import { cn } from '@/lib/utils';

interface Props {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false as boolean,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <AuthLayout>
            <Head title="Log In" />

            {status && (
                <div className="mb-6 text-sm text-primary bg-primary/10 border border-primary/20 rounded-2xl px-4 py-3">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="flex flex-col gap-5">
                {/* Email */}
                <div className="flex flex-col gap-2">
                    <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="email">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        placeholder="curator@archive.com"
                        required
                        autoFocus
                        autoComplete="email"
                        className={cn(
                            'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all',
                            'focus:ring-2 focus:ring-primary/40',
                            errors.email && 'ring-2 ring-error',
                        )}
                    />
                    {errors.email && <p className="text-error text-xs px-2">{errors.email}</p>}
                </div>

                {/* Password */}
                <div className="flex flex-col gap-2">
                    <div className="flex justify-between items-center px-1">
                        <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider" htmlFor="password">
                            Password
                        </label>
                        {canResetPassword && (
                            <Link
                                href="/forgot-password"
                                className="font-label text-[10px] uppercase font-bold text-primary hover:text-primary-dim transition-colors tracking-widest"
                            >
                                Forgot Password?
                            </Link>
                        )}
                    </div>
                    <input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        placeholder="••••••••"
                        required
                        autoComplete="current-password"
                        className={cn(
                            'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all',
                            'focus:ring-2 focus:ring-primary/40',
                            errors.password && 'ring-2 ring-error',
                        )}
                    />
                    {errors.password && <p className="text-error text-xs px-2">{errors.password}</p>}
                </div>

                {/* Remember Me */}
                <label className="flex items-center gap-3 px-1 cursor-pointer group">
                    <input
                        type="checkbox"
                        checked={data.remember}
                        onChange={(e) => setData('remember', e.target.checked)}
                        className="w-4 h-4 rounded accent-primary cursor-pointer"
                    />
                    <span className="font-label text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">
                        Remember me
                    </span>
                </label>

                {/* Submit */}
                <button
                    type="submit"
                    disabled={processing}
                    className="mt-2 w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-headline font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all flex items-center justify-center gap-2 group disabled:opacity-60"
                >
                    {processing ? (
                        <span className="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin" />
                    ) : (
                        <>
                            <span>Sign In</span>
                            <span className="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </>
                    )}
                </button>
            </form>

            {/* Divider */}
            <div className="relative flex items-center py-6">
                <div className="flex-grow border-t border-outline-variant/20" />
                <span className="flex-shrink mx-4 text-xs font-bold text-outline uppercase tracking-widest">or</span>
                <div className="flex-grow border-t border-outline-variant/20" />
            </div>

            <div className="text-center">
                <p className="text-on-surface-variant font-label text-sm">
                    Don't have an account?{' '}
                    <Link href="/register" className="text-secondary font-bold hover:text-primary transition-colors ml-1">
                        Sign up instead
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
