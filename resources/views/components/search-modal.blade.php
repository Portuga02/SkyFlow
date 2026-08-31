<div x-data="globalSearch()" 
     x-show="open" 
     x-cloak
     @keydown.escape.window="open = false" 
     @search-open.window="openModal()"
     class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20 bg-slate-900/60 backdrop-blur-sm flex items-start justify-center transition-all"
     style="display: none;">

    <!-- Backdrop Click -->
    <div class="fixed inset-0 transition-opacity" @click="open = false"></div>

    <!-- Modal Box -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 overflow-hidden transition-all transform"
         @click.stop
         x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <!-- Search Header -->
        <div class="relative flex items-center px-4 border-b border-gray-100">
            <i class="fa-solid fa-magnifying-glass text-gray-400 text-lg ml-2"></i>
            <input type="text" 
                   x-ref="searchInput"
                   x-model="query" 
                   @input="search()"
                   @keydown.arrow-down.prevent="navigateResults(1)"
                   @keydown.arrow-up.prevent="navigateResults(-1)"
                   @keydown.enter.prevent="selectResult()"
                   placeholder="{{ __('Buscar tarefas... (⌘K ou Ctrl+K)') }}"
                   class="w-full bg-transparent border-0 py-4 pl-3 pr-10 text-gray-800 placeholder:text-gray-400 focus:ring-0 focus:outline-none text-base">
            
            <template x-if="query">
                <button @click="query = ''; results = []; $refs.searchInput.focus()" class="text-gray-400 hover:text-gray-600 px-2 py-1">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </template>
        </div>

        <!-- Body / Results -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 p-2">
            <!-- Loading State -->
            <template x-if="loading">
                <div class="py-12 text-center text-gray-400">
                    <i class="fa-solid fa-circle-notch animate-spin text-2xl text-brand-500 mb-2"></i>
                    <p class="text-xs">{{ __('Buscando tarefas...') }}</p>
                </div>
            </template>

            <!-- Not Found State -->
            <template x-if="!loading && results.length === 0 && query.trim().length >= 2">
                <div class="py-12 text-center text-gray-400">
                    <i class="fa-regular fa-folder-open text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm font-medium text-gray-600">{{ __('Nenhuma tarefa encontrada') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Tente buscar por outro termo ou ID.') }}</p>
                </div>
            </template>

            <!-- Results List -->
            <template x-if="!loading && results.length > 0">
                <div class="space-y-1" role="listbox">
                    <template x-for="(result, index) in results" :key="result.id">
                        <a :href="result.url"
                           :class="selectedIndex === index ? 'bg-brand-50/80 border-brand-200 text-brand-900' : 'hover:bg-gray-50 border-transparent text-gray-700'"
                           @mouseenter="selectedIndex = index"
                           @click="open = false"
                           class="flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl border transition-all cursor-pointer group">

                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <span class="flex items-center justify-center w-7 h-7 rounded-lg shrink-0"
                                      :class="result.is_completed ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500'">
                                    <i class="fa-solid text-xs" :class="result.is_completed ? 'fa-check' : 'fa-list-check'"></i>
                                </span>

                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-sm text-gray-900 group-hover:text-brand-600 truncate" x-text="result.title"></p>
                                    <p class="text-xs text-gray-400 truncate mt-0.5" x-text="result.description || '{{ __('Sem descrição') }}'"></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <template x-if="result.category">
                                    <span class="text-[11px] font-medium text-white px-2 py-0.5 rounded-md shadow-sm"
                                          :style="`background-color: ${result.category_color || '#64748b'}`"
                                          x-text="result.category"></span>
                                </template>

                                <template x-if="result.priority === 'high'">
                                    <span class="text-[11px] font-semibold bg-rose-50 text-rose-600 border border-rose-100 px-2 py-0.5 rounded-md">{{ __('Alta') }}</span>
                                </template>
                                <template x-if="result.priority === 'medium'">
                                    <span class="text-[11px] font-semibold bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-md">{{ __('Média') }}</span>
                                </template>
                                <template x-if="result.priority === 'low'">
                                    <span class="text-[11px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded-md">{{ __('Baixa') }}</span>
                                </template>

                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300 group-hover:text-brand-400 group-hover:translate-x-0.5 transition-all"></i>
                            </div>
                        </a>
                    </template>
                </div>
            </template>

            <!-- Initial Helper State -->
            <template x-if="!loading && query.trim().length < 2">
                <div class="py-10 text-center text-gray-400">
                    <p class="text-xs">{{ __('Digite pelo menos 2 caracteres para iniciar a busca...') }}</p>
                </div>
            </template>
        </div>

        <!-- Footer Shortcuts -->
        <div class="px-4 py-2.5 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 font-sans font-semibold text-[10px] bg-white border border-gray-200 rounded shadow-xs">↑</kbd>
                    <kbd class="px-1.5 py-0.5 font-sans font-semibold text-[10px] bg-white border border-gray-200 rounded shadow-xs">↓</kbd>
                    <span>{{ __('navegar') }}</span>
                </span>
                <span class="inline-flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 font-sans font-semibold text-[10px] bg-white border border-gray-200 rounded shadow-xs">↵</kbd>
                    <span>{{ __('abrir') }}</span>
                </span>
                <span class="inline-flex items-center gap-1">
                    <kbd class="px-1.5 py-0.5 font-sans font-semibold text-[10px] bg-white border border-gray-200 rounded shadow-xs">ESC</kbd>
                    <span>{{ __('fechar') }}</span>
                </span>
            </div>
            <span class="font-medium text-gray-400" x-text="results.length + ' resultado' + (results.length !== 1 ? 's' : '')"></span>
        </div>
    </div>
</div>

<script>
    function globalSearch() {
        return {
            open: false,
            query: '',
            results: [],
            loading: false,
            selectedIndex: -1,
            debounceTimer: null,
            abortController: null,

            init() {
                document.addEventListener('keydown', (e) => {
                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                        e.preventDefault();
                        this.openModal();
                    }
                });

                this.$watch('open', (value) => {
                    if (value) {
                        this.$nextTick(() => this.$refs.searchInput.focus());
                    } else {
                        this.query = '';
                        this.results = [];
                        this.selectedIndex = -1;
                    }
                });
            },

            openModal() {
                this.open = true;
            },

            search() {
                clearTimeout(this.debounceTimer);
                this.selectedIndex = -1;

                if (this.query.trim().length < 2) {
                    this.results = [];
                    this.loading = false;
                    return;
                }

                this.loading = true;
                this.debounceTimer = setTimeout(() => {
                    if (this.abortController) {
                        this.abortController.abort();
                    }
                    this.abortController = new AbortController();

                    fetch(`/search?q=${encodeURIComponent(this.query)}`, { signal: this.abortController.signal })
                        .then(res => res.json())
                        .then(data => {
                            this.results = data;
                            this.loading = false;
                        })
                        .catch(err => {
                            if (err.name !== 'AbortError') {
                                this.loading = false;
                            }
                        });
                }, 250);
            },

            navigateResults(step) {
                if (this.results.length === 0) return;
                const total = this.results.length;
                this.selectedIndex = (this.selectedIndex + step + total) % total;
            },

            selectResult() {
                if (this.selectedIndex >= 0 && this.results[this.selectedIndex]) {
                    window.location.href = this.results[this.selectedIndex].url;
                }
            }
        }
    }
</script>