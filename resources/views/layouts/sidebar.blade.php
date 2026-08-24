<!-- Sidebar -->
<aside
    class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-brand-100 flex flex-col z-40
    transform transition-transform duration-300 -translate-x-full md:translate-x-0"
    :class="mobileOpen ? 'translate-x-0' : ''">

    <!-- Header (SkyFlow Linkável + Animação do Raio) -->
    <div class="flex items-center justify-between p-4 border-b border-brand-50">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group transition">
            <div
                class="h-8 w-8 rounded-lg bg-brand-600 text-white flex items-center justify-center text-sm font-bold shadow-sm transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6">
                <i class="fa-solid fa-cloud-bolt transition-transform duration-300 group-hover:scale-110"></i>
            </div>
            <span class="font-bold text-brand-950 group-hover:text-brand-600 transition-colors">SkyFlow</span>
        </a>
        <button @click="mobileOpen = false"
            class="md:hidden text-gray-400 hover:text-gray-600 transition hover:rotate-90">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Nav links -->
    <nav class="flex-1 overflow-y-auto scrollbar-thin px-3 py-5 space-y-6">

        <!-- Search Button (Lupa com leve zoom) -->
        <button @click="$dispatch('search-open'); mobileOpen = false"
            class="group w-full flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-semibold bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
            <i
                class="fa-solid fa-magnifying-glass w-4 text-center shrink-0 transition-transform duration-200 group-hover:scale-125 group-hover:rotate-12"></i>
            <span class="flex-1 text-left text-xs sm:text-sm">{{ __('Buscar...') }}</span>
            <kbd class="text-xs px-1.5 py-0.5 rounded bg-white border border-brand-200 hidden sm:inline">⌘K</kbd>
        </button>

        <div class="space-y-1">
            <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300">{{ __('Principal') }}</p>

            <!-- Dashboard (Azul / Cor do Tema) -->
            <a href="{{ route('dashboard') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 transition-all duration-200 group-hover:scale-110 group-hover:bg-blue-100 shadow-xs">
                    <i class="fa-solid fa-gauge-high text-sm"></i>
                </span>
                <span class="truncate">{{ __('Painel de controle') }}</span>
            </a>

            <!-- Tarefas (Índigo) -->
            <a href="{{ route('todos.index') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('todos.*') && !request()->routeIs('todos.create') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 transition-all duration-200 group-hover:scale-110 group-hover:bg-indigo-100 shadow-xs">
                    <i class="fa-solid fa-list-check text-sm"></i>
                </span>
                <span class="truncate">{{ __('Tarefas') }}</span>
            </a>

            <!-- Nova Tarefa (Esmeralda / Verde) -->
            <a href="{{ route('todos.create') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('todos.create') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 transition-all duration-200 group-hover:scale-110 group-hover:rotate-90 group-hover:bg-emerald-100 shadow-xs">
                    <i class="fa-solid fa-circle-plus text-sm"></i>
                </span>
                <span class="truncate">{{ __('Nova Tarefa') }}</span>
            </a>

            <!-- Kanban (Âmbar / Laranja) -->
            <a href="{{ route('kanban.index') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('kanban.*') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-500 transition-all duration-200 group-hover:scale-110 group-hover:bg-amber-100 shadow-xs">
                    <i class="fa-solid fa-table-columns text-sm"></i>
                </span>
                <span class="truncate">{{ __('Kanban') }}</span>
            </a>

            <!-- Minha Equipe (Roxo) -->
            <a href="{{ route('team.index') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('team.*') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 text-purple-600 transition-all duration-200 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-purple-100 shadow-xs">
                    <i class="fa-solid fa-users-gear text-sm"></i>
                </span>
                <span class="truncate">{{ __('Minha Equipe') }}</span>
            </a>
        </div>

        <div class="space-y-1 pt-2">
            <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-brand-300">{{ __('Organização') }}
            </p>

            <!-- Calendário (Ciano / Sky) -->
            <a href="{{ route('calendar.index') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('calendar.*') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-sky-50 text-sky-500 transition-all duration-200 group-hover:scale-110 group-hover:-translate-y-0.5 group-hover:bg-sky-100 shadow-xs">
                    <i class="fa-solid fa-calendar-days text-sm"></i>
                </span>
                <span class="truncate">{{ __('Calendário') }}</span>
            </a>

            <!-- Categorias (Fúcsia / Rosa) -->
            <a href="{{ route('categories.index') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('categories.*') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-fuchsia-50 text-fuchsia-600 transition-all duration-200 group-hover:scale-110 group-hover:rotate-6 group-hover:bg-fuchsia-100 shadow-xs">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </span>
                <span class="truncate">{{ __('Categorias') }}</span>
            </a>

            <!-- Bloco de Notas (Amarelo Quente / Warm Amber) -->
            <a href="{{ route('notes.index') }}"
                class="group flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                    {{ request()->routeIs('notes.*') ? 'bg-brand-50 text-brand-600 font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-50 hover:text-brand-600' }}"
                @click="mobileOpen = false">
                <span
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 transition-all duration-200 group-hover:scale-110 group-hover:-rotate-12 group-hover:bg-yellow-100 shadow-xs">
                    <i class="fa-solid fa-note-sticky text-sm"></i>
                </span>
                <span class="truncate">{{ __('Bloco de Notas') }}</span>
            </a>
        </div>
    </nav>

<div class="border-t border-brand-50 p-4" x-data="{ open: false }">
    @php
        $user = Auth::user();
        $fallbackAvatar = 'https://api.dicebear.com/7.x/notionists/svg?seed=' . urlencode($user->name) . '&backgroundColor=e0e7ff,fef3c7,dbeafe,fce7f3';
        $userAvatar = !empty($user->avatar_path) ? $user->avatar_path : $fallbackAvatar;
    @endphp

    <button @click="open = !open"
        class="group w-full flex items-center gap-3 text-sm font-semibold text-brand-950 hover:text-brand-600 transition">

        <div class="relative shrink-0">
            <img src="{{ $userAvatar }}" 
                 alt="{{ $user->name }}"
                 class="h-8 w-8 rounded-full object-cover shadow-xs ring-1 ring-brand-100 bg-white"
                 onerror="this.src='{{ $fallbackAvatar }}'">
        </div>

        <span class="flex-1 text-left truncate text-xs sm:text-sm font-bold text-brand-950">{{ $user->name }}</span>
        <i class="fa-solid fa-chevron-down text-xs transition shrink-0 text-slate-400" :class="open && 'rotate-180'"></i>
    </button>

    <div x-show="open" @click.outside="open = false" class="mt-2 space-y-1" x-transition x-cloak>
        <a href="{{ route('profile.edit') }}"
            class="block px-3 py-2 text-sm text-gray-600 hover:bg-brand-50 hover:text-brand-600 rounded-lg transition"
            @click="mobileOpen = false">
            {{ __('Perfil') }}
        </a>
        <form method="POST" action="{{ route('logout') }}" class="block">
            @csrf
            <button type="submit"
                class="w-full text-left px-3 py-2 text-sm text-gray-600 hover:bg-rose-50 hover:text-rose-600 rounded-lg transition">
                {{ __('Sair') }}
            </button>
        </form>
    </div>
</div>
</aside>
