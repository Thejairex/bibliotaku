import { Fragment } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { useUIStore } from '@/stores/useUIStore';
import { Sidebar } from './Sidebar';

export function MobileDrawer() {
    const { mobileDrawerOpen, setMobileDrawerOpen } = useUIStore();

    return (
        <Transition.Root show={mobileDrawerOpen} as={Fragment}>
            <Dialog as="div" className="relative z-[100] lg:hidden" onClose={setMobileDrawerOpen}>
                <Transition.Child
                    as={Fragment}
                    enter="transition-opacity ease-linear duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black/60 backdrop-blur-sm" />
                </Transition.Child>

                <div className="fixed inset-0 flex">
                    <Transition.Child
                        as={Fragment}
                        enter="transition ease-in-out duration-300 transform"
                        enterFrom="-translate-x-full"
                        enterTo="translate-x-0"
                        leave="transition ease-in-out duration-300 transform"
                        leaveFrom="translate-x-0"
                        leaveTo="-translate-x-full"
                    >
                        <Dialog.Panel className="relative flex w-full max-w-xs flex-1 flex-col">
                            <Sidebar className="w-full border-none" />
                            <div className="absolute right-4 top-4">
                                <button
                                    type="button"
                                    className="p-2 text-on-surface-variant hover:text-on-surface transition-colors"
                                    onClick={() => setMobileDrawerOpen(false)}
                                >
                                    <span className="material-symbols-outlined">close</span>
                                </button>
                            </div>
                        </Dialog.Panel>
                    </Transition.Child>
                </div>
            </Dialog>
        </Transition.Root>
    );
}
