<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <a href="{{ route('todos.index') }}" class="text-xs font-semibold text-brand-500 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 transition">
                    <i class="fa-solid fa-arrow-left mr-1"></i> {{ __('Voltar para tarefas') }}
                </a>
                <h2 class="font-extrabold text-2xl text-brand-950 dark:text-slate-100 leading-tight mt-1 transition-colors duration-300">
                    {{ $todo->title }}
                </h2>
            </div>
            <a href="{{ route('todos.edit', $todo->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-brand-600 dark:text-slate-200 bg-brand-50 dark:bg-slate-800 hover:bg-brand-100 dark:hover:bg-slate-700 border border-transparent dark:border-slate-700 transition">
                <i class="fa-solid fa-pen"></i> {{ __('Editar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('alert-success'))
                <div class="mb-6 animate-fade-in flex items-center gap-3 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 px-4 py-3 text-emerald-800 dark:text-emerald-400 shadow-sm transition-colors duration-300">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ session('alert-success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Coluna principal -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Status + Descrição -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 p-6 space-y-4 transition-colors duration-300">
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($todo->is_completed)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-3 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400">
                                    <i class="fa-solid fa-circle-check"></i> {{ __('Completo') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 px-3 py-1 text-xs font-bold text-amber-700 dark:text-amber-400">
                                    <i class="fa-solid fa-hourglass-half"></i> {{ __('Pendente') }}
                                </span>
                            @endif

                            @php
                                $priorityMap = [
                                    'high'   => ['label' => 'Alta', 'class' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400'],
                                    'medium' => ['label' => 'Média', 'class' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400'],
                                    'low'    => ['label' => 'Baixa', 'class' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400'],
                                ];
                                $p = $priorityMap[$todo->priority ?? 'medium'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $p['class'] }}">
                                <i class="fa-solid fa-flag"></i> {{ __('Prioridade') }} {{ $p['label'] }}
                            </span>

                            @if ($todo->due_date)
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold
                                    {{ $todo->is_overdue ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400' : 'bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-slate-300' }}">
                                    <i class="fa-regular fa-calendar"></i> {{ $todo->due_date->format('d/m/Y') }}
                                    @if ($todo->is_overdue) &middot; {{ __('atrasada') }} @endif
                                </span>
                            @endif

                            @if ($todo->category)
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold text-white shadow-xs"
                                    style="background-color: {{ $todo->category->color }}">
                                    <i class="{{ $todo->category->icon }}"></i> {{ $todo->category->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Etiquetas -->
                        <div x-data="{ adding: false }" class="flex flex-wrap items-center gap-2">
                            @foreach ($todo->labels ?? [] as $i => $label)
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold text-white shadow-xs"
                                    style="background-color: {{ $label['color'] }}">
                                    {{ $label['name'] }}
                                    <form method="POST" action="{{ route('todos.labels.destroy', [$todo->id, $i]) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="opacity-70 hover:opacity-100"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </span>
                            @endforeach

                            <button @click="adding = !adding" class="text-xs font-semibold text-brand-500 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 border border-dashed border-brand-300 dark:border-slate-600 rounded-full px-3 py-1 transition">
                                <i class="fa-solid fa-plus"></i> {{ __('Etiqueta') }}
                            </button>

                            <form x-show="adding" x-cloak method="POST" action="{{ route('todos.labels.store', $todo->id) }}"
                                class="flex items-center gap-2 mt-2 w-full" x-data="{ color: '#0c8fe6' }">
                                @csrf
                                <input type="text" name="name" required maxlength="30" placeholder="{{ __('Nome da etiqueta') }}"
                                    class="text-sm rounded-lg border-brand-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 focus:border-brand-500 focus:ring-brand-500 flex-1 transition-colors">
                                <input type="color" name="color" x-model="color" class="h-9 w-12 rounded-lg border border-brand-200 dark:border-slate-700 dark:bg-slate-900 p-1 cursor-pointer">
                                <button type="submit" class="px-3 py-2 rounded-lg bg-brand-600 text-white text-xs font-semibold hover:bg-brand-700 shadow-sm">{{ __('Add') }}</button>
                            </form>
                        </div>

                        <div class="border-t border-brand-50 dark:border-slate-700 pt-4 transition-colors">
                            <span class="text-xs font-semibold uppercase tracking-wide text-brand-400 dark:text-slate-400">{{ __('Descrição') }}</span>
                            <p class="mt-1 text-gray-700 dark:text-slate-200 leading-relaxed">{{ $todo->description ?: __('Sem descrição.') }}</p>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 p-6 transition-colors duration-300">
                        @php
                            $checklist = $todo->checklist ?? [];
                            $progress = $todo->checklist_progress;
                        @endphp

                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-bold text-brand-950 dark:text-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-list-check text-brand-500 dark:text-brand-400"></i> {{ __('Checklist') }}
                            </h3>
                            <span class="text-xs font-semibold text-brand-500 dark:text-brand-400">{{ $progress }}%</span>
                        </div>

                        @if (count($checklist))
                            <div class="h-1.5 w-full rounded-full bg-brand-50 dark:bg-slate-700 overflow-hidden mb-4">
                                <div class="h-full rounded-full bg-brand-500 transition-all" style="width: {{ $progress }}%"></div>
                            </div>
                        @endif

                        <div class="space-y-2">
                            @forelse ($checklist as $i => $item)
                                <div class="flex items-center gap-3 group">
                                    <form method="POST" action="{{ route('todos.checklist.toggle', [$todo->id, $i]) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="flex h-6 w-6 items-center justify-center rounded-md border-2 transition shrink-0
                                                {{ ($item['done'] ?? false) ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-brand-300 dark:border-slate-600 text-transparent hover:border-brand-500' }}">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </button>
                                    </form>
                                    <span class="flex-1 text-sm text-gray-700 dark:text-slate-200 {{ ($item['done'] ?? false) ? 'line-through text-gray-400 dark:text-slate-500' : '' }}">
                                        {{ $item['text'] }}
                                    </span>
                                    <form method="POST" action="{{ route('todos.checklist.destroy', [$todo->id, $i]) }}"
                                        class="opacity-0 group-hover:opacity-100 transition">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 dark:text-slate-600 hover:text-rose-500 dark:hover:text-rose-400"><i class="fa-solid fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 dark:text-slate-500">{{ __('Nenhum item ainda. Adicione o primeiro passo abaixo.') }}</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('todos.checklist.store', $todo->id) }}" class="flex items-center gap-2 mt-4">
                            @csrf
                            <input type="text" name="text" required maxlength="255" placeholder="{{ __('Adicionar item ao checklist...') }}"
                                class="flex-1 text-sm rounded-lg border-brand-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 focus:border-brand-500 focus:ring-brand-500 transition-colors">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 text-sm font-semibold hover:bg-brand-100 dark:hover:bg-slate-600 transition">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Anexos -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 p-6 transition-colors duration-300">
                        <h3 class="font-bold text-brand-950 dark:text-slate-100 flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-paperclip text-brand-500 dark:text-brand-400"></i> {{ __('Anexos') }}
                        </h3>

                        <div class="space-y-2 mb-4">
                            @forelse ($todo->attachments ?? [] as $i => $file)
                                <div class="flex items-center gap-3 rounded-lg border border-brand-50 dark:border-slate-700 px-3 py-2 bg-slate-50/50 dark:bg-slate-900/40">
                                    <i class="fa-solid fa-file text-brand-400"></i>
                                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                        class="flex-1 text-sm text-brand-700 dark:text-brand-400 hover:underline truncate">{{ $file['name'] }}</a>
                                    <form method="POST" action="{{ route('todos.attachments.destroy', [$todo->id, $i]) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 dark:text-slate-600 hover:text-rose-500 dark:hover:text-rose-400"><i class="fa-solid fa-trash text-xs"></i></button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 dark:text-slate-500">{{ __('Nenhum anexo enviado ainda.') }}</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('todos.attachments.store', $todo->id) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <input type="file" name="file" required
                                class="flex-1 text-sm text-gray-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 dark:file:bg-slate-700 file:text-brand-600 dark:file:text-brand-400 file:text-sm file:font-semibold hover:file:bg-brand-100 dark:hover:file:bg-slate-600 transition">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 text-sm font-semibold hover:bg-brand-100 dark:hover:bg-slate-600 transition">
                                <i class="fa-solid fa-upload"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Comentários -->
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 p-6 transition-colors duration-300">
                        <h3 class="font-bold text-brand-950 dark:text-slate-100 flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-comments text-brand-500 dark:text-brand-400"></i> {{ __('Comentários') }}
                        </h3>

                        <div class="space-y-3 mb-4">
                            @forelse (array_reverse($todo->comments ?? []) as $comment)
                                <div class="flex gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white text-xs font-bold">
                                        {{ strtoupper(substr($comment['user'], 0, 1)) }}
                                    </span>
                                    <div class="flex-1 bg-brand-50/60 dark:bg-slate-900/60 border border-transparent dark:border-slate-700/60 rounded-lg px-3 py-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-brand-900 dark:text-slate-200">{{ $comment['user'] }}</span>
                                            <span class="text-[11px] text-gray-400 dark:text-slate-500">{{ \Carbon\Carbon::parse($comment['at'])->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-slate-300 mt-0.5">{{ $comment['body'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400 dark:text-slate-500">{{ __('Seja o primeiro a comentar.') }}</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('todos.comments.store', $todo->id) }}" class="flex items-start gap-2">
                            @csrf
                            <textarea name="body" required rows="2" placeholder="{{ __('Escreva um comentário...') }}"
                                class="flex-1 text-sm rounded-lg border-brand-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-500 focus:border-brand-500 focus:ring-brand-500 transition-colors"></textarea>
                            <button type="submit" class="px-4 py-2 rounded-lg bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 h-fit shadow-sm">
                                {{ __('Enviar') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar de detalhes -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-card border border-brand-50 dark:border-slate-700 p-6 space-y-4 transition-colors duration-300">
                        <h3 class="font-bold text-brand-950 dark:text-slate-100 text-sm">{{ __('Detalhes') }}</h3>

                        <div>
                            <span class="text-xs text-gray-400 dark:text-slate-400">{{ __('Responsável') }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                @if ($todo->assignee)
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-600 text-white text-xs font-bold">
                                        {{ strtoupper(substr($todo->assignee->name, 0, 1)) }}
                                    </span>
                                    <span class="text-sm font-semibold text-brand-950 dark:text-slate-200">{{ $todo->assignee->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-slate-500">{{ __('Ninguém atribuído') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="border-t border-brand-50 dark:border-slate-700 pt-3 text-xs text-gray-400 dark:text-slate-400 space-y-1 transition-colors">
                            <p><i class="fa-regular fa-clock mr-1"></i>{{ __('Criada em') }} {{ $todo->created_at->format('d/m/Y H:i') }}</p>
                            <p><i class="fa-solid fa-rotate mr-1"></i>{{ __('Atualizada em') }} {{ $todo->updated_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <form method="POST" action="{{ route('todos.destroy', $todo->id) }}"
                            onsubmit="return confirm('{{ __('Tem certeza que deseja excluir esta tarefa?') }}');">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/40 border border-transparent dark:border-rose-800 transition">
                                <i class="fa-solid fa-trash"></i> {{ __('Excluir tarefa') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>