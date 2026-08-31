<section>
    <header class="flex items-center gap-4">
        <div class="h-16 w-16 rounded-full bg-brand-100 border-2 border-brand-200 flex items-center justify-center text-brand-600 text-2xl font-bold overflow-hidden shadow-sm relative group cursor-pointer">
            <!-- Se houver imagem de perfil no futuro, coloque a tag <img> aqui -->
            <span>{{ substr($user->name, 0, 1) }}</span>
            
            <!-- Overlay de Hover para trocar foto (Visual) -->
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fa-solid fa-camera text-white text-sm"></i>
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                {{ __('Informações Pessoais') }} <i class="fa-solid fa-circle-check text-brand-500 text-sm"></i>
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                {{ __('Atualize sua foto, nome, email e como você é visto no sistema.') }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6 max-w-2xl">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Nome Completo') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-user text-slate-400"></i>
                    </div>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition text-slate-800 font-medium" />
                </div>
                <x-input-error class="mt-2 text-rose-500 text-sm font-medium" :messages="$errors->get('name')" />
            </div>

            <!-- Título / Cargo (Exemplo visual para o futuro) -->
            <div>
                <label for="role" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Cargo / Título (Opcional)') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-briefcase text-slate-400"></i>
                    </div>
                    <input id="role" name="role" type="text" placeholder="Ex: Desenvolvedor Senior" disabled
                        class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 cursor-not-allowed shadow-sm text-slate-500" title="Recurso em breve!" />
                </div>
            </div>
        </div>

        <!-- E-mail -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Endereço de E-mail') }}</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-regular fa-envelope text-slate-400"></i>
                </div>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                    class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition text-slate-800 font-medium" />
            </div>
            <x-input-error class="mt-2 text-rose-500 text-sm font-medium" :messages="$errors->get('email')" />

            <!-- Alerta de Email não verificado bonitão -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-amber-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-amber-800">
                            {{ __('Seu endereço de e-mail ainda não foi verificado.') }}
                        </p>
                        <button form="send-verification" class="mt-1 text-sm font-bold text-amber-600 hover:text-amber-700 underline transition">
                            {{ __('Clique aqui para reenviar o link de verificação.') }}
                        </button>
                        
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-xs font-bold text-emerald-600 flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> {{ __('Um novo link foi enviado para seu e-mail!') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Bio Curta (Exemplo visual para o futuro) -->
        <div>
            <label for="bio" class="block text-sm font-bold text-slate-700 mb-2">{{ __('Sobre você') }}</label>
            <textarea id="bio" name="bio" rows="3" disabled placeholder="Escreva uma breve descrição..."
                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 cursor-not-allowed shadow-sm text-slate-500 resize-none" title="Recurso em breve!"></textarea>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl shadow-md transition-all active:scale-95">
                <i class="fa-solid fa-floppy-disk"></i> {{ __('Salvar Perfil') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-bold text-emerald-600 flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                    <i class="fa-solid fa-circle-check"></i> {{ __('Perfil atualizado!') }}
                </p>
            @endif
        </div>
    </form>
</section>