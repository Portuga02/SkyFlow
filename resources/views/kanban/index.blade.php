<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Kanban') }}</h2>
                <p class="text-sm text-brand-600 mt-1">
                    {{ __('Arraste as tarefas entre colunas ou reordene as colunas segurando no cabeçalho.') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="openNewColumnModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-sm font-semibold rounded-lg transition">
                    <i class="fa-solid fa-circle-plus"></i> {{ __('Nova Coluna') }}
                </button>
                <a href="{{ route('todos.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-50 hover:bg-brand-100 text-brand-600 text-sm font-semibold rounded-lg transition">
                    <i class="fa-solid fa-list"></i> {{ __('Voltar para Lista') }}
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Import SortableJS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
        <div class="overflow-x-auto pb-6 custom-scrollbar">
            <!-- CONTAINER DAS COLUNAS (ARRASTÁVEL) -->
            <div id="kanban-board" class="flex items-start gap-6 min-w-max">

                <!-- LOOP DINÂMICO DE COLUNAS -->
                @foreach ($columns as $index => $column)
                    @php
                        $columnSlug = $column->slug ?? \Illuminate\Support\Str::slug($column->name);

                        // Filtra as tarefas correspondentes a esta coluna
                        $columnTodos = $todos->filter(function ($item) use ($column, $columnSlug, $index) {
                            // Se a tarefa já aponta diretamente para o ID da coluna
                            if (isset($item->kanban_column_id) && $item->kanban_column_id == $column->id) {
                                return true;
                            }

                            // Verificação por slug/status direto
                            if ($item->status === $columnSlug) {
                                return true;
                            }

                            // Mapeamento de colunas de conclusão (Última coluna ou com nomes conclusivos)
                            if (
                                in_array($columnSlug, ['concluido', 'done', 'finalizado', 'mergeado']) &&
                                ($item->is_completed || in_array($item->status, ['done', 'concluido']))
                            ) {
                                return true;
                            }

                            // Mapeamento de colunas iniciais (Primeira coluna recebe tarefas pendentes/antigas)
                            if (
                                $index === 0 &&
                                !$item->is_completed &&
                                (empty($item->status) || in_array($item->status, ['todo', 'a-fazer', 'pending']))
                            ) {
                                return true;
                            }

                            return false;
                        });
                    @endphp

                    <div class="kanban-column-container flex-1 min-w-[320px] max-w-[380px] flex flex-col bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden transition-all"
                        data-column-id="{{ $column->id }}">

                        <!-- Cabeçalho Dinâmico -->
                        <div class="column-header cursor-grab active:cursor-grabbing p-4 border-b select-none flex items-center justify-between"
                            style="background-color: {{ $column->color }}15; border-color: {{ $column->color }}40;">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg flex items-center justify-center text-white shadow-xs"
                                    style="background-color: {{ $column->color }}">
                                    <i class="fa-solid {{ $column->icon ?? 'fa-layer-group' }} text-sm"></i>
                                </div>
                                <h3 class="font-bold text-brand-950 text-sm truncate max-w-[140px]">{{ $column->name }}
                                </h3>
                            </div>

                            <div class="flex items-center gap-2">
                                <span
                                    class="column-badge inline-flex items-center justify-center h-6 min-w-[24px] px-1.5 rounded-full text-white text-xs font-bold shadow-xs"
                                    style="background-color: {{ $column->color }}">{{ $columnTodos->count() }}</span>

                                <button type="button" onclick="deleteColumn({{ $column->id }})"
                                    class="text-gray-400 hover:text-rose-500 transition p-1" title="Excluir Coluna">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Corpo da Coluna -->
                        <div class="kanban-tasks flex-1 min-h-[300px] h-fit p-3 space-y-3"
                            style="background-color: {{ $column->color }}06;" data-status="{{ $columnSlug }}"
                            data-column-id="{{ $column->id }}">

                            @foreach ($columnTodos as $todo)
                                @php
                                    $isOverdue = false;
                                    if ($todo->due_date && !$todo->is_completed) {
                                        $isOverdue = \Carbon\Carbon::parse($todo->due_date)->isPast();
                                    }
                                @endphp

                                <!-- Card da Tarefa -->
                                <div class="kanban-card bg-white rounded-xl border-l-4 p-4 shadow-sm hover:shadow-md transition cursor-grab active:cursor-grabbing {{ $isOverdue ? 'ring-1 ring-rose-200' : '' }}"
                                    style="border-left-color: {{ $isOverdue ? '#f43f5e' : $column->color }};"
                                    data-id="{{ $todo->id }}">

                                    <div class="flex items-start justify-between gap-2">
                                        <p class="font-bold text-sm text-brand-950 mb-1 leading-snug">
                                            {{ $todo->title }}
                                        </p>
                                        @if ($isOverdue)
                                            <span
                                                class="inline-flex items-center gap-1 text-[10px] font-extrabold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full shrink-0 animate-pulse">
                                                <i class="fa-solid fa-clock"></i> Atrasada
                                            </span>
                                        @endif
                                    </div>

                                    @if ($todo->description)
                                        <p class="text-xs text-gray-500 mb-3 line-clamp-2 leading-relaxed">
                                            {{ $todo->description }}
                                        </p>
                                    @endif

                                    <div
                                        class="flex items-center justify-between gap-2 mt-2 pt-2 border-t border-slate-50">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @if ($todo->priority === 'highest' || $todo->priority === 'high')
                                                <span
                                                    class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">Alta</span>
                                            @elseif ($todo->priority === 'medium')
                                                <span
                                                    class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">Média</span>
                                            @else
                                                <span
                                                    class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">Baixa</span>
                                            @endif

                                            @if ($todo->due_date)
                                                <span
                                                    class="text-[10px] font-medium {{ $isOverdue ? 'text-rose-600 font-bold' : 'text-gray-400' }} flex items-center gap-1">
                                                    <i class="fa-regular fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($todo->due_date)->format('d/m') }}
                                                </span>
                                            @endif
                                        </div>

                                        <a href="{{ route('todos.show', $todo->id) }}"
                                            style="color: {{ $column->color }}"
                                            class="hover:opacity-75 transition p-1">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- MODAL: NOVA COLUNA -->
    <div id="newColumnModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 border border-brand-100">
            <h3 class="text-2xl font-bold text-brand-950 mb-2">{{ __('Criar Nova Coluna') }}</h3>
            <p class="text-sm text-gray-600 mb-6">{{ __('Personalize seu quadro Kanban com novas colunas.') }}</p>

            <form id="newColumnForm" onsubmit="createNewColumn(event)" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-brand-950 mb-2">{{ __('Nome da Coluna') }}</label>
                    <input type="text" id="columnName" placeholder="Ex: Em Revisão, Bloqueado..."
                        class="w-full px-4 py-2.5 rounded-lg border border-brand-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none text-sm"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-brand-950 mb-3">{{ __('Cor') }}</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="columnColor" value="#f59e0b"
                            class="h-10 w-20 rounded-lg cursor-pointer border-2 border-brand-200">
                        <div class="flex gap-2">
                            <button type="button" onclick="setColor('#f59e0b')"
                                class="h-8 w-8 rounded-lg bg-amber-500 border border-gray-200 hover:scale-105 transition"></button>
                            <button type="button" onclick="setColor('#0c8fe6')"
                                class="h-8 w-8 rounded-lg bg-blue-500 border border-gray-200 hover:scale-105 transition"></button>
                            <button type="button" onclick="setColor('#10b981')"
                                class="h-8 w-8 rounded-lg bg-emerald-500 border border-gray-200 hover:scale-105 transition"></button>
                            <button type="button" onclick="setColor('#8b5cf6')"
                                class="h-8 w-8 rounded-lg bg-purple-500 border border-gray-200 hover:scale-105 transition"></button>
                            <button type="button" onclick="setColor('#ec4899')"
                                class="h-8 w-8 rounded-lg bg-pink-500 border border-gray-200 hover:scale-105 transition"></button>
                        </div>
                    </div>
                </div>

                <div x-data="{
                    selectedIcon: 'fa-layer-group',
                    icons: [
                        'fa-layer-group', 'fa-list-ul', 'fa-bars-staggered', 'fa-fire',
                        'fa-bug', 'fa-rocket', 'fa-code', 'fa-terminal',
                        'fa-database', 'fa-server', 'fa-paint-roller', 'fa-vial',
                        'fa-box-open', 'fa-hammer', 'fa-lightbulb', 'fa-star',
                        'fa-check-double', 'fa-flag-checkered', 'fa-triangle-exclamation', 'fa-calendar-days'
                    ]
                }" class="pt-2">

                    <label class="block text-sm font-semibold text-brand-950 mb-2">{{ __('Ícone da Coluna') }}</label>

                    <div
                        class="grid grid-cols-5 sm:grid-cols-10 gap-2 p-3 bg-slate-50 border border-slate-200 rounded-xl max-h-40 overflow-y-auto custom-scrollbar">
                        <template x-for="icon in icons" :key="icon">
                            <button type="button" @click="selectedIcon = icon"
                                :class="selectedIcon === icon ?
                                    'bg-brand-100 border-brand-500 text-brand-700 shadow-md scale-105' :
                                    'bg-white border-gray-200 text-gray-500 hover:bg-gray-100'"
                                class="h-9 w-9 flex items-center justify-center rounded-lg border transition-all">
                                <i class="fa-solid text-base" :class="icon"></i>
                            </button>
                        </template>
                    </div>

                    <input type="hidden" id="columnIcon" name="icon" x-model="selectedIcon">
                </div>

                <div class="flex gap-3 justify-end pt-4">
                    <button type="button" onclick="closeNewColumnModal()"
                        class="px-5 py-2.5 rounded-lg font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition text-sm">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg font-semibold text-white bg-brand-600 hover:bg-brand-700 transition shadow-md text-sm">
                        {{ __('Criar Coluna') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            updateCounts();

            // 1. Sortable para as Colunas
            const board = document.getElementById('kanban-board');
            if (board) {
                new Sortable(board, {
                    animation: 250,
                    handle: '.column-header',
                    ghostClass: 'opacity-30',
                    dragClass: 'shadow-2xl'
                });
            }

            // 2. Sortable para os Cards
            document.querySelectorAll('.kanban-tasks').forEach(column => {
                new Sortable(column, {
                    group: 'kanban-cards',
                    animation: 200,
                    ghostClass: 'opacity-40',
                    onEnd: function(evt) {
                        const cardId = evt.item.dataset.id;
                        const newStatus = evt.to.dataset.status;
                        const columnId = evt.to.dataset.columnId;

                        if (cardId && newStatus) {
                            updateCardStatus(cardId, newStatus, columnId);
                        }
                    }
                });
            });
        });

        async function updateCardStatus(cardId, newStatus, columnId) {
            try {
                const response = await fetch('/kanban/move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: cardId,
                        status: newStatus,
                        kanban_column_id: columnId
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    updateCounts();
                } else {
                    console.error('Erro retornado pelo backend:', data);
                    alert('Não foi possível mover a tarefa: ' + (data.message || JSON.stringify(data.errors ||
                        'Erro desconhecido')));
                    location.reload();
                }
            } catch (error) {
                console.error('Falha na requisição:', error);
                location.reload();
            }
        }

        function updateCounts() {
            document.querySelectorAll('.kanban-column-container').forEach(container => {
                const tasksContainer = container.querySelector('.kanban-tasks');
                const badge = container.querySelector('.column-badge');
                if (tasksContainer && badge) {
                    const count = tasksContainer.querySelectorAll('.kanban-card').length;
                    badge.textContent = count;
                }
            });
        }

        function openNewColumnModal() {
            document.getElementById('newColumnModal').classList.remove('hidden');
            document.getElementById('columnName').focus();
        }

        function closeNewColumnModal() {
            document.getElementById('newColumnModal').classList.add('hidden');
            document.getElementById('columnName').value = '';
        }

        function setColor(color) {
            document.getElementById('columnColor').value = color;
        }

        function createNewColumn(e) {
            e.preventDefault();

            const name = document.getElementById('columnName').value.trim();
            const color = document.getElementById('columnColor').value;
            const icon = document.getElementById('columnIcon').value;

            if (!name) return;

            fetch('/kanban/column/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        color,
                        icon
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        closeNewColumnModal();
                        setTimeout(() => location.reload(), 200);
                    } else {
                        alert('Erro ao criar coluna: ' + (data.error || 'Desconhecido'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro na requisição.');
                });
        }

        function deleteColumn(id) {
            if (!confirm('Tem certeza que deseja excluir esta coluna? As tarefas nela deixarão de aparecer no Kanban.')) {
                return;
            }

            fetch(`/kanban/column/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erro ao excluir a coluna.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro de conexão ao tentar excluir.');
                });
        }

        document.getElementById('newColumnModal').addEventListener('click', function(e) {
            if (e.target === this) closeNewColumnModal();
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-app-layout>
