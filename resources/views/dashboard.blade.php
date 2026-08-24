<x-app-layout>
    @php
        $user = Auth::user();

        // 1. Lógica do Avatar
        $avatarUrl = !empty($user->avatar_path)
            ? asset('storage/' . $user->avatar_path)
            : 'https://api.dicebear.com/7.x/notionists/svg?seed=' .
                urlencode($user->name) .
                '&backgroundColor=e0e7ff,fef3c7,dbeafe,fce7f3';

        // 2. Lógica de Saudação e Primeiro Nome
        $hora = now()->format('H');
        $saudacao = 'Boa noite';
        if ($hora >= 5 && $hora < 12) {
            $saudacao = 'Bom dia';
        } elseif ($hora >= 12 && $hora < 18) {
            $saudacao = 'Boa tarde';
        }
        $primeiroNome = explode(' ', trim($user->name))[0];
    @endphp

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- BANNER DE BOAS-VINDAS -->
            <div
                class="bg-gradient-to-r from-brand-600 to-blue-500 rounded-2xl p-6 md:p-8 text-white shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between relative overflow-hidden gap-6">
                <!-- Textos da Esquerda -->
                <div class="relative z-10">
                    <h2 class="text-3xl font-extrabold mb-2">{{ $saudacao }}, {{ $primeiroNome }}! 🚀</h2>
                    <p class="text-blue-100 text-sm md:text-base">
                        {{ __('Aqui está o resumo do seu fluxo de trabalho de hoje.') }}
                    </p>
                </div>

                <!-- Lado Direito: Clima + Avatar -->
                <div
                    class="relative z-10 flex items-center gap-4 md:gap-6 w-full md:w-auto justify-between md:justify-end">

                    <!-- Widget de Clima (Efeito Vidro) -->
                    <div id="weather-widget"
                        class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20 shadow-sm transition-all hover:bg-white/20">
                        <div class="text-2xl drop-shadow-sm" id="weather-icon">⏳</div>
                        <div class="flex flex-col justify-center">
                            <div class="font-bold text-lg leading-none" id="temp">--°C</div>
                            <div class="text-xs text-blue-100 font-medium mt-0.5 uppercase tracking-wide"
                                id="desc">Buscando...</div>
                        </div>
                    </div>

                    <!-- Avatar do Usuário -->
                    <div class="flex-shrink-0">
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}"
                            class="w-14 h-14 md:w-16 md:h-16 rounded-full object-cover border-2 border-white/30 shadow-lg bg-white transition hover:scale-105">
                    </div>
                </div>

                <!-- Efeito visual de fundo (Blur) -->
                <div class="absolute -right-10 -top-10 h-40 w-40 bg-white/10 rounded-full blur-2xl pointer-events-none">
                </div>
                <div
                    class="absolute -left-10 -bottom-10 h-32 w-32 bg-brand-400/20 rounded-full blur-2xl pointer-events-none">
                </div>
            </div>

            <!-- AÇÕES RÁPIDAS -->
            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
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
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4 hover:shadow-md transition">
                    <div
                        class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase">{{ __('Total de Tarefas') }}</p>
                        <h3 class="text-2xl font-black text-brand-950">{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4 hover:shadow-md transition">
                    <div
                        class="h-12 w-12 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase">{{ __('Pendentes') }}</p>
                        <h3 class="text-2xl font-black text-brand-950">{{ $stats['pending'] ?? 0 }}</h3>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex items-center gap-4 hover:shadow-md transition">
                    <div
                        class="h-12 w-12 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase">{{ __('Concluídas') }}</p>
                        <h3 class="text-2xl font-black text-brand-950">{{ $stats['completed'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- GRID PRINCIPAL (URGÊNCIAS E CATEGORIAS/NOTAS) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- LADO ESQUERDO: Tarefas Urgentes -->
                <div
                    class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-brand-50 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-brand-50 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-extrabold text-lg text-brand-950 flex items-center gap-2">
                            <i class="fa-solid fa-fire text-rose-500"></i> Fogo no Parquinho (Urgentes)
                        </h3>
                        <a href="{{ route('todos.index') }}"
                            class="text-sm font-semibold text-brand-600 hover:text-brand-800 transition">Ver todas</a>
                    </div>

                    <div class="p-6 flex-1">
                        @if (!isset($urgentTodos) || $urgentTodos->isEmpty())
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
                                        <!-- Botão AJAX -->
                                        <button onclick="markAsDone({{ $todo->id }})" title="Marcar como Concluída"
                                            class="focus:outline-none group/btn mt-1 flex-shrink-0 cursor-pointer">
                                            <i
                                                class="fa-regular fa-circle text-gray-300 text-xl group-hover/btn:hidden"></i>
                                            <i
                                                class="fa-solid fa-circle-check text-emerald-500 text-xl hidden group-hover/btn:block transition transform group-hover/btn:scale-110"></i>
                                        </button>

                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('todos.show', $todo->id) }}"
                                                class="block font-bold text-brand-950 truncate hover:text-brand-600 transition">
                                                {{ $todo->title }}
                                            </a>

                                            <div class="flex items-center gap-3 mt-2 flex-wrap">
                                                @if ($todo->due_date)
                                                    <span
                                                        class="text-xs font-semibold px-2.5 py-1 rounded-md bg-rose-50 text-rose-600 flex items-center gap-1 border border-rose-100">
                                                        <i class="fa-regular fa-calendar"></i>
                                                        {{ \Carbon\Carbon::parse($todo->due_date)->isToday() ? 'Hoje às ' . \Carbon\Carbon::parse($todo->due_date)->format('H:i') : \Carbon\Carbon::parse($todo->due_date)->format('d/m/Y') }}
                                                    </span>
                                                @endif

                                                @if ($todo->category)
                                                    <span
                                                        class="text-xs font-semibold px-2.5 py-1 rounded-md text-white shadow-sm flex items-center gap-1"
                                                        style="background-color: {{ $todo->category->color }}">
                                                        <i
                                                            class="{{ $todo->category->icon ?? 'fa-solid fa-tag' }}"></i>
                                                        {{ $todo->category->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Avatares da Equipe (Múltiplos Responsáveis) -->
                                        @if (method_exists($todo, 'assignedUsers') && $todo->assignedUsers->count() > 0)
                                            <div class="flex -space-x-2 overflow-hidden flex-shrink-0 mt-1">
                                                @foreach ($todo->assignedUsers->take(3) as $assignee)
                                                    @php
                                                        $assigneeAvatar = !empty($assignee->avatar_path)
                                                            ? asset('storage/' . $assignee->avatar_path)
                                                            : 'https://api.dicebear.com/7.x/notionists/svg?seed=' .
                                                                urlencode($assignee->name) .
                                                                '&backgroundColor=e0e7ff,fef3c7,dbeafe,fce7f3';
                                                    @endphp
                                                    <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover bg-white"
                                                        src="{{ $assigneeAvatar }}" alt="{{ $assignee->name }}"
                                                        title="{{ $assignee->name }}">
                                                @endforeach
                                                @if ($todo->assignedUsers->count() > 3)
                                                    <div
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full ring-2 ring-white bg-gray-100 text-xs font-bold text-gray-600">
                                                        +{{ $todo->assignedUsers->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- LADO DIREITO: Distribuição de Projetos e Notas -->
                <div class="space-y-6">

                    <!-- Foco por Projetos -->
                    <div class="bg-white rounded-2xl shadow-sm border border-brand-50 p-6">
                        <h3 class="font-extrabold text-lg text-brand-950 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-blue-500"></i> Foco Atual
                        </h3>

                        @if (isset($categories) && $categories->count() > 0)
                            <div class="space-y-4">
                                @foreach ($categories as $category)
                                    <div>
                                        <div class="flex justify-between text-sm font-bold mb-1">
                                            <span class="text-brand-950 flex items-center gap-1"><i
                                                    class="{{ $category->icon ?? 'fa-solid fa-folder' }} text-gray-400 text-xs"></i>
                                                {{ $category->name }}</span>
                                            <span class="text-gray-500">{{ $category->todos_count }} <span
                                                    class="hidden sm:inline">pendentes</span></span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                            @php
                                                $pendingCount = $stats['pending'] ?? 0;
                                                $percent =
                                                    $pendingCount > 0
                                                        ? ($category->todos_count / $pendingCount) * 100
                                                        : 0;
                                            @endphp
                                            <div class="h-2.5 rounded-full transition-all duration-500"
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
                    <div class="bg-white rounded-2xl shadow-sm border border-brand-50 p-6">
                        <h3 class="font-extrabold text-lg text-brand-950 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-thumbtack text-amber-500"></i> Anotações Recentes
                        </h3>

                        @if (isset($recentNotes) && $recentNotes->count() > 0)
                            <div class="space-y-3">
                                @foreach ($recentNotes as $note)
                                    <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-lg shadow-sm hover:shadow transition cursor-pointer"
                                        onclick="window.location.href='/notes'">
                                        <h4 class="font-bold text-sm text-amber-900 truncate">{{ $note->title }}</h4>
                                        <p class="text-xs text-amber-900/80 leading-relaxed line-clamp-3">
                                            {{ Str::limit(strip_tags($note->content), 120) }}
                                        </p>
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
        document.addEventListener('DOMContentLoaded', () => {

            const lat = -8.0543;
            const lon = -34.8813;

            fetch(
                    `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,weather_code&timezone=America%2FRecife`)
                .then(res => res.json())
                .then(data => {
                    if (!data || !data.current) return;

                    const temp = Math.round(data.current.temperature_2m);
                    const code = data.current.weather_code;

                    let icon = '☀️';
                    let desc = 'Ensolarado';

                    if (code === 1 || code === 2) {
                        icon = '🌤️';
                        desc = 'Parc. Nublado';
                    }
                    if (code === 3) {
                        icon = '☁️';
                        desc = 'Nublado';
                    }
                    if (code >= 51 && code <= 67) {
                        icon = '🌧️';
                        desc = 'Chuva';
                    }
                    if (code >= 80 && code <= 82) {
                        icon = '🌦️';
                        desc = 'Pancadas';
                    }
                    if (code >= 95) {
                        icon = '⛈️';
                        desc = 'Tempestade';
                    }

                    document.getElementById('temp').innerText = `${temp}°C`;
                    document.getElementById('weather-icon').innerText = icon;
                    document.getElementById('desc').innerText = desc;
                })
                .catch(err => {
                    console.error('Erro ao buscar clima:', err);
                    document.getElementById('weather-widget').style.display = 'none';
                });
        });

        // === SCRIPT PARA CONCLUIR TAREFAS VIA AJAX ===
        function markAsDone(id) {
            fetch('/kanban/move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        id: id,
                        status: 'done'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
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

        // === SCRIPT PARA CRIAR NOTA RÁPIDA ===
        function createQuickNote() {
            fetch('/notes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: 'Nova Nota',
                        content: 'Clique para editar...',
                        color: '#fef08a'
                    })
                })
                .then(() => {
                    window.location.href = '/notes';
                })
                .catch(err => {
                    console.error('Erro ao criar nota:', err);
                    window.location.href = '/notes';
                });
        }
    </script>
</x-app-layout>
