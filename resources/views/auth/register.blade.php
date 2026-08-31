<x-guest-layout>
    <div class="bg-[#111625]/90 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 shadow-2xl shadow-black/50">

        <!-- Logo Circular com Glow -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="relative mb-3 flex items-center justify-center">
                <div class="absolute inset-0 bg-brand-500/30 rounded-2xl blur-md"></div>
                <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-sky-400 flex items-center justify-center text-white text-2xl shadow-lg">
                    <i class="fa-solid fa-cloud-bolt"></i>
                </div>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">{{ __('Criar Conta') }}</h1>
            <p class="text-xs text-slate-400 mt-1 font-medium">{{ __('Comece a organizar suas tarefas em minutos.') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
            @csrf

            <!-- Nome -->
            <div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    <input id="name" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Seu nome completo" 
                        class="w-full bg-[#181f33]/80 border border-slate-700/60 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

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
                        autocomplete="username"
                        placeholder="seu@email.com" 
                        class="w-full bg-[#181f33]/80 border border-slate-700/60 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
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
                        autocomplete="new-password" 
                        placeholder="Senha" 
                        class="w-full bg-[#181f33]/80 border border-slate-700/60 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirmar Senha -->
            <div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <input id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password" 
                        placeholder="Confirmar senha" 
                        class="w-full bg-[#181f33]/80 border border-slate-700/60 rounded-xl pl-10 pr-4 py-3 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all shadow-inner" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Botão Cadastrar -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-600 to-sky-500 hover:from-brand-500 hover:to-sky-400 text-white font-bold text-sm shadow-lg shadow-brand-500/25 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                    {{ __('REGISTRAR') }}
                </button>
            </div>

            <!-- Link para Login -->
            <div class="pt-1 text-center">
                <a href="{{ route('login') }}"
                    class="text-xs text-slate-400 hover:text-brand-400 transition">
                    {{ __('Já tem uma conta? Entrar') }}
                </a>
            </div>
        </form>
    </div>

    <p class="text-center text-[11px] text-slate-600 mt-6">
        &copy; {{ date('Y') }} SkyFlow. Todos os direitos reservados.
    </p>
</x-guest-layout>