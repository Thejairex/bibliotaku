import { useForm, Head } from '@inertiajs/react';
import AuthLayout from '@/layouts/AuthLayout';
import { cn } from '@/lib/utils';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors } = useForm({ password: '' });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/user/confirm-password');
    };

    return (
        <AuthLayout title="Confirm Password">
            <Head title="Confirm Password" />

            <p className="text-on-surface-variant text-sm mb-6 -mt-4">
                This is a secure area. Please confirm your password before continuing.
            </p>

            <form onSubmit={submit} className="flex flex-col gap-5">
                <div className="flex flex-col gap-2">
                    <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor="password">
                        Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        placeholder="••••••••"
                        required
                        autoFocus
                        autoComplete="current-password"
                        className={cn(
                            'w-full bg-surface-container-low border-none rounded-2xl px-6 py-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all',
                            'focus:ring-2 focus:ring-primary/40',
                            errors.password && 'ring-2 ring-error',
                        )}
                    />
                    {errors.password && <p className="text-error text-xs px-2">{errors.password}</p>}
                </div>

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all disabled:opacity-60"
                >
                    {processing ? (
                        <span className="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin inline-block" />
                    ) : 'Confirm'}
                </button>
            </form>
        </AuthLayout>
    );
}
