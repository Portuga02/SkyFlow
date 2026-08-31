<x-guest-layout>
    <div class="bg-[#111625]/90 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 shadow-2xl shadow-black/50">

        <!-- Logo Circular com Glow -->
        <div class="flex flex-col items-center text-center mb-7">
            <div class="relative mb-3 flex items-center justify-center">
                <div class="absolute inset-0 bg-brand-500/30 rounded-2xl blur-md"></div>
                <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-sky-400 flex items-center justify-center text-white text-2xl shadow-lg">
                    <i class="fa-solid fa-cloud-bolt"></i>
                </div>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">SkyFlow</h1>
            <p class="text-xs text-slate-400 mt-1 font-medium">{{ __('Conecte-se. Organize. Fluidez total.') }}</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- E-mail -->
            <div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="seu@email.com" 
                        class="w-full bg-[#181f33]/80 border border-slate-700/60 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <!-- Senha -->
            <div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••" 
                        class="w-full bg-[#181f33]/80 border border-slate-700/60 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <!-- Lembrar-me & Esqueceu Senha -->
            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox"
                        class="rounded-md bg-[#181f33] border-slate-700 text-brand-600 focus:ring-0 focus:ring-offset-0 cursor-pointer h-4 w-4"
                        name="remember">
                    <span class="ms-2 text-xs text-slate-400">{{ __('Lembrar de mim') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-xs text-slate-400 hover:text-brand-400 transition" href="{{ route('password.request') }}">
                        {{ __('Esqueceu a senha?') }}
                    </a>
                @endif
            </div>

            <!-- Botão Principal -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-600 to-sky-500 hover:from-brand-500 hover:to-sky-400 text-white font-bold text-sm shadow-lg shadow-brand-500/25 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                    {{ __('ENTRAR') }}
                </button>
            </div>

            <!-- Link de Registro -->
            @if (Route::has('register'))
                <div class="pt-1">
                    <a href="{{ route('register') }}"
                        class="w-full py-3 px-4 rounded-xl border border-slate-700/80 hover:bg-slate-800/50 text-slate-300 font-semibold text-xs transition text-center block">
                        {{ __('CRIAR CONTA') }}
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Rodapé -->
    <p class="text-center text-[11px] text-slate-600 mt-6">
        &copy; {{ date('Y') }} SkyFlow. Todos os direitos reservados.
    </p>
</x-guest-layout>