<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 dark:text-slate-100 leading-tight transition-colors duration-300">
                    {{ __('Minhas Tarefas') }}
                </h2>
                <p class="text-sm text-brand-600 dark:text-brand-400 mt-1 transition-colors duration-300">{{ __('Organize seu fluxo e mantenha o foco no que importa.') }}</p>
            </div>

            <a href="{{ route('todos.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                <i class="fa-solid fa-circle-plus"></i>
                {{ __('Nova Tarefa') }}
            </a>

            <a href="{{ route('kanban.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-50 dark:bg-slate-800 hover:bg-brand-100 dark:hover:bg-slate-700 text-brand-600 dark:text-brand-400 text-sm font-semibold rounded-lg transition"
                title="{{ __('Ver em Kanban') }}">
                <i class="fa-solid fa-grip"></i>
                <span class="hidden sm:inline">{{ __('Kanban') }}</span>
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('alert-success'))
                <div class="animate-fade-in flex items-center gap-3 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-emerald-800 dark:text-emerald-400 shadow-sm transition-colors duration-300">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ session('alert-success') }}</span>
                </div>
            @endif

            @php
                $total = count($todoList);
                $completedCount = collect($todoList)->where('is_completed', true)->count();
                $pendingCount = $total - $completedCount;
                $progress = $total > 0 ? round(($completedCount / $total) * 100) : 0;
            @endphp

            <!-- Stat cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50 dark:border-slate-700 transition-colors duration-300">
                    <div class="h-12 w-12 rounded-xl bg-brand-100 dark:bg-brand-900/40 flex items-center justify-center text-brand-600 dark:text-brand-400">
                        <i class="fa-solid fa-list-ul text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950 dark:text-slate-100">{{ $total }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400 dark:text-slate-500">{{ __('Total de tarefas') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50 dark:border-slate-700 transition-colors duration-300">
                    <div class="h-12 w-12 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-hourglass-half text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950 dark:text-slate-100">{{ $pendingCount }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400 dark:text-slate-500">{{ __('Pendentes') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50 dark:border-slate-700 transition-colors duration-300">
                    <div class="h-12 w-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl font-extrabold text-brand-950 dark:text-slate-100">{{ $completedCount }}</p>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">{{ $progress }}%</span>
                        </div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400 dark:text-slate-500">{{ __('Concluídas') }}</p>
                        <div class="mt-1.5 h-1.5 w-full rounded-full bg-brand-50 dark:bg-slate-700 overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($total > 0)
                <div x-data="{ filter: 'all' }" class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 overflow-hidden transition-colors duration-300">
                    <div class="flex items-center gap-2 px-5 pt-5">
                        <button @click="filter = 'all'"
                            :class="filter === 'all' ? 'bg-brand-600 text-white' : 'bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-slate-600'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition">{{ __('Todas') }}</button>
                        <button @click="filter = 'pending'"
                            :class="filter === 'pending' ? 'bg-amber-500 text-white' : 'bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-slate-600'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition">{{ __('Pendentes') }}</button>
                        <button @click="filter = 'done'"
                            :class="filter === 'done' ? 'bg-emerald-500 text-white' : 'bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-slate-600'"
                            class="px-4 py-1.5 rounded-full text-xs font-semibold transition">{{ __('Concluídas') }}</button>
                    </div>

                    <ul class="p-5 space-y-3">
                        @foreach ($todoList as $todosLists)
                            <!-- AQUI ESTÁ A CORREÇÃO MÁGICA: Adicionei 'highest' e o fallback de segurança '?? #f59e0b' -->
                            <li x-show="filter === 'all' || (filter === 'pending' && {{ $todosLists->is_completed ? 'false' : 'true' }}) || (filter === 'done' && {{ $todosLists->is_completed ? 'true' : 'false' }})"
                                style="border-left-width: 4px; border-left-color: {{ ['low' => '#10b981', 'medium' => '#f59e0b', 'high' => '#f43f5e', 'highest' => '#be123c'][$todosLists->priority ?? 'medium'] ?? '#f59e0b' }};"
                                class="group animate-fade-in flex flex-col sm:flex-row sm:items-center gap-4 rounded-xl border {{ $todosLists->is_completed ? 'border-emerald-100 dark:border-emerald-900/30 bg-emerald-50/40 dark:bg-emerald-900/10' : 'border-brand-100 dark:border-slate-700 bg-white dark:bg-slate-800/50' }} p-4 hover:shadow-card-hover transition">

                                <form method="POST" action="{{ route('todos.toggle', $todosLists->id) }}" class="shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        title="{{ $todosLists->is_completed ? __('Marcar como pendente') : __('Marcar como concluída') }}"
                                        class="flex h-8 w-8 items-center justify-center rounded-full border-2 transition
                                            {{ $todosLists->is_completed ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-brand-300 dark:border-slate-600 text-transparent hover:border-brand-500' }}">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                </form>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="font-semibold text-brand-950 dark:text-slate-200 {{ $todosLists->is_completed ? 'line-through text-brand-400 dark:text-slate-500' : '' }}">
                                            {{ $todosLists->title }}
                                        </p>
                                        @foreach ($todosLists->labels ?? [] as $label)
                                            <span class="text-[10px] font-bold text-white px-2 py-0.5 rounded-full" style="background-color: {{ $label['color'] }}">
                                                {{ $label['name'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-slate-400 truncate {{ $todosLists->is_completed ? 'line-through' : '' }}">
                                        {{ $todosLists->description }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-1">
                                        @if ($todosLists->due_date)
                                            <span class="text-[11px] font-semibold {{ $todosLists->is_overdue ? 'text-rose-500' : 'text-gray-400 dark:text-slate-500' }}">
                                                <i class="fa-regular fa-calendar mr-1"></i>{{ $todosLists->due_date->format('d/m') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    @if ($todosLists->is_completed)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400">
                                            <i class="fa-solid fa-circle-check"></i> {{ __('Completo') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 px-3 py-1 text-xs font-bold text-amber-700 dark:text-amber-400">
                                            <i class="fa-solid fa-hourglass-half"></i> {{ __('Pendente') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('todos.show', $todosLists->id) }}" title="{{ __('Ver detalhes') }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-slate-600 transition">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('todos.edit', $todosLists->id) }}" title="{{ __('Editar tarefa') }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 hover:bg-brand-100 dark:hover:bg-slate-600 transition">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('todos.destroy', $todosLists->id) }}"
                                        onsubmit="return confirm('{{ __('Tem certeza que deseja excluir esta tarefa?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="{{ __('Excluir tarefa') }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 py-16 px-6 text-center transition-colors duration-300">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-900/40 text-brand-500 dark:text-brand-400">
                        <i class="fa-solid fa-clipboard-list text-2xl"></i>
                    </div>
                    <h4 class="mt-4 text-lg font-bold text-brand-950 dark:text-slate-100">{{ __('Nenhuma tarefa por aqui ainda') }}</h4>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ __('Que tal criar a primeira e organizar seu dia?') }}</p>
                    <a href="{{ route('todos.create') }}"
                        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                        <i class="fa-solid fa-circle-plus"></i> {{ __('Criar minha primeira tarefa') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>