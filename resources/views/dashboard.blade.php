<x-app-layout>
    <x-slot name="header">
        <div class="space-y-1">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-950">{{ __('Dashboard') }}</h2>
            <p class="text-sm text-brand-600">{{ __('Bem-vindo de volta! Aqui está o seu resumo.') }}</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-brand-600 to-brand-700 rounded-2xl shadow-card p-6 sm:p-8 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="text-3xl sm:text-4xl font-bold">{{ __('Olá') }}, {{ Auth::user()->name }}!</div>
                    <p class="text-brand-100 mt-2 text-sm sm:text-base">{{ Auth::user()->email }}</p>
                </div>
                @if (Auth::user()->avatar_path)
                    <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="{{ Auth::user()->name }}"
                        class="h-20 w-20 rounded-full object-cover border-4 border-brand-500">
                @else
                    <div class="h-20 w-20 rounded-full bg-brand-500 text-white flex items-center justify-center text-3xl font-bold shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <i class="fa-solid fa-list-check text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-brand-950">{{ Auth::user()->todos()->count() }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Total de Tarefas') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600 shrink-0">
                        <i class="fa-solid fa-hourglass-half text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-brand-950">{{ Auth::user()->todos()->where('is_completed', false)->count() }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Pendentes') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-brand-950">{{ Auth::user()->todos()->where('is_completed', true)->count() }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ __('Concluídas') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Account Table -->
        <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
            <div class="p-6 border-b border-brand-50">
                <h3 class="font-bold text-brand-950">{{ __('Minha Conta') }}</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-brand-500 uppercase bg-brand-50/60 hidden sm:table-header-group">
                        <tr>
                            <th class="px-6 py-3">{{ __('ID') }}</th>
                            <th class="px-6 py-3">{{ __('Nome') }}</th>
                            <th class="px-6 py-3">{{ __('E-mail') }}</th>
                            <th class="px-6 py-3">{{ __('Ações') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b border-brand-50 hover:bg-brand-50/40 block sm:table-row cursor-pointer sm:cursor-default p-4 sm:p-0"
                            onclick="if (window.innerWidth < 640) window.location.href='{{ route('profile.edit') }}'">
                            <td class="px-6 py-4 font-medium text-brand-950 hidden sm:table-cell">{{ Auth::user()->id }}</td>
                            <td class="px-6 py-4 block sm:table-cell font-semibold sm:font-normal">
                                {{ Auth::user()->name }}
                                <span class="block text-xs text-gray-400 sm:hidden mt-1">{{ Auth::user()->email }}</span>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">{{ Auth::user()->email }}</td>
                            <td class="px-6 py-4 block sm:table-cell" onclick="event.stopPropagation()">
                                <div class="flex gap-2">
                                    <a href="{{ route('profile.edit') }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <a href="{{ route('todos.index') }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition">
                                        <i class="fa-solid fa-list text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('profile.destroy') }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition"
                                            onclick="return confirm('Tem certeza?')">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>