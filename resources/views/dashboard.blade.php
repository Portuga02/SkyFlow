<x-app-layout>
    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- BANNER DE BOAS-VINDAS -->
            <div
                class="bg-gradient-to-r from-brand-600 to-blue-500 rounded-2xl p-8 text-white shadow-lg flex items-center justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-extrabold mb-2">{{ __('Olá, ') }}{{ Auth::user()->name }}! 🚀</h2>
                    <p class="text-blue-100 text-sm md:text-base">
                        {{ __('Aqui está o resumo do seu fluxo de trabalho de hoje.') }}</p>
                </div>
                <div
                    class="hidden md:flex h-16 w-16 bg-white/20 backdrop-blur-sm rounded-full items-center justify-center text-3xl font-bold shadow-inner relative z-10">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <!-- Efeito visual de fundo -->
                <div class="absolute -right-10 -top-10 h-40 w-40 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <!-- AÇÕES RÁPIDAS -->
            <div class="flex gap-4 overflow-x-auto pb-2">
                <a href="{{ route('todos.create') }}"
                    class="flex items-center gap-2 px-5 py-3 bg-white rounded-xl shadow-sm border border-brand-100 text-brand-950 font-semibold hover:bg-brand-50 hover:border-brand-300 transition whitespace-nowrap">
                    <i class="fa-solid fa-circle-plus text-brand-600"></i> Nova Tarefa
                </a>
                <a href="{{ route('kanban.index') }}"
                    class="flex items-center gap-2 px-5 py-3 bg-white rounded-xl shadow-sm border border-brand-100 text-brand-950 font-semibold hover:bg-brand-50 hover:border-brand-300 transition whitespace-nowrap">
                    <i class="fa-solid fa-table-columns text-amber-500"></i> Abrir Kanban
                </a>
                <button onclick="createQuickNote()"
                    class="flex items-center gap-2 px-5 py-3 bg-white rounded-xl shadow-sm border border-brand-100 text-brand-950 font-semibold hover:bg-brand-50 hover:border-brand-300 transition whitespace-nowrap">
                    <i class="fa-solid fa-note-sticky text-emerald-500"></i> Nota Rápida
                </button>
            </div>

            <!-- ESTATÍSTICAS (CARDS) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase">{{ __('Total de Tarefas') }}</p>
                        <h3 class="text-2xl font-black text-brand-950">{{ $stats['total'] }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase">{{ __('Pendentes') }}</p>
                        <h3 class="text-2xl font-black text-brand-950">{{ $stats['pending'] }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase">{{ __('Concluídas') }}</p>
                        <h3 class="text-2xl font-black text-brand-950">{{ $stats['completed'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- GRID PRINCIPAL (URGÊNCIAS E CATEGORIAS/NOTAS) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LADO ESQUERDO: Tarefas Urgentes (Fogo no Parquinho) -->
                <div
                    class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-brand-50 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-brand-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-extrabold text-lg text-brand-950 flex items-center gap-2">
                            <i class="fa-solid fa-fire text-rose-500"></i> Fogo no Parquinho (Urgentes)
                        </h3>
                        <a href="{{ route('todos.index') }}"
                            class="text-sm font-semibold text-brand-600 hover:text-brand-800">Ver todas</a>
                    </div>

                    <div class="p-6 flex-1">
                        @if ($urgentTodos->isEmpty())
                            <div class="h-full flex flex-col items-center justify-center text-center space-y-3 py-10">
                                <div
                                    class="h-16 w-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 text-2xl">
                                    <i class="fa-solid fa-mug-hot"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-brand-950">Tudo sob controle!</h4>
                                    <p class="text-sm text-gray-500">Não há tarefas de alta prioridade pendentes no
                                        momento.</p>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($urgentTodos as $todo)
                                    <div
                                        class="group flex items-start gap-4 p-4 rounded-xl border border-brand-50 hover:border-brand-200 hover:shadow-md transition bg-white">
                                        <!-- Botão AJAX para concluir a tarefa -->
                                        <button onclick="markAsDone({{ $todo->id }})" title="Marcar como Concluída"
                                            class="focus:outline-none group/btn mt-1 flex-shrink-0 cursor-pointer">
                                            <i
                                                class="fa-regular fa-circle text-gray-300 text-xl group-hover/btn:hidden"></i>
                                            <i
                                                class="fa-solid fa-circle-check text-emerald-500 text-xl hidden group-hover/btn:block transition"></i>
                                        </button>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('todos.show', $todo->id) }}"
                                                class="block font-bold text-brand-950 truncate hover:text-brand-600 transition">
                                                {{ $todo->title }}
                                            </a>
                                            <div class="flex items-center gap-3 mt-1.5">
                                                @if ($todo->due_date)
                                                    <span
                                                        class="text-xs font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-600">
                                                        <i class="fa-regular fa-calendar mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($todo->due_date)->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                                @if ($todo->category)
                                                    <span class="text-xs font-semibold px-2 py-0.5 rounded text-white"
                                                        style="background-color: {{ $todo->category->color }}">
                                                        {{ $todo->category->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- LADO DIREITO: Distribuição de Projetos e Notas -->
                <div class="space-y-6">

                    <!-- Foco por Projetos (Ex: SkyRadar, SkyMaps, etc) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-brand-50 p-6">
                        <h3 class="font-extrabold text-lg text-brand-950 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-blue-500"></i> Foco Atual
                        </h3>

                        @if (isset($categories) && $categories->count() > 0)
                            <div class="space-y-4">
                                @foreach ($categories as $category)
                                    <div>
                                        <div class="flex justify-between text-sm font-bold mb-1">
                                            <span class="text-brand-950">{{ $category->name }}</span>
                                            <span class="text-gray-500">{{ $category->todos_count }} pendentes</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                                            <!-- Calcula uma porcentagem fictícia baseada no total de pendentes gerais para gerar a barra -->
                                            @php
                                                $percent =
                                                    $stats['pending'] > 0
                                                        ? ($category->todos_count / $stats['pending']) * 100
                                                        : 0;
                                            @endphp
                                            <div class="h-2.5 rounded-full"
                                                style="width: {{ $percent }}%; background-color: {{ $category->color }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">Nenhuma categoria ativa no momento.</p>
                        @endif
                    </div>

                    <!-- Post-its (Últimas Notas) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-brand-50 p-6">
                        <h3 class="font-extrabold text-lg text-brand-950 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-thumbtack text-amber-500"></i> Anotações Recentes
                        </h3>

                        @if (isset($recentNotes) && $recentNotes->count() > 0)
                            <div class="space-y-3">
                                @foreach ($recentNotes as $note)
                                    <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-lg shadow-sm">
                                        <h4 class="font-bold text-sm text-amber-900 truncate">{{ $note->title }}</h4>
                                        <p class="text-xs text-amber-700 mt-1 line-clamp-2">{{ $note->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">O bloco de notas está vazio.</p>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        function markAsDone(id) {
            // Envia a ordem para a API do Kanban mudar o status para 'done'
            fetch('/kanban/move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        id: id,
                        status: 'done' // O slug da sua coluna de Concluído
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Se deu certo, recarrega a página suavemente para atualizar os gráficos
                        location.reload();
                    } else {
                        alert('Ocorreu um erro ao concluir a tarefa.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Falha na comunicação com o servidor.');
                });
        }

        function createQuickNote() {
            // Faz uma requisição silenciosa para criar a nota
            fetch('/notes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json' // Pede para o Laravel não tentar redirecionar na marra
                    },
                    body: JSON.stringify({
                        title: 'Nova Nota',
                        content: 'Clique para editar...',
                        color: '#fef08a' // Amarelinho padrão
                    })
                })
                .then(() => {
                    // Redireciona você direto pro Bloco de Notas!
                    window.location.href = '/notes';
                })
                .catch(err => {
                    console.error('Erro ao criar nota:', err);
                    window.location.href = '/notes';
                });
    }
    </script>

</x-app-layout>
