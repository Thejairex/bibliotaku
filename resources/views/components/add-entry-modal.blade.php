{{-- ADD ENTRY MODAL --}}
<div id="addEntryModal"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">

    {{-- Backdrop --}}
    <div id="modalBackdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

    {{-- Modal Panel --}}
    <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-surface-container rounded-xl shadow-2xl shadow-black/60 flex flex-col
                transform scale-95 transition-transform duration-300" id="modalPanel">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-8 py-6 border-b border-outline-variant/10">
            <div>
                <h3 class="font-headline font-black text-2xl tracking-tight">{{ __('Add to Archive') }}</h3>
                <p class="text-on-surface-variant text-sm mt-0.5">{{ __('Log a new anime, manga, or any other medium.') }}</p>
            </div>
            <button id="closeAddModal" class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Modal Form --}}
        <form method="POST" action="{{ route('my-list.store') }}" class="px-8 py-6 space-y-6">
            @csrf

            {{-- Title Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_title">
                        {{ __('Title') }} <span class="text-error">*</span>
                    </label>
                    <input id="modal_title" name="title" type="text" required
                        value="{{ old('title') }}"
                        placeholder="{{ __('e.g. Attack on Titan') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    @error('title') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_original_title">
                        {{ __('Original Title') }}
                    </label>
                    <input id="modal_original_title" name="original_title" type="text"
                        value="{{ old('original_title') }}"
                        placeholder="{{ __('e.g. 進撃の巨人') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            {{-- Type & Status Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_type">
                        {{ __('Type') }} <span class="text-error">*</span>
                    </label>
                    <select id="modal_type" name="type" required
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all appearance-none cursor-pointer">
                        <option value="anime"  {{ old('type') === 'anime'   ? 'selected' : '' }}>Anime</option>
                        <option value="manga"  {{ old('type') === 'manga'   ? 'selected' : '' }}>Manga</option>
                        <option value="manhwa" {{ old('type') === 'manhwa'  ? 'selected' : '' }}>Manhwa</option>
                        <option value="manhua" {{ old('type') === 'manhua'  ? 'selected' : '' }}>Manhua</option>
                        <option value="novel"  {{ old('type') === 'novel'   ? 'selected' : '' }}>{{ __('Novel') }}</option>
                    </select>
                    @error('type') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_status">
                        {{ __('Status') }} <span class="text-error">*</span>
                    </label>
                    <select id="modal_status" name="status" required
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all appearance-none cursor-pointer">
                        <option value="watching"      {{ old('status') === 'watching'      ? 'selected' : '' }}>{{ __('Watching') }}</option>
                        <option value="reading"       {{ old('status') === 'reading'       ? 'selected' : '' }}>{{ __('Reading') }}</option>
                        <option value="rewatching"    {{ old('status') === 'rewatching'    ? 'selected' : '' }}>{{ __('Rewatching') }}</option>
                        <option value="completed"     {{ old('status') === 'completed'     ? 'selected' : '' }}>{{ __('Completed') }}</option>
                        <option value="on_hold"       {{ old('status') === 'on_hold'       ? 'selected' : '' }}>{{ __('On Hold') }}</option>
                        <option value="dropped"       {{ old('status') === 'dropped'       ? 'selected' : '' }}>{{ __('Dropped') }}</option>
                        <option value="plan_to_watch" {{ old('status', 'plan_to_watch') === 'plan_to_watch' ? 'selected' : '' }}>{{ __('Plan to Watch') }}</option>
                    </select>
                    @error('status') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Progress (shows dynamically based on type) --}}
            <div id="animeProgress" class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_current_episode">{{ __('Current Episode') }}</label>
                    <input id="modal_current_episode" name="current_episode" type="number" min="0"
                        value="{{ old('current_episode', 0) }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_total_episodes">{{ __('Total Episodes') }}</label>
                    <input id="modal_total_episodes" name="total_episodes" type="number" min="0"
                        value="{{ old('total_episodes') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            <div id="mangaProgress" class="grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Current Chapter') }}</label>
                    <input name="current_chapter" type="number" min="0" value="{{ old('current_chapter', 0) }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Total Chapters') }}</label>
                    <input name="total_chapters" type="number" min="0" value="{{ old('total_chapters') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Current Volume') }}</label>
                    <input name="current_volume" type="number" min="0" value="{{ old('current_volume', 0) }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Total Volumes') }}</label>
                    <input name="total_volumes" type="number" min="0" value="{{ old('total_volumes') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            {{-- Rating & MAL ID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_rating">
                        {{ __('Rating') }} <span class="text-on-surface-variant font-normal">(1–5)</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <input id="modal_rating" name="rating" type="number" min="1" max="5"
                            value="{{ old('rating') }}"
                            placeholder="—"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                    @error('rating') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_mal_id">
                        {{ __('MAL ID') }} <span class="text-on-surface-variant font-normal">({{ __('optional') }})</span>
                    </label>
                    <input id="modal_mal_id" name="mal_id" type="number" min="1"
                        value="{{ old('mal_id') }}"
                        placeholder="e.g. 16498"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    @error('mal_id') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Cover URL --}}
            <div class="space-y-2">
                <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_cover_url">
                    {{ __('Cover Image URL') }} <span class="text-on-surface-variant font-normal">({{ __('optional') }})</span>
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">image</span>
                    <input id="modal_cover_url" name="cover_url" type="url"
                        value="{{ old('cover_url') }}"
                        placeholder="https://..."
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                @error('cover_url') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
            </div>

            {{-- Notes --}}
            <div class="space-y-2">
                <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="modal_notes">
                    {{ __('Notes') }} <span class="text-on-surface-variant font-normal">({{ __('optional') }})</span>
                </label>
                <textarea id="modal_notes" name="notes" rows="3"
                    placeholder="{{ __('Your thoughts, recommendations, reminders...') }}"
                    class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all resize-none">{{ old('notes') }}</textarea>
                @error('notes') <p class="text-error text-xs mt-1 px-1">{{ $message }}</p> @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4 pt-2 border-t border-outline-variant/10">
                <button type="button" id="cancelAddModal"
                    class="px-6 py-3 rounded-full font-label font-bold text-sm text-on-surface-variant hover:text-on-surface bg-surface-container-highest hover:bg-surface-variant transition-all">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    class="gradient-cta px-10 py-3 rounded-full font-label font-bold text-on-primary text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95">
                    {{ __('Add to Archive') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MAL Search Modal (Livewire) --}}
