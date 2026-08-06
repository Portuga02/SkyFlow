<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <p class="text-sm text-brand-600 mt-1">{{ __('Bem-vindo de volta! Aqui está o seu resumo.') }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @php
                $total = \App\Models\Todo::count();
                $completedCount = \App\Models\Todo::where('is_completed', true)->count();
                $pendingCount = $total - $completedCount;
            @endphp

            <!-- Boas vindas -->
            <div class="rounded-2xl bg-gradient-to-r from-brand-700 to-brand-500 p-6 sm:p-8 text-white shadow-card relative overflow-hidden">
                <div class="absolute -right-6 -top-6 h-32 w-32 rounded-full bg-white/10"></div>
                <div class="absolute right-16 bottom-0 h-20 w-20 rounded-full bg-white/10"></div>
                <div class="relative flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/15 text-2xl font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-lg font-bold">{{ __('Olá, :name!', ['name' => Auth::user()->name]) }}</p>
                        <p class="text-sm text-brand-100">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50">
                    <div class="h-12 w-12 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600">
                        <i class="fa-solid fa-list-ul text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950">{{ $total }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400">{{ __('Total de tarefas') }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50">
                    <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-hourglass-half text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950">{{ $pendingCount }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400">{{ __('Pendentes') }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-card p-5 flex items-center gap-4 border border-brand-50">
                    <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-brand-950">{{ $completedCount }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-400">{{ __('Concluídas') }}</p>
                    </div>
                </div>
            </div>

            <!-- Conta -->
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                <div class="px-6 py-5 border-b border-brand-50">
                    <h3 class="font-bold text-brand-950">{{ __('Minha Conta') }}</h3>
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-brand-500 uppercase bg-brand-50/60">
                            <tr>
                                <th scope="col" class="px-6 py-3">{{ __('ID') }}</th>
                                <th scope="col" class="px-6 py-3">{{ __('Nome') }}</th>
                                <th scope="col" class="px-6 py-3">{{ __('E-mail') }}</th>
                                <th scope="col" class="px-6 py-3 text-center">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b border-brand-50 hover:bg-brand-50/40">
                                <td class="px-6 py-4 font-medium text-brand-950">{{ Auth::user()->id }}</td>
                                <td class="px-6 py-4">{{ Auth::user()->name }}</td>
                                <td class="px-6 py-4">{{ Auth::user()->email }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('profile.edit') }}"
                                           class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition"
                                           title="Ver meu perfil">
                                            <i class="fa-solid fa-circle-user"></i>
                                        </a>

                                        <a href="{{ route('todos.index') }}"
                                           class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 hover:bg-brand-100 transition"
                                           title="Ir para suas listas de tarefas">
                                            <i class="fa-solid fa-list-check"></i>
                                        </a>

                                        <form action="{{ route('profile.destroy') }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition"
                                                    title="Excluir este usuário"
                                                    onclick="return confirm('Tem certeza que deseja excluir sua conta?');">
                                                <i class="fa-solid fa-trash"></i>
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
    </div>
</x-app-layout>
