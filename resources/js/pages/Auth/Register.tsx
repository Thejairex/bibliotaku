import { useForm, Head, Link } from '@inertiajs/react';
import AuthLayout from '@/layouts/AuthLayout';
import { cn } from '@/lib/utils';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/register');
    };

    const fields = [
        { id: 'name', label: 'Full Name', type: 'text', placeholder: 'Alexander Curator', icon: 'person', autoComplete: 'name' },
        { id: 'email', label: 'Email Address', type: 'email', placeholder: 'curator@archive.com', icon: 'mail', autoComplete: 'email' },
        { id: 'password', label: 'Password', type: 'password', placeholder: '••••••••', icon: 'lock', autoComplete: 'new-password' },
        { id: 'password_confirmation', label: 'Confirm Password', type: 'password', placeholder: '••••••••', icon: 'enhanced_encryption', autoComplete: 'new-password' },
    ] as const;

    return (
        <AuthLayout title="Create your account">
            <Head title="Register" />

            <p className="text-on-surface-variant text-sm mb-8 -mt-4">Join the elite circle of curators today.</p>

            <form onSubmit={submit} className="flex flex-col gap-5">
                {fields.map(({ id, label, type, placeholder, icon, autoComplete }) => (
                    <div key={id} className="flex flex-col gap-2">
                        <label className="font-label text-xs font-bold text-on-surface-variant uppercase tracking-wider ml-1" htmlFor={id}>
                            {label}
                        </label>
                        <div className="relative group">
                            <span className="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg transition-colors group-focus-within:text-primary pointer-events-none">
                                {icon}
                            </span>
                            <input
                                id={id}
                                type={type}
                                value={data[id]}
                                onChange={(e) => setData(id, e.target.value)}
                                placeholder={placeholder}
                                autoComplete={autoComplete}
                                required
                                autoFocus={id === 'name'}
                                className={cn(
                                    'w-full bg-surface-container-low border-none rounded-2xl py-4 pl-12 pr-4 text-on-surface placeholder:text-on-surface-variant/40 outline-none transition-all',
                                    'focus:ring-2 focus:ring-primary/40',
                                    errors[id] && 'ring-2 ring-error',
                                )}
                            />
                        </div>
                        {errors[id] && <p className="text-error text-xs px-2">{errors[id]}</p>}
                    </div>
                ))}

                <button
                    type="submit"
                    disabled={processing}
                    className="mt-2 w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-headline font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-60"
                >
                    {processing ? (
                        <span className="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin" />
                    ) : 'Create Account'}
                </button>
            </form>

            <div className="text-center mt-6">
                <p className="text-on-surface-variant text-sm">
                    Already have an account?{' '}
                    <Link href="/login" className="text-primary font-bold hover:text-primary-dim transition-colors ml-1">
                        Sign in
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