@livewire('media-search-mal')

<script>
    (function () {
        const modal       = document.getElementById('addEntryModal');
        const panel       = document.getElementById('modalPanel');
        const backdrop    = document.getElementById('modalBackdrop');
        const typeSelect  = document.getElementById('modal_type');
        const animeBlock  = document.getElementById('animeProgress');
        const mangaBlock  = document.getElementById('mangaProgress');

        const ANIME_TYPES = ['anime'];

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

        function updateProgressFields() {
            const isAnime = ANIME_TYPES.includes(typeSelect.value);
            animeBlock.classList.toggle('hidden', !isAnime);
            mangaBlock.classList.toggle('hidden', isAnime);
        }

        // Triggers
        document.getElementById('openAddModal')?.addEventListener('click', openModal);
        document.getElementById('openAddModalEmpty')?.addEventListener('click', openModal);
        document.getElementById('openAddModalMobile')?.addEventListener('click', openModal);
        document.getElementById('closeAddModal').addEventListener('click', closeModal);
        document.getElementById('cancelAddModal').addEventListener('click', closeModal);
        backdrop.addEventListener('click', closeModal);

        // Type toggle
        typeSelect.addEventListener('change', updateProgressFields);
        updateProgressFields(); // init

        // Auto-open if there are validation errors
        @if ($errors->any())
            openModal();
        @endif

        // Esc key
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

        // Open MAL modal via Livewire event
        document.getElementById('openMalModal')?.addEventListener('click', () => {
            window.Livewire.dispatch('open-mal-search');
        });

        // Mobile Picker Logic
        document.getElementById('mobilePickerManual')?.addEventListener('click', () => {
            document.getElementById('mobileAddPicker')?.classList.add('hidden');
            openModal();
        });
        document.getElementById('mobilePickerMal')?.addEventListener('click', () => {
            document.getElementById('mobileAddPicker')?.classList.add('hidden');
            window.Livewire.dispatch('open-mal-search');
        });

        // Mobile: show a small picker when tapping Add (BottomNav)
        const mobileAddBtn = document.getElementById('openAddModalMobile');
        mobileAddBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            document.getElementById('mobileAddPicker')?.classList.toggle('hidden');
        });

        // Hide picker when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#mobileAddPicker') && !e.target.closest('#openAddModalMobile')) {
                document.getElementById('mobileAddPicker')?.classList.add('hidden');
            }
        });

        // Reload page when Livewire saves an entry
        document.addEventListener('livewire:entry-saved', () => window.location.reload());
        document.addEventListener('entry-saved', () => window.location.reload());
    })();
</script>
