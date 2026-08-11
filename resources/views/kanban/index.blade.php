<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Kanban') }}</h2>
                <p class="text-sm text-brand-600 mt-1">{{ __('Arraste as tarefas entre colunas para mudar o status.') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('todos.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-50 hover:bg-brand-100 text-brand-600 text-sm font-semibold rounded-lg transition">
                    <i class="fa-solid fa-list"></i> {{ __('Voltar para Lista') }}
                </a>
                <button @click="showNewColumnModal = true"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-sm font-semibold rounded-lg transition">
                    <i class="fa-solid fa-circle-plus"></i> {{ __('Nova Coluna') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="kanbanApp()">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Kanban Grid -->
            <div class="grid gap-6 kanban-grid" :style="`grid-template-columns: repeat(auto-fit, minmax(350px, 1fr))`">
                <template x-for="(column, columnKey) in columns" :key="columnKey">
                    <div class="flex flex-col bg-white rounded-xl shadow-card border border-brand-50 overflow-hidden">
                        <!-- Column Header -->
                        <div class="p-4 border-b border-brand-50 flex items-center justify-between"
                            :style="`border-left: 4px solid ${column.color}`">
                            <div class="flex items-center gap-2">
                                <i :class="`fa-solid ${column.icon}`" :style="`color: ${column.color}`"></i>
                                <h3 class="font-bold text-brand-950" x-text="column.label"></h3>
                                <span class="ml-auto inline-flex items-center justify-center h-6 w-6 rounded-full bg-brand-100 text-brand-700 text-xs font-bold"
                                    x-text="todos.filter(t => t.status === columnKey).length"></span>
                            </div>
                            <button @click="deleteColumn(columnKey)" class="text-gray-400 hover:text-rose-600 transition text-sm">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>

                        <!-- Cards Area -->
                        <div class="kanban-column flex-1 min-h-96 space-y-3 p-3 overflow-y-auto" 
                            :data-status="columnKey"
                            @dragover.prevent
                            @drop="moveCard($event, columnKey)">
                            <template x-for="todo in todos.filter(t => t.status === columnKey)" :key="todo.id">
                                <div class="kanban-card bg-brand-50 rounded-lg border border-brand-200 p-4 cursor-grab active:cursor-grabbing hover:shadow-md transition"
                                    draggable="true"
                                    :data-id="todo.id"
                                    @dragstart="draggedCard = todo.id"
                                    @dragend="draggedCard = null">

                                    <p class="font-semibold text-sm text-brand-950" x-text="todo.title"></p>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="todo.description"></p>

                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        <template x-if="todo.priority === 'high'">
                                            <span class="inline-flex text-[10px] font-bold text-white bg-rose-500 px-2 py-0.5 rounded">{{ __('Alta') }}</span>
                                        </template>
                                        <template x-if="todo.priority === 'medium'">
                                            <span class="inline-flex text-[10px] font-bold text-white bg-amber-500 px-2 py-0.5 rounded">{{ __('Média') }}</span>
                                        </template>
                                        <template x-if="todo.priority === 'low'">
                                            <span class="inline-flex text-[10px] font-bold text-white bg-emerald-500 px-2 py-0.5 rounded">{{ __('Baixa') }}</span>
                                        </template>
                                        <template x-if="todo.category">
                                            <span class="text-[10px] font-bold text-white px-2 py-0.5 rounded" 
                                                :style="`background-color: ${todo.category_color}`"
                                                x-text="todo.category"></span>
                                        </template>
                                    </div>

                                    <div class="mt-2 text-right">
                                        <a :href="`/todos/${todo.id}`" class="text-[11px] font-semibold text-brand-500 hover:text-brand-700">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- New Column Modal -->
        <div x-show="showNewColumnModal" @click="if ($el === $event.target) showNewColumnModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-transition>
            
            <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6" @click.stop>
                <h3 class="text-xl font-bold text-brand-950 mb-4">{{ __('Nova Coluna') }}</h3>

                <form @submit.prevent="createColumn()" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-brand-950 mb-2">{{ __('Nome da Coluna') }}</label>
                        <input type="text" x-model="newColumn.name" placeholder="Ex: Em Revisão"
                            class="w-full px-3 py-2 rounded-lg border border-brand-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-brand-950 mb-2">{{ __('Cor') }}</label>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="newColumn.color" class="h-10 w-16 rounded-lg cursor-pointer">
                            <div class="flex gap-2">
                                <button type="button" @click="newColumn.color = '#3b82f6'" class="h-8 w-8 rounded-lg bg-blue-500 border-2 border-gray-300 hover:border-gray-900"></button>
                                <button type="button" @click="newColumn.color = '#8b5cf6'" class="h-8 w-8 rounded-lg bg-purple-500 border-2 border-gray-300 hover:border-gray-900"></button>
                                <button type="button" @click="newColumn.color = '#ec4899'" class="h-8 w-8 rounded-lg bg-pink-500 border-2 border-gray-300 hover:border-gray-900"></button>
                                <button type="button" @click="newColumn.color = '#f59e0b'" class="h-8 w-8 rounded-lg bg-amber-500 border-2 border-gray-300 hover:border-gray-900"></button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showNewColumnModal = false"
                            class="px-4 py-2 rounded-lg font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg font-semibold text-white bg-brand-600 hover:bg-brand-700 transition">
                            {{ __('Criar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function kanbanApp() {
            return {
                todos: @json($todos),
                columns: {},
                draggedCard: null,
                showNewColumnModal: false,
                newColumn: { name: '', color: '#3b82f6' },

                async init() {
                    await this.loadColumns();
                },

                async loadColumns() {
                    try {
                        const res = await fetch('/kanban/columns');
                        this.columns = await res.json();
                    } catch (err) {
                        console.error('Erro ao carregar colunas:', err);
                    }
                },

                moveCard(e, newStatus) {
                    if (!this.draggedCard) return;

                    const cardElement = document.querySelector(`[data-id="${this.draggedCard}"]`);
                    if (cardElement) {
                        const column = e.currentTarget;
                        column.appendChild(cardElement);
                    }

                    this.updateCardStatus(this.draggedCard, newStatus);
                },

                async updateCardStatus(cardId, newStatus) {
                    try {
                        const res = await fetch('/kanban/move', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ id: cardId, status: newStatus }),
                        });
                        const data = await res.json();
                        if (!data.success) {
                            alert(data.error || 'Erro ao mover card');
                            location.reload();
                        }
                    } catch (err) {
                        console.error('Erro:', err);
                        alert('Erro ao mover a tarefa');
                    }
                },

                async createColumn() {
                    try {
                        const res = await fetch('/kanban/column/create', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(this.newColumn),
                        });
                        if (res.ok) {
                            this.newColumn = { name: '', color: '#3b82f6' };
                            this.showNewColumnModal = false;
                            await this.loadColumns();
                        }
                    } catch (err) {
                        alert('Erro ao criar coluna');
                    }
                },

                async deleteColumn(columnKey) {
                    if (!confirm('Tem certeza? Os cards continuarão existindo.')) return;
                    
                    try {
                        const res = await fetch(`/kanban/column/${columnKey}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        });
                        if (res.ok) {
                            await this.loadColumns();
                        }
                    } catch (err) {
                        alert('Erro ao deletar coluna');
                    }
                }
            }
        }
    </script>
</x-app-layout>