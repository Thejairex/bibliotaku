import SettingsLayout from '@/layouts/SettingsLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { useToastStore } from '@/stores/useToastStore';
import { SharedPageProps } from '@/types/SharedProps';
import { useState } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { Fragment } from 'react';

const inputClass = "w-full px-4 py-3 bg-surface-container-highest rounded-xl text-sm text-on-surface placeholder:text-on-surface-variant/40 outline-none focus:ring-2 focus:ring-primary/40 transition-all";
const labelClass = "block text-xs font-black text-on-surface-variant uppercase tracking-[0.12em] mb-2 px-1";

export default function ProfileSettings() {
    const { auth } = usePage<SharedPageProps>().props;
    const { addToast } = useToastStore();
    const [confirmingDeletion, setConfirmingDeletion] = useState(false);

    // Profile Info Form
    const profileForm = useForm({
        name: auth.user.name,
        email: auth.user.email,
        avatar: auth.user.avatar || '',
    });

    // Password Update Form (using Fortify's updatePassword endpoint if possible, or we use a custom one)
    // For simplicity, let's keep it together if the user wants or use a separate form
    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const deleteForm = useForm({
        password: '',
    });

    const updateProfile = (e: React.FormEvent) => {
        e.preventDefault();
        profileForm.patch('/settings/profile', {
            onSuccess: () => addToast('success', 'Profile updated successfully!'),
            preserveScroll: true,
        });
    };

    const updatePassword = (e: React.FormEvent) => {
        e.preventDefault();
        // Fortify's default endpoint for password updates
        passwordForm.put(route('user-password.update'), {
            onSuccess: () => {
                passwordForm.reset();
                addToast('success', 'Password updated successfully!');
            },
            preserveScroll: true,
        });
    };

    const deleteAccount = (e: React.FormEvent) => {
        e.preventDefault();
        deleteForm.delete('/settings/profile', {
            onSuccess: () => setConfirmingDeletion(false),
            onFinish: () => deleteForm.reset(),
            preserveScroll: true,
        });
    };

    return (
        <SettingsLayout 
            title="Public Profile" 
            description="Control how you appear to others and update your credentials."
        >
            <Head title="Profile Settings" />

            <div className="space-y-12">
                {/* Profile Information */}
                <section className="space-y-6">
                    <h3 className="text-sm font-black text-on-surface uppercase tracking-[0.1em] border-l-2 border-primary pl-3">General Information</h3>
                    <form onSubmit={updateProfile} className="space-y-5 max-w-xl">
                        <div>
                            <label className={labelClass}>Display Name</label>
                            <input 
                                type="text" 
                                value={profileForm.data.name} 
                                onChange={e => profileForm.setData('name', e.target.value)} 
                                className={inputClass}
                            />
                            {profileForm.errors.name && <p className="text-red-400 text-xs mt-2">{profileForm.errors.name}</p>}
                        </div>

                        <div>
                            <label className={labelClass}>Email Address</label>
                            <input 
                                type="email" 
                                value={profileForm.data.email} 
                                onChange={e => profileForm.setData('email', e.target.value)} 
                                className={inputClass}
                            />
                            {profileForm.errors.email && <p className="text-red-400 text-xs mt-2">{profileForm.errors.email}</p>}
                        </div>

                        <div>
                            <label className={labelClass}>Avatar URL</label>
                            <input 
                                type="url" 
                                value={profileForm.data.avatar} 
                                onChange={e => profileForm.setData('avatar', e.target.value)} 
                                className={inputClass}
                                placeholder="https://..."
                            />
                            {profileForm.errors.avatar && <p className="text-red-400 text-xs mt-2">{profileForm.errors.avatar}</p>}
                        </div>

                        <button 
                            type="submit" 
                            disabled={profileForm.processing}
                            className="px-6 py-3 bg-primary text-on-primary rounded-xl font-bold text-sm hover:bg-primary/90 transition-all disabled:opacity-50"
                        >
                            Save Profile
                        </button>
                    </form>
                </section>

                <hr className="border-outline-variant/5" />

                {/* Change Password */}
                <section className="space-y-6">
                    <h3 className="text-sm font-black text-on-surface uppercase tracking-[0.1em] border-l-2 border-primary pl-3">Update Password</h3>
                    <form onSubmit={updatePassword} className="space-y-5 max-w-xl">
                        <div>
                            <label className={labelClass}>Current Password</label>
                            <input 
                                type="password" 
                                value={passwordForm.data.current_password} 
                                onChange={e => passwordForm.setData('current_password', e.target.value)} 
                                className={inputClass}
                            />
                            {passwordForm.errors.current_password && <p className="text-red-400 text-xs mt-2">{passwordForm.errors.current_password}</p>}
                        </div>

                        <div>
                            <label className={labelClass}>New Password</label>
                            <input 
                                type="password" 
                                value={passwordForm.data.password} 
                                onChange={e => passwordForm.setData('password', e.target.value)} 
                                className={inputClass}
                            />
                            {passwordForm.errors.password && <p className="text-red-400 text-xs mt-2">{passwordForm.errors.password}</p>}
                        </div>

                        <div>
                            <label className={labelClass}>Confirm Password</label>
                            <input 
                                type="password" 
                                value={passwordForm.data.password_confirmation} 
                                onChange={e => passwordForm.setData('password_confirmation', e.target.value)} 
                                className={inputClass}
                            />
                            {passwordForm.errors.password_confirmation && <p className="text-red-400 text-xs mt-2">{passwordForm.errors.password_confirmation}</p>}
                        </div>

                        <button 
                            type="submit" 
                            disabled={passwordForm.processing}
                            className="px-6 py-3 bg-surface-container-highest text-on-surface rounded-xl font-bold text-sm hover:bg-outline-variant/20 transition-all disabled:opacity-50"
                        >
                            Update Password
                        </button>
                    </form>
                </section>

                <hr className="border-outline-variant/5" />

                {/* Danger Zone */}
                <section className="space-y-6">
                    <h3 className="text-sm font-black text-red-400 uppercase tracking-[0.1em] border-l-2 border-red-500 pl-3">Danger Zone</h3>
                    <div className="bg-red-500/5 border border-red-500/10 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div>
                            <h4 className="font-bold text-on-surface">Delete Account</h4>
                            <p className="text-xs text-on-surface-variant mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                        </div>
                        <button 
                            onClick={() => setConfirmingDeletion(true)}
                            className="px-6 py-3 bg-red-500 text-white rounded-xl font-bold text-sm hover:bg-red-600 transition-all"
                        >
                            Delete Account
                        </button>
                    </div>
                </section>
            </div>

            {/* Delete Confirmation Modal */}
            <Transition show={confirmingDeletion} as={Fragment}>
                <Dialog onClose={() => setConfirmingDeletion(false)} className="relative z-50">
                    <Transition.Child as={Fragment} enter="ease-out duration-200" enterFrom="opacity-0" enterTo="opacity-100" leave="ease-in duration-150" leaveFrom="opacity-100" leaveTo="opacity-0">
                        <div className="fixed inset-0 bg-black/80 backdrop-blur-md" />
                    </Transition.Child>

                    <div className="fixed inset-0 flex items-center justify-center p-4">
                        <Transition.Child as={Fragment} enter="ease-out duration-200" enterFrom="opacity-0 scale-95" enterTo="opacity-100 scale-100" leave="ease-in duration-150" leaveFrom="opacity-100 scale-100" leaveTo="opacity-0 scale-95">
                            <Dialog.Panel className="w-full max-w-md bg-[#1a1a1a] rounded-[2.5rem] border border-red-500/20 p-8 shadow-2xl">
                                <Dialog.Title className="text-2xl font-headline font-black text-on-surface">Are you absolutely sure?</Dialog.Title>
                                <p className="text-on-surface-variant text-sm mt-3 leading-relaxed">
                                    Please enter your password to confirm you would like to permanently delete your account. This action cannot be undone.
                                </p>

                                <form onSubmit={deleteAccount} className="mt-6 space-y-4">
                                    <input 
                                        type="password" 
                                        placeholder="Password"
                                        className={inputClass}
                                        value={deleteForm.data.password}
                                        onChange={e => deleteForm.setData('password', e.target.value)}
                                        autoFocus
                                    />
                                    {deleteForm.errors.password && <p className="text-red-400 text-xs">{deleteForm.errors.password}</p>}

                                    <div className="flex gap-3 pt-2">
                                        <button 
                                            type="button"
                                            onClick={() => setConfirmingDeletion(false)}
                                            className="flex-1 py-3.5 rounded-2xl text-sm font-bold text-on-surface-variant hover:bg-surface-container transition-all"
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            type="submit"
                                            disabled={deleteForm.processing}
                                            className="flex-1 py-3.5 bg-red-500 text-white rounded-2xl text-sm font-bold hover:bg-red-600 transition-all disabled:opacity-50"
                                        >
                                            Delete My Account
                                        </button>
                                    </div>
                                </form>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </Dialog>
            </Transition>
        </SettingsLayout>
    );
}
