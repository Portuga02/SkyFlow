<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-extrabold text-brand-950">{{ __('Bem-vindo de volta') }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ __('Entre para continuar organizando seu fluxo.') }}</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('E-mail')" class="font-semibold text-slate-700" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-xl border-slate-200 focus:border-brand-500 focus:ring-brand-500 shadow-xs"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="seu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Senha -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Senha')" class="font-semibold text-slate-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-brand-600 hover:text-brand-700 font-medium transition"
                        href="{{ route('password.request') }}">
                        {{ __('Esqueceu?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password"
                class="block mt-1 w-full rounded-xl border-slate-200 focus:border-brand-500 focus:ring-brand-500 shadow-xs"
                type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Lembrar-me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded-md border-slate-300 text-brand-600 shadow-xs focus:ring-brand-500 cursor-pointer"
                    name="remember">
                <span class="ms-2 text-sm text-slate-600 select-none">{{ __('Lembrar de mim') }}</span>
            </label>
        </div>

        <!-- Botão Entrar -->
        <div class="pt-2">
            <button type="submit"
                class="w-full py-3 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm shadow-md hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                {{ __('Entrar na Conta') }}
            </button>
        </div>

        <!-- Link para Registro -->
        @if (Route::has('register'))
            <div class="text-center pt-4 border-t border-slate-100 mt-6">
                <p class="text-sm text-slate-500">
                    {{ __('Não tem uma conta?') }}
                    <a href="{{ route('register') }}"
                        class="font-bold text-brand-600 hover:text-brand-700 ml-1 transition">
                        {{ __('Cadastre-se gratuitamente') }}
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
