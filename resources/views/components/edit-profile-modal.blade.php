{{-- EDIT PROFILE MODAL --}}
<div id="editProfileModal"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">

    {{-- Backdrop --}}
    <div id="editProfileBackdrop" class="absolute inset-0 bg-black/75 backdrop-blur-sm"></div>

    {{-- Modal Panel --}}
    <div id="editProfilePanel"
        class="relative z-10 w-full max-w-lg bg-surface-container rounded-2xl shadow-2xl shadow-black/70 flex flex-col
               transform scale-95 transition-transform duration-300 overflow-hidden">

        {{-- Decorative header glow --}}
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary/50 to-transparent"></div>

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-8 py-6 border-b border-outline-variant/10">
            <div class="flex items-center gap-4">
                {{-- Current avatar preview --}}
                <div id="epModalAvatarPreview"
                    class="w-12 h-12 rounded-xl overflow-hidden bg-primary/20 flex items-center justify-center border-2 border-primary/20 shrink-0">
                    @if(auth()->user()->avatar)
                        <img id="epModalAvatarImg" src="{{ auth()->user()->avatar }}" alt="" class="w-full h-full object-cover" />
                    @else
                        <span class="font-headline font-black text-primary text-lg" id="epModalInitials">
                            {{ auth()->user()->initials() }}
                        </span>
                    @endif
                </div>
                <div>
                    <h3 class="font-headline font-black text-xl tracking-tight">{{ __('Edit Profile') }}</h3>
                    <p class="text-on-surface-variant text-xs mt-0.5">{{ __('Update your personal information') }}</p>
                </div>
            </div>
            <button id="closeEditProfileModal"
                class="w-9 h-9 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition-all">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        {{-- Flash messages inside modal (shown after redirect) --}}

        {{-- Form --}}
        <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}" class="px-8 py-6 space-y-5 overflow-y-auto max-h-[70vh]">
            @csrf
            @method('PATCH')

            {{-- Name --}}
            <div class="space-y-2">
                <label class="block text-[10px] font-label font-black text-on-surface-variant uppercase tracking-[0.15em] px-1"
                       for="ep_name">
                    {{ __('Display Name') }} <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">badge</span>
                    <input id="ep_name" name="name" type="text" required
                        value="{{ old('name', auth()->user()->name) }}"
                        placeholder="{{ __('Your name') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all font-body text-sm" />
                </div>
                @error('name')
                    <p class="text-error text-xs px-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <label class="block text-[10px] font-label font-black text-on-surface-variant uppercase tracking-[0.15em] px-1"
                       for="ep_email">
                    {{ __('Email Address') }} <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">alternate_email</span>
                    <input id="ep_email" name="email" type="email" required
                        value="{{ old('email', auth()->user()->email) }}"
                        placeholder="you@example.com"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all font-body text-sm" />
                </div>
                @error('email')
                    <p class="text-error text-xs px-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Avatar URL --}}
            <div class="space-y-2">
                <label class="block text-[10px] font-label font-black text-on-surface-variant uppercase tracking-[0.15em] px-1"
                       for="ep_avatar">
                    {{ __('Avatar URL') }}
                    <span class="normal-case font-normal text-outline ml-1">{{ __('(direct image link)') }}</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">account_circle</span>
                    <input id="ep_avatar" name="avatar" type="url"
                        value="{{ old('avatar', auth()->user()->avatar) }}"
                        placeholder="https://..."
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all font-body text-sm" />
                </div>
                @error('avatar')
                    <p class="text-error text-xs px-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Divider: Password section --}}
            <div class="pt-2">
                <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-outline-variant/30"></div>
                    <span class="text-[10px] font-black font-label uppercase tracking-[0.2em] text-on-surface-variant">{{ __('Change Password') }}</span>
                    <div class="h-px flex-1 bg-outline-variant/30"></div>
                </div>
                <p class="text-center text-[10px] text-outline mt-2">{{ __('Leave blank to keep current password') }}</p>
            </div>

            {{-- Current password --}}
            <div class="space-y-2">
                <label class="block text-[10px] font-label font-black text-on-surface-variant uppercase tracking-[0.15em] px-1"
                       for="ep_current_password">
                    {{ __('Current Password') }}
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">lock</span>
                    <input id="ep_current_password" name="current_password" type="password"
                        placeholder="••••••••"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all font-body text-sm" />
                </div>
                @error('current_password')
                    <p class="text-error text-xs px-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- New password --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-[10px] font-label font-black text-on-surface-variant uppercase tracking-[0.15em] px-1"
                           for="ep_password">
                        {{ __('New Password') }}
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">lock_reset</span>
                        <input id="ep_password" name="password" type="password"
                            placeholder="••••••••"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all font-body text-sm" />
                    </div>
                    @error('password')
                        <p class="text-error text-xs px-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label class="block text-[10px] font-label font-black text-on-surface-variant uppercase tracking-[0.15em] px-1"
                           for="ep_password_confirmation">
                        {{ __('Confirm') }}
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">lock_open</span>
                        <input id="ep_password_confirmation" name="password_confirmation" type="password"
                            placeholder="••••••••"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all font-body text-sm" />
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-outline-variant/10">
                <button type="button" id="cancelEditProfile"
                    class="px-6 py-3 rounded-full font-label font-bold text-sm text-on-surface-variant hover:text-on-surface bg-surface-container-highest hover:bg-surface-variant transition-all">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    class="gradient-cta px-8 py-3 rounded-full font-label font-bold text-on-primary text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const modal    = document.getElementById('editProfileModal');
    const panel    = document.getElementById('editProfilePanel');
    const backdrop = document.getElementById('editProfileBackdrop');
    const avatarInput = document.getElementById('ep_avatar');
    const avatarPreview = document.getElementById('epModalAvatarPreview');

    function openModal() {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        panel.classList.remove('scale-95');
        panel.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('opacity-0', 'pointer-events-none');
        panel.classList.add('scale-95');
        panel.classList.remove('scale-100');
        document.body.style.overflow = '';
    }

    // Live avatar preview on URL input
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('input', () => {
            const url = avatarInput.value.trim();
            if (url) {
                avatarPreview.innerHTML = `<img src="${url}" class="w-full h-full object-cover" onerror="this.style.display='none'" />`;
            } else {
                avatarPreview.innerHTML = `<span class="font-headline font-black text-primary text-lg">{{ auth()->user()->initials() }}</span>`;
            }
        });
    }

    // Trigger button on profile page
    document.querySelectorAll('[data-open-profile-modal]').forEach(btn => {
        btn.addEventListener('click', openModal);
    });

    document.getElementById('closeEditProfileModal').addEventListener('click', closeModal);
    document.getElementById('cancelEditProfile').addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

    // Auto-open if there are validation errors
    @if($errors->isNotEmpty())
        openModal();
    @endif
})();
</script>
