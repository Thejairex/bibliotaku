import { useForm, Head, Link } from '@inertiajs/react';
import AuthLayout from '@/layouts/AuthLayout';

interface Props { status?: string }

export default function VerifyEmail({ status }: Props) {
    const { post, processing } = useForm({});

    return (
        <AuthLayout title="Verify your email">
            <Head title="Email Verification" />

            <p className="text-on-surface-variant text-sm mb-6 -mt-4">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>

            {status === 'verification-link-sent' && (
                <div className="mb-6 text-sm text-primary bg-primary/10 border border-primary/20 rounded-2xl px-4 py-3">
                    A new verification link has been sent to your email address.
                </div>
            )}

            <div className="flex flex-col gap-4">
                <button
                    onClick={() => post('/email/verification-notification')}
                    disabled={processing}
                    className="w-full bg-gradient-to-br from-primary to-primary-dim text-on-primary font-bold py-4 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-primary/40 active:scale-[0.98] transition-all disabled:opacity-60"
                >
                    {processing ? (
                        <span className="w-5 h-5 border-2 border-on-primary/30 border-t-on-primary rounded-full animate-spin inline-block" />
                    ) : 'Resend Verification Email'}
                </button>

                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    className="text-sm text-on-surface-variant hover:text-error transition-colors text-center"
                >
                    Log Out
                </Link>
            </div>
        </AuthLayout>
    );
}
