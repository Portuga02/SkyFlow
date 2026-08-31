<section>
    <header>
        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-brand-500"></i> {{ __('Atualizar Senha') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ __('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6 max-w-xl">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Senha Atual') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-500 text-sm font-medium" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Nova Senha') }}</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-500 text-sm font-medium" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Confirmar Nova Senha') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-500 text-sm font-medium" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl shadow-md transition-all active:scale-95">
                <i class="fa-solid fa-floppy-disk"></i> {{ __('Salvar Alterações') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-bold text-emerald-600 flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                    <i class="fa-solid fa-circle-check"></i> {{ __('Senha salva!') }}
                </p>
            @endif
        </div>
    </form>
</section>