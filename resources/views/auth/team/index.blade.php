<x-app-layout>
    <!-- Modal AlpineJS State -->
    <div x-data="{ inviteModalOpen: false }" class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('alert-success'))
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                    <span class="text-sm font-medium">{{ session('alert-success') }}</span>
                    <p class="text-xs text-emerald-600 ml-2">(Anote a senha antes de fechar!)</p>
                </div>
            @endif

            <!-- HEADER -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-brand-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-brand-950 flex items-center gap-2">
                        <i class="fa-solid fa-users-gear text-blue-500"></i> Gestão de Equipe
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Gerencie os membros do seu workspace e defina permissões.</p>
                </div>
                
                @if(Auth::user()->role === 'admin')
                    <button @click="inviteModalOpen = true" class="flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-soft transition">
                        <i class="fa-solid fa-user-plus"></i> Convidar Membro
                    </button>
                @endif
            </div>

            <!-- TABELA DE USUÁRIOS -->
            <div class="bg-white rounded-2xl shadow-sm border border-brand-50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-slate-50/50 border-b border-brand-50 text-gray-500 uppercase text-xs font-extrabold">
                            <tr>
                                <th class="px-6 py-4">Membro</th>
                                <th class="px-6 py-4">Cargo</th>
                                <th class="px-6 py-4">Data de Entrada</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-50">
                            @foreach($members as $member)
                                @php
                                    $avatar = !empty($member->avatar_path) 
                                        ? asset('storage/' . $member->avatar_path) 
                                        : 'https://api.dicebear.com/7.x/notionists/svg?seed=' . urlencode($member->name) . '&backgroundColor=e0e7ff,fef3c7,dbeafe,fce7f3';
                                @endphp
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $avatar }}" class="w-10 h-10 rounded-full object-cover border border-brand-100 bg-white">
                                            <div>
                                                <p class="font-bold text-brand-950">{{ $member->name }} 
                                                    @if($member->id === Auth::id()) <span class="text-xs text-brand-500 font-semibold">(Você)</span> @endif
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($member->role === 'admin')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-purple-50 text-purple-700 text-xs font-bold border border-purple-100">
                                                <i class="fa-solid fa-crown text-[10px]"></i> Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 text-xs font-bold border border-gray-200">
                                                Membro
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium">
                                        {{ $member->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(Auth::user()->role === 'admin' && $member->id !== Auth::id())
                                            <button class="text-gray-400 hover:text-rose-500 transition" title="Remover membro">
                                                <i class="fa-solid fa-trash-can text-lg"></i>
                                            </button>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- MODAL CONVIDAR MEMBRO -->
        <div x-show="inviteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Fundo escuro -->
                <div x-show="inviteModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Painel do Modal -->
                <div x-show="inviteModalOpen" 
                     @click.outside="inviteModalOpen = false"
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block w-full max-w-lg overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle">
                    
                    <div class="px-6 py-5 border-b border-brand-50 flex justify-between items-center bg-slate-50">
                        <h3 class="text-lg font-extrabold text-brand-950" id="modal-title">Convidar Novo Membro</h3>
                        <button @click="inviteModalOpen = false" class="text-gray-400 hover:text-gray-600 text-xl"><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <form action="{{ route('team.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nome do Membro</label>
                            <input type="text" name="name" required class="block w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">E-mail Profissional</label>
                            <input type="email" name="email" required class="block w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Permissão</label>
                            <select name="role" required class="block w-full border-gray-300 rounded-lg focus:ring-brand-500 focus:border-brand-500 text-sm">
                                <option value="member">Membro Padrão (Pode criar e ver tarefas da equipe)</option>
                                <option value="admin">Administrador (Pode gerenciar a equipe toda)</option>
                            </select>
                        </div>

                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="inviteModalOpen = false" class="px-4 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-lg transition">Cancelar</button>
                            <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-lg transition shadow-md">
                                Adicionar Membro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>