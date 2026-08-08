<div x-data="globalSearch()" x-show="open" @keydown.escape="open = false" @search-open="open = true"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
    style="display: none;" @click="if ($el === $event.target) open = false">

    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg overflow-hidden"
        @click.stop x-transition>

        <!-- Search Input -->
        <div class="p-4 border-b border-brand-50">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-3.5 text-brand-400"></i>
                <input type="text" x-model="query" @input="search()"
                    placeholder="{{ __('Buscar tarefas... (⌘K ou Ctrl+K)') }}"
                    class="w-full pl-10 pr-4 py-3 border border-brand-200 rounded-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
            </div>
        </div>

        <!-- Results -->
        <div class="max-h-96 overflow-y-auto">
            <template x-if="loading">
                <div class="p-8 text-center">
                    <div class="inline-block">
                        <i class="fa-solid fa-spinner animate-spin text-brand-500 text-2xl"></i>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ __('Buscando...') }}</p>
                </div>
            </template>

            <template x-if="!loading && results.length === 0 && query">
                <div class="p-8 text-center">
                    <i class="fa-solid fa-magnifying-glass text-3xl text-gray-300 mb-3"></i>
                    <p class="text-sm text-gray-500">{{ __('Nenhuma tarefa encontrada.') }}</p>
                </div>
            </template>

            <template x-if="!loading && results.length > 0">
                <div class="space-y-1 p-2">
                    <template x-for="result in results" :key="result.id">
                        <a :href="result.url"
                            class="block px-4 py-3 rounded-lg hover:bg-brand-50 cursor-pointer transition"
                            @click="open = false">

                            <div class="flex items-center justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-brand-950" x-text="result.title"></p>
                                    <p class="text-xs text-gray-500 line-clamp-1 mt-0.5" x-text="result.description"></p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <template x-if="result.category">
                                        <span class="text-xs font-semibold text-white px-2 py-1 rounded"
                                            :style="`background-color: ${result.category_color}`"
                                            x-text="result.category"></span>
                                    </template>

                                    <template x-if="result.priority === 'high'">
                                        <span class="text-xs font-bold bg-rose-100 text-rose-700 px-2 py-1 rounded">{{ __('Alta') }}</span>
                                    </template>
                                    <template x-if="result.priority === 'medium'">
                                        <span class="text-xs font-bold bg-amber-100 text-amber-700 px-2 py-1 rounded">{{ __('Média') }}</span>
                                    </template>
                                    <template x-if="result.priority === 'low'">
                                        <span class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-1 rounded">{{ __('Baixa') }}</span>
                                    </template>

                                    <template x-if="result.is_completed">
                                        <i class="fa-solid fa-check text-emerald-500 text-xs"></i>
                                    </template>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
            </template>

            <template x-if="!loading && !query">
                <div class="p-8 text-center text-gray-500 text-sm">
                    {{ __('Digite para buscar tarefas...') }}
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
            <span>{{ __('Pressione ESC para sair') }}</span>
            <span x-text="results.length + ' resultado' + (results.length !== 1 ? 's' : '')"></span>
        </div>
    </div>

    <script>
        function globalSearch() {
            return {
                open: false,
                query: '',
                results: [],
                loading: false,
                debounceTimer: null,

                init() {
                    document.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                            e.preventDefault();
                            this.open = true;
                            setTimeout(() => document.querySelector('[x-data="globalSearch()"] input').focus(), 50);
                        }
                    });
                },

                search() {
                    clearTimeout(this.debounceTimer);

                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    this.loading = true;
                    this.debounceTimer = setTimeout(() => {
                        fetch(`/search?q=${encodeURIComponent(this.query)}`)
                            .then(r => r.json())
                            .then(data => {
                                this.results = data;
                                this.loading = false;
                            })
                            .catch(() => { this.loading = false; });
                    }, 300);
                }
            }
        }
    </script>
</div>
