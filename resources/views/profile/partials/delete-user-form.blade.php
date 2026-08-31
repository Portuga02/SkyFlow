<section class="space-y-6">
    <header>
        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> {{ __('Deletar Conta') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500 max-w-2xl">
            {{ __('Assim que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir sua conta, faça o download de quaisquer dados ou informações que você deseja reter.') }}
        </p>
    </header>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 px-6 py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 text-sm font-bold rounded-xl shadow-sm transition-all active:scale-95">
        <i class="fa-solid fa-trash-can"></i> {{ __('Excluir Minha Conta') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 relative overflow-hidden">
            @csrf
            @method('delete')
            
            <!-- Faixa vermelha de alerta no topo do modal -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-rose-500"></div>

            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2 mt-2">
                <i class="fa-solid fa-skull text-rose-500"></i> {{ __('Você tem certeza absoluta?') }}
            </h2>

            <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                {{ __('Esta ação é irreversível. Todas as suas tarefas do Kanban, anotações e configurações serão destruídas permanentemente. Digite sua senha atual para confirmar a exclusão da conta.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Sua Senha') }}</label>
                <input id="password" name="password" type="password" placeholder="{{ __('Digite sua senha para confirmar') }}"
                    class="w-full sm:w-3/4 px-4 py-3 rounded-xl border-slate-200 focus:border-rose-500 focus:ring focus:ring-rose-500/20 shadow-sm transition" />
                
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-500 text-sm font-medium" />
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition shadow-sm">
                    {{ __('Cancelar') }}
                </button>

                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl shadow-md transition-all active:scale-95">
                    <i class="fa-solid fa-bomb"></i> {{ __('Sim, Deletar Conta') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>