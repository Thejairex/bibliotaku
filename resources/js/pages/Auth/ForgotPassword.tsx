import { useForm, Head, Link } from '@inertiajs/react';
import AuthLayout from '@/layouts/AuthLayout';
import { cn } from '@/lib/utils';

interface Props {
    status?: string;
}

export default function ForgotPassword({ status }: Props) {
    const { data, setData, post, processing, errors } = useForm({ email: '' });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/forgot-password');
    };

    return (
        <AuthLayout title="Forgot Password">
            <Head title="Forgot Password" />

            <p className="text-on-surface-variant text-sm mb-6 -mt-4">
                Enter your email and we'll send you a link to reset your password.
            </p>

            {status && (
                <div className="mb-6 text-sm text-primary bg-primary/10 border border-primary/20 rounded-2xl px-4 py-3">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="flex flex-col gap-5">
                <div className="flex flex-col gap-2">
                    <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="email">
                        Email Address
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

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-headline font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-60"
                >
                    {processing ? (
                        <span className="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin" />
                    ) : 'Email Password Reset Link'}
                </button>
            </form>

            <div className="text-center mt-6">
                <p className="text-on-surface-variant text-sm">
                    Or, return to{' '}
                    <Link href="/login" className="text-primary font-bold hover:text-primary-dim transition-colors">
                        log in
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
