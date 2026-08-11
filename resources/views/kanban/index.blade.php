<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Kanban') }}</h2>
                <p class="text-sm text-brand-600 mt-1">{{ __('Arraste as tarefas entre colunas para mudar o status.') }}
                </p>
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

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
        <div class="overflow-x-auto pb-4">
            <div class="flex gap-6 min-w-max" style="min-width: fit-content;">

                <!-- LOOP DINÂMICO DE COLUNAS -->
                @foreach ($columns as $column)
                    <div
                        class="flex-shrink-0 w-96 flex flex-col bg-white rounded-2xl shadow-lg border-0 overflow-hidden">

                        <!-- Cabeçalho Dinâmico -->
                        <!-- Cabeçalho Dinâmico -->
                        <div class="p-4 border-b-2"
                            style="background-color: {{ $column->color }}15; border-color: {{ $column->color }}50;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full flex items-center justify-center text-white"
                                        style="background-color: {{ $column->color }}">
                                        <i class="fa-solid fa-layer-group text-sm"></i>
                                    </div>
                                    <h3 class="font-bold text-brand-950">{{ $column->name }}</h3>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span id="count-{{ $column->slug }}"
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-full text-white text-sm font-bold"
                                        style="background-color: {{ $column->color }}">0</span>

                                    <!-- NOVO BOTÃO DE EXCLUIR AQUI 👇 -->
                                    <button onclick="deleteColumn({{ $column->id }})"
                                        class="text-gray-400 hover:text-rose-500 transition" title="Excluir Coluna">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Corpo da Coluna -->
                        <div class="kanban-column flex-1 min-h-96 max-h-96 space-y-3 p-4 overflow-y-auto"
                            style="background-color: {{ $column->color }}05;" data-status="{{ $column->slug }}"
                            ondragover="event.preventDefault()" ondrop="handleDrop(event, '{{ $column->slug }}')">

                            @foreach ($todos->where('status', $column->slug) as $todo)
                                <!-- Card -->
                                <div class="kanban-card bg-white rounded-xl border-l-4 p-4 cursor-grab hover:shadow-lg transition hover:scale-102 shadow-md"
                                    style="border-left-color: {{ $column->color }};" draggable="true"
                                    data-id="{{ $todo->id }}" ondragstart="handleDragStart(event)"
                                    ondragend="handleDragEnd(event)">

                                    <p class="font-bold text-sm text-brand-950 mb-1">{{ $todo->title }}</p>
                                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ $todo->description }}</p>

                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        @if ($todo->priority === 'highest' || $todo->priority === 'high')
                                            <span
                                                class="text-[10px] font-bold text-white bg-rose-500 px-2.5 py-1 rounded-full">{{ __('Alta') }}</span>
                                        @elseif ($todo->priority === 'medium')
                                            <span
                                                class="text-[10px] font-bold text-white bg-amber-500 px-2.5 py-1 rounded-full">{{ __('Média') }}</span>
                                        @else
                                            <span
                                                class="text-[10px] font-bold text-white bg-blue-500 px-2.5 py-1 rounded-full">{{ __('Baixa') }}</span>
                                        @endif
                                    </div>

                                    <div class="flex justify-end">
                                        <a href="{{ route('todos.show', $todo->id) }}"
                                            style="color: {{ $column->color }}" class="hover:opacity-75 transition">
                                            <i class="fa-solid fa-arrow-right text-lg"></i>
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
                        class="w-full px-4 py-2.5 rounded-lg border border-brand-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-brand-950 mb-3">{{ __('Cor') }}</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="columnColor" value="#f59e0b"
                            class="h-10 w-20 rounded-lg cursor-pointer border-2 border-brand-200">
                        <div class="flex gap-2">
                            <button type="button" onclick="setColor('#f59e0b')"
                                class="h-8 w-8 rounded-lg bg-amber-500 border-2 border-gray-300 hover:border-gray-900 transition"></button>
                            <button type="button" onclick="setColor('#0c8fe6')"
                                class="h-8 w-8 rounded-lg bg-blue-500 border-2 border-gray-300 hover:border-gray-900 transition"></button>
                            <button type="button" onclick="setColor('#10b981')"
                                class="h-8 w-8 rounded-lg bg-emerald-500 border-2 border-gray-300 hover:border-gray-900 transition"></button>
                            <button type="button" onclick="setColor('#8b5cf6')"
                                class="h-8 w-8 rounded-lg bg-purple-500 border-2 border-gray-300 hover:border-gray-900 transition"></button>
                            <button type="button" onclick="setColor('#ec4899')"
                                class="h-8 w-8 rounded-lg bg-pink-500 border-2 border-gray-300 hover:border-gray-900 transition"></button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="closeNewColumnModal()"
                        class="px-5 py-2.5 rounded-lg font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg font-semibold text-white bg-brand-600 hover:bg-brand-700 transition shadow-lg">
                        {{ __('Criar Coluna') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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

            if (!name) return;

            fetch('/kanban/column/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        name,
                        color
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        closeNewColumnModal();
                        setTimeout(() => location.reload(), 300);
                    } else {
                        alert('Erro ao criar coluna: ' + (data.error || 'Desconhecido'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro na requisição. Verifique o console.');
                });
        }

        let draggedId = null;

        function handleDragStart(e) {
            draggedId = e.target.closest('.kanban-card').dataset.id;
            e.target.closest('.kanban-card').style.opacity = '0.5';
        }

        function handleDragEnd(e) {
            e.target.closest('.kanban-card').style.opacity = '1';
        }

        function handleDrop(e, status) {
            e.preventDefault();

            if (!draggedId) return;

            const card = document.querySelector(`[data-id="${draggedId}"]`);
            if (card) {
                // Adiciona o card à nova coluna visualmente
                e.currentTarget.appendChild(card);
                // Chama a API para salvar
                updateCardStatus(draggedId, status);
            }
            draggedId = null;
        }

        async function updateCardStatus(cardId, newStatus) {
            try {
                const response = await fetch('/kanban/move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        id: cardId,
                        status: newStatus
                    })
                });

                const data = await response.json();

                if (data.success) {
                    updateCounts();
                } else {
                    location.reload();
                }
            } catch (error) {
                location.reload();
            }
        }

        // Nova função dinâmica para contar os cards (funciona para infinitas colunas!)
        function updateCounts() {
            document.querySelectorAll('.kanban-column').forEach(column => {
                const status = column.dataset.status;
                const count = column.querySelectorAll('.kanban-card').length;
                const badge = document.getElementById('count-' + status);
                if (badge) {
                    badge.textContent = count;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', updateCounts);

        document.getElementById('newColumnModal').addEventListener('click', function(e) {
            if (e.target === this) closeNewColumnModal();
        });

        function deleteColumn(id) {
            if (!confirm('Tem certeza que deseja excluir esta coluna? As tarefas nela deixarão de aparecer no Kanban.')) {
                return;
            }

            fetch(`/kanban/column/${id}`, {
                    method: 'DELETE',
                    headers: {
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
    </script>

    <style>
        .hover\:scale-102:hover {
            transform: scale(1.02);
        }
    </style>
</x-app-layout>
