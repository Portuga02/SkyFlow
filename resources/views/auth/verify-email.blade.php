<x-guest-layout>
    <div class="bg-[#111625]/90 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 shadow-2xl shadow-black/50">

        <!-- Header com Ícone e Glow -->
        <div class="flex flex-col items-center text-center mb-6">
            <div class="relative mb-3 flex items-center justify-center">
                <div class="absolute inset-0 bg-brand-500/30 rounded-2xl blur-md"></div>
                <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-sky-400 flex items-center justify-center text-white text-2xl shadow-lg">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">{{ __('Verifique seu E-mail') }}</h1>
            <p class="text-xs text-slate-400 mt-2 font-medium leading-relaxed">
                {{ __('Obrigado por se inscrever! Antes de começar, verifique seu endereço de e-mail clicando no link que acabamos de enviar. Se você não recebeu, podemos enviar outro.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3 text-center text-xs font-medium text-emerald-400 shadow-sm">
                <i class="fa-solid fa-circle-check mr-1"></i>
                {{ __('Um novo link de verificação foi enviado para o endereço de e-mail fornecido.') }}
            </div>
        @endif

        <div class="space-y-4 pt-2">
            <!-- Botão de Reenvio -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-brand-600 to-sky-500 hover:from-brand-500 hover:to-sky-400 text-white font-bold text-sm shadow-lg shadow-brand-500/25 transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    {{ __('REENVIAR E-MAIL DE VERIFICAÇÃO') }}
                </button>
            </form>

            <!-- Botão de Logout -->
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit"
                    class="text-xs text-slate-400 hover:text-rose-400 transition font-semibold">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i>
                    {{ __('Sair da conta') }}
                </button>
            </form>
        </div>
    </div>

    <p class="text-center text-[11px] text-slate-600 mt-6">
        &copy; {{ date('Y') }} SkyFlow. Todos os direitos reservados.
    </p>
</x-guest-layout>