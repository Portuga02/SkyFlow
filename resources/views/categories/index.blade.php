<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 dark:text-slate-100 leading-tight transition-colors duration-300">
                    {{ __('Categorias') }}
                </h2>
                <p class="text-sm text-brand-600 dark:text-brand-400 mt-1 transition-colors duration-300">{{ __('Organize suas tarefas em categorias e subcategorias.') }}</p>
            </div>

            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                <i class="fa-solid fa-circle-plus"></i>
                {{ __('Nova Categoria') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('alert-success'))
                <div class="animate-fade-in flex items-center gap-3 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-emerald-800 dark:text-emerald-400 shadow-sm transition-colors duration-300">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ session('alert-success') }}</span>
                </div>
            @endif

            @if ($categories->isEmpty())
                <!-- Empty state -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 py-16 px-6 text-center transition-colors duration-300">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-500 dark:text-brand-400">
                        <i class="fa-solid fa-layer-group text-2xl"></i>
                    </div>
                    <h4 class="mt-4 text-lg font-bold text-brand-950 dark:text-slate-100">{{ __('Nenhuma categoria criada ainda') }}</h4>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ __('Crie categorias pra organizar melhor suas tarefas.') }}</p>
                    <a href="{{ route('categories.create') }}"
                        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                        <i class="fa-solid fa-circle-plus"></i> {{ __('Criar minha primeira categoria') }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($categories as $category)
                        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 overflow-hidden transition-colors duration-300">
                            <div class="p-5 flex items-start gap-4">
                                <div class="h-11 w-11 shrink-0 rounded-xl flex items-center justify-center text-white shadow-xs"
                                    style="background-color: {{ $category->color }}">
                                    <i class="{{ $category->icon }}"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-brand-950 dark:text-slate-100">{{ $category->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">
                                        {{ $category->todos_count }} {{ Str::plural('tarefa', $category->todos_count) }}
                                        @if ($category->children->isNotEmpty())
                                            &middot; {{ $category->children->count() }} {{ Str::plural('subcategoria', $category->children->count()) }}
                                        @endif
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('categories.edit', $category->id) }}" title="{{ __('Editar categoria') }}"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-slate-600 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('categories.destroy', $category->id) }}"
                                        onsubmit="return confirm('{{ __('Excluir esta categoria? As tarefas vinculadas ficarão sem categoria.') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="{{ __('Excluir categoria') }}"
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if ($category->children->isNotEmpty())
                                <div class="border-t border-brand-50 dark:border-slate-700 bg-brand-50/40 dark:bg-slate-900/40 px-5 py-3 space-y-2 transition-colors">
                                    @foreach ($category->children as $child)
                                        <div class="flex items-center gap-3">
                                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $child->color }}"></span>
                                            <span class="text-sm text-gray-600 dark:text-slate-300 flex-1">{{ $child->name }}</span>
                                            <span class="text-xs text-gray-400 dark:text-slate-500">{{ $child->todos->count() }}</span>
                                            <a href="{{ route('categories.edit', $child->id) }}" class="text-brand-400 hover:text-brand-600 dark:hover:text-brand-300 transition">
                                                <i class="fa-solid fa-pen text-[11px]"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>