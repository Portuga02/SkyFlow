<x-app-layout>
    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card de Cabeçalho -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 dark:border-slate-700 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-brand-50 dark:bg-slate-700 text-brand-600 dark:text-brand-400 flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-extrabold text-brand-950 dark:text-slate-100 tracking-tight">
                            {{ __('Gestão de Equipe') }}
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('Gerencie os membros do seu workspace e defina permissões.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabela de Membros -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700/80">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Membro') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Cargo') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Data de Entrada') }}</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">{{ __('Ações') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-700/50">
                            
                            <!-- LOOP DE MEMBROS -->
                            <!-- Nota: Se a sua variável do controller não for $users, altere aqui -->
                            @forelse ($users ?? [auth()->user()] as $member)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($member->avatar_path)
                                                <img src="{{ $member->avatar_path }}" alt="{{ $member->name }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-slate-800 shadow-sm">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-brand-600 dark:bg-brand-500 text-white flex items-center justify-center text-sm font-bold shadow-sm ring-2 ring-white dark:ring-slate-800">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-sm font-bold text-brand-950 dark:text-slate-100">{{ $member->name }}</span>
                                                    @if ($member->id === auth()->id())
                                                        <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400">({{ __('Você') }})</span>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $member->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 shadow-xs">
                                            {{ $member->role === 'admin' ? __('Admin') : __('Membro') }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">
                                        {{ $member->created_at ? $member->created_at->format('d/m/Y') : '31/08/2026' }}
                                    </td>
                                    
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        @if ($member->id !== auth()->id())
                                            <button class="text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition p-2 rounded-lg hover:bg-brand-50 dark:hover:bg-slate-700">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                        @else
                                            <span class="text-slate-300 dark:text-slate-600">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                        {{ __('Nenhum membro encontrado.') }}
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>