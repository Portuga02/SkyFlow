<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 dark:text-slate-100 leading-tight transition-colors duration-300">
                    <i class="fa-regular fa-calendar-days text-brand-500 mr-2"></i>{{ __('Calendário') }}
                </h2>
                <p class="text-sm text-brand-600 dark:text-brand-400 mt-1 transition-colors duration-300">
                    {{ __('Alterne entre Mês, Semana e Dia para gerenciar seu fluxo.') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('todos.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-soft transition">
                    <i class="fa-solid fa-plus"></i> {{ __('Nova Tarefa') }}
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $rawCategories = \App\Models\Category::orderBy('name')->get();
    @endphp

    <div class="py-8" x-data="calendarComponent(@js($todos ?? []))">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Barra Superior: Controles, Filtro de Período e Navegação -->
            <div class="bg-white dark:bg-slate-800/90 backdrop-blur-md rounded-2xl p-4 border border-brand-50 dark:border-slate-700 flex flex-wrap items-center justify-between gap-4 shadow-sm transition-colors duration-300">
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-black text-brand-950 dark:text-slate-100 tracking-tight" x-text="headerTitle"></h3>
                    <button @click="goToToday()" class="text-xs px-3 py-1.5 rounded-lg bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 font-semibold hover:bg-brand-100 dark:hover:bg-slate-600 transition">
                        {{ __('Hoje') }}
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Toggle de Visualização -->
                    <div class="flex items-center bg-slate-100 dark:bg-slate-900/80 p-1 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold">
                        <button @click="viewMode = 'month'"
                            :class="viewMode === 'month' ? 'bg-white dark:bg-slate-800 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                            class="px-3 py-1.5 rounded-lg transition">
                            {{ __('Mês') }}
                        </button>
                        <button @click="viewMode = 'week'"
                            :class="viewMode === 'week' ? 'bg-white dark:bg-slate-800 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                            class="px-3 py-1.5 rounded-lg transition">
                            {{ __('Semana') }}
                        </button>
                        <button @click="viewMode = 'day'"
                            :class="viewMode === 'day' ? 'bg-white dark:bg-slate-800 text-brand-600 dark:text-brand-400 shadow-sm' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                            class="px-3 py-1.5 rounded-lg transition">
                            {{ __('Dia') }}
                        </button>
                    </div>

                    <!-- Setas de Avanço e Recuo -->
                    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-900/80 p-1 rounded-xl border border-slate-200 dark:border-slate-700">
                        <button @click="navigate(-1)" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <button @click="navigate(1)" class="h-8 w-8 flex items-center justify-center rounded-lg hover:bg-white dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grade do Calendário -->
            <div class="bg-white dark:bg-slate-800/90 backdrop-blur-md rounded-3xl border border-brand-50 dark:border-slate-700 shadow-card overflow-hidden transition-colors duration-300">
                
                <template x-if="viewMode !== 'day'">
                    <div class="grid grid-cols-7 border-b border-slate-100 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-900/40 text-center py-3">
                        <template x-for="dayName in ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']" :key="dayName">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400" x-text="dayName"></span>
                        </template>
                    </div>
                </template>

                <!-- 1. Visualização Mensal -->
                <template x-if="viewMode === 'month'">
                    <div class="grid grid-cols-7 auto-rows-fr divide-x divide-y divide-slate-100 dark:divide-slate-700/60 bg-slate-100/20 dark:bg-slate-900/10">
                        <template x-for="(cell, index) in monthCells" :key="index">
                            <div 
                                @dragover.prevent
                                @dragenter.prevent="dragOverDate = cell.dateString"
                                @dragleave.prevent="if(dragOverDate === cell.dateString) dragOverDate = null"
                                @drop.prevent="handleDrop(cell.dateString)"
                                @click="openQuickCreate(cell.dateString)"
                                :class="{
                                    'bg-slate-50/60 dark:bg-slate-900/40 opacity-40': !cell.isCurrentMonth,
                                    'bg-brand-500/10 dark:bg-brand-500/15 border-brand-500 ring-2 ring-brand-500/30 ring-inset': dragOverDate === cell.dateString,
                                    'bg-white dark:bg-slate-800': cell.isCurrentMonth && dragOverDate !== cell.dateString
                                }"
                                class="min-h-[125px] p-2 flex flex-col justify-between transition-all group relative cursor-pointer hover:bg-slate-50/80 dark:hover:bg-slate-700/30">

                                <div class="flex items-center justify-between mb-1.5 pointer-events-none">
                                    <span 
                                        :class="{
                                            'bg-brand-600 text-white font-bold h-6 w-6 rounded-full flex items-center justify-center shadow-md shadow-brand-500/30': cell.isToday,
                                            'text-slate-700 dark:text-slate-300 font-semibold text-xs': !cell.isToday && cell.isCurrentMonth,
                                            'text-slate-400 dark:text-slate-600 text-xs': !cell.isCurrentMonth
                                        }"
                                        x-text="cell.dayNumber">
                                    </span>
                                    <span class="opacity-0 group-hover:opacity-100 text-[10px] text-brand-600 dark:text-brand-400 font-semibold transition">
                                        <i class="fa-solid fa-plus"></i>
                                    </span>
                                </div>

                                <div class="space-y-1.5 flex-1 overflow-y-auto max-h-[85px] no-scrollbar">
                                    <template x-for="todo in getTodosForDate(cell.dateString)" :key="todo.id">
                                        <div 
                                            draggable="true"
                                            @dragstart="handleDragStart(todo)"
                                            @click.stop="window.location.href = `/tarefas/${todo.id}`"
                                            :class="{
                                                'opacity-50 line-through': todo.is_completed,
                                                'border-l-rose-500': todo.priority === 'high' || todo.priority === 'highest',
                                                'border-l-amber-500': todo.priority === 'medium',
                                                'border-l-sky-500': todo.priority === 'low' || todo.priority === 'lowest'
                                            }"
                                            class="text-xs p-1.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80 border-l-[3px] shadow-2xs hover:shadow-md transition-all cursor-grab active:cursor-grabbing flex items-center gap-1.5">
                                            <i class="fa-solid fa-grip-vertical text-[10px] text-slate-300 dark:text-slate-600"></i>
                                            <span class="font-medium text-slate-800 dark:text-slate-200 truncate flex-1" x-text="todo.title"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- 2. Visualização Semanal -->
                <template x-if="viewMode === 'week'">
                    <div class="grid grid-cols-7 divide-x divide-slate-100 dark:divide-slate-700/60 bg-slate-100/20 dark:bg-slate-900/10 min-h-[480px]">
                        <template x-for="cell in weekCells" :key="cell.dateString">
                            <div 
                                @dragover.prevent
                                @dragenter.prevent="dragOverDate = cell.dateString"
                                @dragleave.prevent="if(dragOverDate === cell.dateString) dragOverDate = null"
                                @drop.prevent="handleDrop(cell.dateString)"
                                @click="openQuickCreate(cell.dateString)"
                                :class="{
                                    'bg-brand-500/10 dark:bg-brand-500/15 border-brand-500 ring-2 ring-brand-500/30 ring-inset': dragOverDate === cell.dateString,
                                    'bg-white dark:bg-slate-800': dragOverDate !== cell.dateString
                                }"
                                class="p-3 flex flex-col justify-between transition-all group relative cursor-pointer hover:bg-slate-50/80 dark:hover:bg-slate-700/30">

                                <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-slate-700/40 pointer-events-none">
                                    <span 
                                        :class="cell.isToday ? 'bg-brand-600 text-white font-bold h-7 w-7 rounded-full flex items-center justify-center shadow-md' : 'text-slate-700 dark:text-slate-300 font-bold text-sm'"
                                        x-text="cell.dayNumber">
                                    </span>
                                    <span class="text-[10px] uppercase font-bold text-slate-400" x-text="cell.monthName"></span>
                                </div>

                                <div class="space-y-2 flex-1 mt-3 overflow-y-auto max-h-[380px] no-scrollbar">
                                    <template x-for="todo in getTodosForDate(cell.dateString)" :key="todo.id">
                                        <div 
                                            draggable="true"
                                            @dragstart="handleDragStart(todo)"
                                            @click.stop="window.location.href = `/tarefas/${todo.id}`"
                                            :class="{
                                                'opacity-50 line-through': todo.is_completed,
                                                'border-l-rose-500': todo.priority === 'high' || todo.priority === 'highest',
                                                'border-l-amber-500': todo.priority === 'medium',
                                                'border-l-sky-500': todo.priority === 'low' || todo.priority === 'lowest'
                                            }"
                                            class="text-xs p-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80 border-l-[3px] shadow-sm hover:shadow-md transition cursor-grab active:cursor-grabbing flex flex-col gap-1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-slate-800 dark:text-slate-100 truncate" x-text="todo.title"></span>
                                                <i class="fa-solid fa-grip-vertical text-[10px] text-slate-300 dark:text-slate-600"></i>
                                            </div>
                                            <template x-if="todo.category">
                                                <span class="text-[10px] font-semibold text-brand-600 dark:text-brand-400" x-text="todo.category.name"></span>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- 3. Visualização Diária -->
                <template x-if="viewMode === 'day'">
                    <div class="p-6 bg-white dark:bg-slate-800 min-h-[400px]">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700/80">
                            <div>
                                <h4 class="text-2xl font-black text-brand-950 dark:text-slate-100" x-text="dayViewTitle"></h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Atividades agendadas para este dia') }}</p>
                            </div>
                            <button @click="openQuickCreate(currentDateStr)" class="px-3.5 py-2 rounded-xl bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 font-bold text-xs hover:bg-brand-100 dark:hover:bg-slate-600 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> {{ __('Criar Tarefa') }}
                            </button>
                        </div>

                        <div class="mt-6 space-y-3 max-w-2xl">
                            <template x-for="todo in getTodosForDate(currentDateStr)" :key="todo.id">
                                <div 
                                    @click="window.location.href = `/tarefas/${todo.id}`"
                                    :class="{
                                        'opacity-50 line-through': todo.is_completed,
                                        'border-l-rose-500': todo.priority === 'high' || todo.priority === 'highest',
                                        'border-l-amber-500': todo.priority === 'medium',
                                        'border-l-sky-500': todo.priority === 'low' || todo.priority === 'lowest'
                                    }"
                                    class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 border-l-4 shadow-sm hover:shadow-md transition cursor-pointer flex items-center justify-between gap-4">
                                    <div>
                                        <h5 class="font-bold text-slate-800 dark:text-slate-100 text-sm" x-text="todo.title"></h5>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5" x-text="todo.description || 'Sem descrição.'"></p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <template x-if="todo.category">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-brand-50 dark:bg-brand-900/40 text-brand-600 dark:text-brand-300" x-text="todo.category.name"></span>
                                        </template>
                                        <i class="fa-solid fa-arrow-right text-xs text-slate-400"></i>
                                    </div>
                                </div>
                            </template>

                            <template x-if="getTodosForDate(currentDateStr).length === 0">
                                <div class="py-12 text-center">
                                    <i class="fa-regular fa-calendar-check text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                                    <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('Nenhuma tarefa agendada para hoje.') }}</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal de Criação Rápida Seguro via AJAX -->
        <div x-show="quickModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-transition>
            <div @click.outside="quickModalOpen = false" class="w-full max-w-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-extrabold text-lg text-brand-950 dark:text-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-brand-500"></i> {{ __('Criar Tarefa Rápida') }}
                    </h4>
                    <button @click="quickModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('todos.store') }}" class="space-y-4" @submit.prevent="submitQuickTask">
                    @csrf
                    <input type="hidden" name="due_date" :value="quickDateFormatted">

                    <div>
                        <x-input-label for="quick_title" class="dark:text-slate-200">{{ __('Título') }}</x-input-label>
                        <x-text-input id="quick_title" name="title" required class="w-full mt-1 dark:bg-slate-900 dark:border-slate-700" placeholder="{{ __('Ex: Reunião de alinhamento') }}" autofocus />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="quick_priority" class="dark:text-slate-200">{{ __('Prioridade') }}</x-input-label>
                            <select id="quick_priority" name="priority" class="block w-full mt-1 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 text-sm focus:border-brand-500 focus:ring-brand-500">
                                <option value="highest">🔴 Urgente</option>
                                <option value="high">🟠 Alta</option>
                                <option value="medium" selected>🟡 Média</option>
                                <option value="low">🔵 Baixa</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="quick_category" class="dark:text-slate-200">{{ __('Categoria') }}</x-input-label>
                            <select id="quick_category" name="category_id" class="block w-full mt-1 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 text-sm focus:border-brand-500 focus:ring-brand-500">
                                <option value="">{{ __('Nenhuma') }}</option>
                                @foreach ($rawCategories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="quickModalOpen = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            {{ __('Cancelar') }}
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-soft transition">
                            {{ __('Salvar') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script de Gestão do Calendário -->
    <script>
        function calendarComponent(initialTodos) {
            return {
                viewMode: 'month',
                currentDate: new Date(),
                todos: initialTodos,
                draggedTodo: null,
                dragOverDate: null,
                quickModalOpen: false,
                quickDateFormatted: '',

                months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],

                get currentDateStr() {
                    const y = this.currentDate.getFullYear();
                    const m = String(this.currentDate.getMonth() + 1).padStart(2, '0');
                    const d = String(this.currentDate.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                },

                get headerTitle() {
                    if (this.viewMode === 'day') {
                        return `${this.currentDate.getDate()} de ${this.months[this.currentDate.getMonth()]} de ${this.currentDate.getFullYear()}`;
                    }
                    if (this.viewMode === 'week') {
                        const start = this.getWeekStart(this.currentDate);
                        const end = new Date(start);
                        end.setDate(end.getDate() + 6);
                        return `${start.getDate()} ${this.months[start.getMonth()].slice(0, 3)} - ${end.getDate()} ${this.months[end.getMonth()].slice(0, 3)} ${end.getFullYear()}`;
                    }
                    return `${this.months[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
                },

                get dayViewTitle() {
                    const days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
                    return `${days[this.currentDate.getDay()]}, ${this.currentDate.getDate()} de ${this.months[this.currentDate.getMonth()]}`;
                },

                get monthCells() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const firstDayIndex = new Date(year, month, 1).getDay();
                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const prevMonthDays = new Date(year, month, 0).getDate();

                    const cells = [];
                    const todayStr = new Date().toISOString().slice(0, 10);

                    for (let i = firstDayIndex - 1; i >= 0; i--) {
                        const d = prevMonthDays - i;
                        const m = month === 0 ? 12 : month;
                        const y = month === 0 ? year - 1 : year;
                        const dateString = `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                        cells.push({ dayNumber: d, dateString, isCurrentMonth: false, isToday: dateString === todayStr });
                    }

                    for (let d = 1; d <= daysInMonth; d++) {
                        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                        cells.push({ dayNumber: d, dateString, isCurrentMonth: true, isToday: dateString === todayStr });
                    }

                    const remaining = (7 - (cells.length % 7)) % 7;
                    for (let d = 1; d <= remaining; d++) {
                        const m = month + 2 > 12 ? 1 : month + 2;
                        const y = month + 2 > 12 ? year + 1 : year;
                        const dateString = `${y}-${String(m).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                        cells.push({ dayNumber: d, dateString, isCurrentMonth: false, isToday: dateString === todayStr });
                    }

                    return cells;
                },

                get weekCells() {
                    const start = this.getWeekStart(this.currentDate);
                    const cells = [];
                    const todayStr = new Date().toISOString().slice(0, 10);

                    for (let i = 0; i < 7; i++) {
                        const day = new Date(start);
                        day.setDate(day.getDate() + i);
                        const y = day.getFullYear();
                        const m = String(day.getMonth() + 1).padStart(2, '0');
                        const d = String(day.getDate()).padStart(2, '0');
                        const dateString = `${y}-${m}-${d}`;
                        cells.push({
                            dayNumber: day.getDate(),
                            monthName: this.months[day.getMonth()].slice(0, 3),
                            dateString: dateString,
                            isToday: dateString === todayStr
                        });
                    }
                    return cells;
                },

                getWeekStart(date) {
                    const d = new Date(date);
                    const day = d.getDay();
                    const diff = d.getDate() - day;
                    return new Date(d.setDate(diff));
                },

                navigate(step) {
                    if (this.viewMode === 'day') {
                        this.currentDate = new Date(this.currentDate.setDate(this.currentDate.getDate() + step));
                    } else if (this.viewMode === 'week') {
                        this.currentDate = new Date(this.currentDate.setDate(this.currentDate.getDate() + (step * 7)));
                    } else {
                        this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + step, 1);
                    }
                },

                goToToday() {
                    this.currentDate = new Date();
                },

                getTodosForDate(dateString) {
                    return this.todos.filter(t => {
                        if (!t.due_date) return false;
                        // Extrai a parte da data de forma segura, ignorando horas
                        return String(t.due_date).substring(0, 10) === dateString;
                    });
                },

                handleDragStart(todo) {
                    this.draggedTodo = todo;
                },

              async handleDrop(newDateString) {
                    this.dragOverDate = null;
                    if (!this.draggedTodo) return;

                    const todoId = this.draggedTodo.id;
                    const oldDate = this.draggedTodo.due_date;
                    
                    // Coloca meio-dia como padrão para não sumir no fuso horário
                    const updatedDueDate = `${newDateString} 12:00:00`;

                    // Atualiza na tela na hora para o usuário não esperar
                    this.draggedTodo.due_date = updatedDueDate;

                    try {
                        // Bate na rota exclusiva de reagendamento!
                        const response = await fetch('{{ route("calendar.reschedule") }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                id: todoId,
                                due_date: updatedDueDate
                            })
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            // Se der ruim, desfaz a alteração visual
                            this.draggedTodo.due_date = oldDate;
                            console.error("Erro na validação:", data);
                        }
                    } catch (error) {
                        this.draggedTodo.due_date = oldDate;
                        console.error("Erro de conexão:", error);
                    } finally {
                        this.draggedTodo = null;
                    }
                },
                openQuickCreate(dateString) {
                    // Substitui a letra T por um espaço em branco para não quebrar o banco de dados
                    this.quickDateFormatted = `${dateString} 09:00:00`;
                    this.quickModalOpen = true;
                },

                // Função Mágica para interceptar o envio e manter você no calendário
                submitQuickTask(e) {
                    const form = e.target;
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Salvando...';
                    submitBtn.disabled = true;

                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        // Força um recarregamento da página atual para puxar a nova tarefa visualmente
                        window.location.reload();
                    })
                    .catch(err => {
                        alert('Erro ao criar a tarefa. Verifique a conexão.');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                }
            };
        }
    </script>
</x-app-layout>