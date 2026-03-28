{{-- EDIT ENTRY MODAL --}}
<div id="editEntryModal"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">

    {{-- Backdrop --}}
    <div id="editModalBackdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

    {{-- Modal Panel --}}
    <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-surface-container rounded-xl shadow-2xl shadow-black/60 flex flex-col
                transform scale-95 transition-transform duration-300" id="editModalPanel">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-8 py-6 border-b border-outline-variant/10">
            <div>
                <h3 class="font-headline font-black text-2xl tracking-tight">{{ __('Update Archive Entry') }}</h3>
                <p class="text-on-surface-variant text-sm mt-0.5">{{ __('Modify your progress and metadata.') }}</p>
            </div>
            <button id="closeEditModal" class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        {{-- Modal Form --}}
        <form id="editEntryForm" method="POST" action="" class="px-8 py-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Title Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_title">
                        {{ __('Title') }} <span class="text-error">*</span>
                    </label>
                    <input id="edit_modal_title" name="title" type="text" required
                        placeholder="{{ __('e.g. Attack on Titan') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_original_title">
                        {{ __('Original Title') }}
                    </label>
                    <input id="edit_modal_original_title" name="original_title" type="text"
                        placeholder="{{ __('e.g. 進撃の巨人') }}"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            {{-- Type & Status Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_type">
                        {{ __('Type') }} <span class="text-error">*</span>
                    </label>
                    <select id="edit_modal_type" name="type" required
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all appearance-none cursor-pointer">
                        <option value="anime">Anime</option>
                        <option value="manga">Manga</option>
                        <option value="manhwa">Manhwa</option>
                        <option value="manhua">Manhua</option>
                        <option value="novel">{{ __('Novel') }}</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_status">
                        {{ __('Status') }} <span class="text-error">*</span>
                    </label>
                    <select id="edit_modal_status" name="status" required
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all appearance-none cursor-pointer">
                        <option value="watching">{{ __('Watching') }}</option>
                        <option value="reading">{{ __('Reading') }}</option>
                        <option value="rewatching">{{ __('Rewatching') }}</option>
                        <option value="completed">{{ __('Completed') }}</option>
                        <option value="on_hold">{{ __('On Hold') }}</option>
                        <option value="dropped">{{ __('Dropped') }}</option>
                        <option value="plan_to_watch">{{ __('Plan to Watch') }}</option>
                    </select>
                </div>
            </div>

            {{-- Progress --}}
            <div id="editAnimeProgress" class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_current_episode">{{ __('Current Episode') }}</label>
                    <input id="edit_modal_current_episode" name="current_episode" type="number" min="0"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_total_episodes">{{ __('Total Episodes') }}</label>
                    <input id="edit_modal_total_episodes" name="total_episodes" type="number" min="0"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            <div id="editMangaProgress" class="grid grid-cols-2 md:grid-cols-4 gap-4 hidden">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Current Chapter') }}</label>
                    <input id="edit_modal_current_chapter" name="current_chapter" type="number" min="0"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Total Chapters') }}</label>
                    <input id="edit_modal_total_chapters" name="total_chapters" type="number" min="0"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Current Volume') }}</label>
                    <input id="edit_modal_current_volume" name="current_volume" type="number" min="0"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1">{{ __('Total Volumes') }}</label>
                    <input id="edit_modal_total_volumes" name="total_volumes" type="number" min="0"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            {{-- Rating & MAL ID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_rating">
                        {{ __('Rating') }} <span class="text-on-surface-variant font-normal">(1–5)</span>
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <input id="edit_modal_rating" name="rating" type="number" min="1" max="5"
                            placeholder="—"
                            class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_mal_id">
                        {{ __('MAL ID') }}
                    </label>
                    <input id="edit_modal_mal_id" name="mal_id" type="number" min="1"
                        placeholder="e.g. 16498"
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            {{-- Cover URL --}}
            <div class="space-y-2">
                <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_cover_url">
                    {{ __('Cover Image URL') }}
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-lg">image</span>
                    <input id="edit_modal_cover_url" name="cover_url" type="url"
                        placeholder="https://..."
                        class="w-full bg-surface-container-low border-none rounded-xl py-3.5 pl-12 pr-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all" />
                </div>
            </div>

            {{-- Notes --}}
            <div class="space-y-2">
                <label class="block text-xs font-label font-bold text-on-surface-variant uppercase tracking-wider px-1" for="edit_modal_notes">
                    {{ __('Notes') }}
                </label>
                <textarea id="edit_modal_notes" name="notes" rows="3"
                    placeholder="{{ __('Your thoughts...') }}"
                    class="w-full bg-surface-container-low border-none rounded-xl py-3.5 px-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/50 outline-none transition-all resize-none"></textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4 pt-2 border-t border-outline-variant/10">
                <button type="button" id="cancelEditModal"
                    class="px-6 py-3 rounded-full font-label font-bold text-sm text-on-surface-variant hover:text-on-surface bg-surface-container-highest hover:bg-surface-variant transition-all">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    class="gradient-cta px-10 py-3 rounded-full font-label font-bold text-on-primary text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95">
                    {{ __('Update Entry') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const modal       = document.getElementById('editEntryModal');
        const panel       = document.getElementById('editModalPanel');
        const backdrop    = document.getElementById('editModalBackdrop');
        const typeSelect  = document.getElementById('edit_modal_type');
        const animeBlock  = document.getElementById('editAnimeProgress');
        const mangaBlock  = document.getElementById('editMangaProgress');
        const form        = document.getElementById('editEntryForm');

        const ANIME_TYPES = ['anime'];

        function openModal(data) {
            // Fill form
            form.action = '{{ route("my-list.update", ":id") }}'.replace(':id', data.id);
            document.getElementById('edit_modal_title').value = data.title || '';
            document.getElementById('edit_modal_original_title').value = data.original_title || '';
            document.getElementById('edit_modal_type').value = data.type || 'anime';
            document.getElementById('edit_modal_status').value = data.status || 'watching';
            document.getElementById('edit_modal_rating').value = data.rating || '';
            document.getElementById('edit_modal_mal_id').value = data.mal_id || '';
            document.getElementById('edit_modal_cover_url').value = data.cover_url || '';
            document.getElementById('edit_modal_notes').value = data.notes || '';
            
            // Progress
            document.getElementById('edit_modal_current_episode').value = data.current_episode || 0;
            document.getElementById('edit_modal_total_episodes').value = data.total_episodes || '';
            document.getElementById('edit_modal_current_chapter').value = data.current_chapter || 0;
            document.getElementById('edit_modal_total_chapters').value = data.total_chapters || '';
            document.getElementById('edit_modal_current_volume').value = data.current_volume || 0;
            document.getElementById('edit_modal_total_volumes').value = data.total_volumes || '';

            updateProgressFields();

            // Show modal
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

        // Listen for open-edit-modal events
        window.addEventListener('open-edit-modal', (e) => {
            openModal(e.detail);
        });

        document.getElementById('closeEditModal').addEventListener('click', closeModal);
        document.getElementById('cancelEditModal').addEventListener('click', closeModal);
        backdrop.addEventListener('click', closeModal);
        typeSelect.addEventListener('change', updateProgressFields);

        // Esc key
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
    })();
</script>
