<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-800 dark:text-slate-100 flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-user-astronaut text-brand-500"></i> {{ __('Meu Perfil') }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors">
                    {{ __('Gerencie seus dados pessoais, segurança e aparência do sistema.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6" x-data="{ tab: 'geral' }">

        <!-- Card de Resumo do Usuário & Workspace -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden transition-colors duration-300">
            <!-- Efeito visual de fundo -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/10 dark:bg-brand-500/5 rounded-full blur-3xl -z-0 opacity-60 translate-x-1/2 -translate-y-1/2"></div>

            <div class="flex items-center gap-5 relative z-10">
                <div class="relative group">
                    @if (Auth::user()->avatar_path)
                        <img src="{{ Auth::user()->avatar_path }}" alt="{{ Auth::user()->name }}"
                            class="h-20 w-20 rounded-2xl object-cover shadow-md ring-4 ring-white dark:ring-slate-700"
                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                        <!-- Fallback oculto -->
                        <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center text-3xl font-extrabold shadow-md ring-4 ring-white dark:ring-slate-700 hidden">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center text-3xl font-extrabold shadow-md ring-4 ring-white dark:ring-slate-700">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 dark:text-slate-100 tracking-tight">{{ Auth::user()->name }}</h3>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mt-0.5">
                        <i class="fa-regular fa-envelope"></i> {{ Auth::user()->email }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:items-end gap-3 w-full sm:w-auto relative z-10">
                <div class="px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-600 text-sm font-medium text-slate-700 dark:text-slate-300 shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-building-user text-brand-500"></i>
                    Workspace: <strong class="text-slate-900 dark:text-slate-100">{{ Auth::user()->team->name ?? 'Pessoal' }}</strong>
                </div>

                @if (Auth::user()->role === 'admin')
                    <span class="px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400 text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <i class="fa-solid fa-crown text-amber-500"></i> Administrador
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400 text-xs font-bold flex items-center gap-1.5 shadow-sm transition-colors">
                        <i class="fa-solid fa-user-shield text-blue-500"></i> Membro
                    </span>
                @endif
            </div>
        </div>

        <!-- Navegação por Abas -->
        <div class="flex p-1.5 bg-slate-100 dark:bg-slate-900/50 rounded-2xl gap-1 max-w-fit shadow-inner transition-colors border border-slate-200 dark:border-slate-800">
            <button @click="tab = 'geral'"
                :class="tab === 'geral' ? 'bg-white dark:bg-slate-800 text-brand-700 dark:text-brand-400 shadow-sm font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium hover:bg-slate-200/50 dark:hover:bg-slate-800/50'"
                class="px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-all">
                <i class="fa-solid fa-user-gear"></i> {{ __('Geral & Aparência') }}
            </button>
            <button @click="tab = 'seguranca'"
                :class="tab === 'seguranca' ? 'bg-white dark:bg-slate-800 text-brand-700 dark:text-brand-400 shadow-sm font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 font-medium hover:bg-slate-200/50 dark:hover:bg-slate-800/50'"
                class="px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-all">
                <i class="fa-solid fa-shield-halved"></i> {{ __('Segurança & Senha') }}
            </button>
            <button @click="tab = 'perigo'"
                :class="tab === 'perigo' ? 'bg-white dark:bg-slate-800 text-rose-600 dark:text-rose-400 shadow-sm font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 font-medium hover:bg-rose-50 dark:hover:bg-rose-900/30'"
                class="px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 transition-all">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ __('Zona de Perigo') }}
            </button>
        </div>

        <!-- CONTEÚDO DAS ABAS -->
        <div class="relative">
            
            <!-- ABA 1: GERAL & APARÊNCIA -->
            <div x-show="tab === 'geral'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                    <h4 class="text-lg font-extrabold text-slate-900 dark:text-slate-100 mb-1">{{ __('Informações Pessoais') }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">{{ __('Atualize sua foto de exibição, nome e endereço de e-mail.') }}</p>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('patch')

                        <!-- Upload Avatar -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">{{ __('Foto de Perfil') }}</label>
                            <div class="flex items-center gap-5 p-4 rounded-2xl border border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 transition-colors">
                                <div id="avatarPreviewContainer" class="relative shrink-0">
                                    @if (Auth::user()->avatar_path)
                                        <img id="avatarPreview" src="{{ Auth::user()->avatar_path }}" class="h-16 w-16 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-800 shadow-sm">
                                    @else
                                        <div id="avatarFallback" class="h-16 w-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-2xl font-bold shadow-sm ring-4 ring-white dark:ring-slate-800">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <img id="avatarPreview" class="h-16 w-16 rounded-2xl object-cover ring-4 ring-white dark:ring-slate-800 shadow-sm hidden">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                                    <label for="avatarInput" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 hover:border-brand-300 dark:hover:border-brand-500 hover:text-brand-600 dark:hover:text-brand-400 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold cursor-pointer transition shadow-sm">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> {{ __('Fazer Upload') }}
                                    </label>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Formatos suportados: PNG, JPG ou WEBP (Max 2MB).</p>
                                </div>
                            </div>
                        </div>

                        <!-- Dados Pessoais -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Nome Completo') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fa-regular fa-user text-slate-400 dark:text-slate-500"></i>
                                    </div>
                                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                                        class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-900 font-medium" />
                                </div>
                                <x-input-error class="mt-2 text-rose-500 text-sm font-medium" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('E-mail') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fa-regular fa-envelope text-slate-400 dark:text-slate-500"></i>
                                    </div>
                                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                        class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition text-slate-900 dark:text-slate-100 bg-white dark:bg-slate-900 font-medium" />
                                </div>
                                <x-input-error class="mt-2 text-rose-500 text-sm font-medium" :messages="$errors->get('email')" />
                            </div>
                        </div>

                        <!-- Tema -->
                        <div class="pt-6 border-t border-slate-100 dark:border-slate-700 transition-colors">
                            <label class="block text-sm font-bold text-slate-900 dark:text-slate-100 mb-2"><i class="fa-solid fa-palette text-brand-500 mr-1"></i> {{ __('Cor de Destaque (Tema)') }}</label>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ __('Personalize a cor principal dos botões e links do seu painel.') }}</p>

                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 transition-colors">
                                <div class="relative w-12 h-12 rounded-xl shadow-sm border-2 border-white dark:border-slate-800 overflow-hidden shrink-0 cursor-pointer hover:scale-105 transition" title="Cor Customizada">
                                    <input type="color" id="theme_color_picker" name="theme_color" value="{{ old('theme_color', $user->theme_color ?? '#0071c4') }}"
                                        class="absolute -top-2 -left-2 w-16 h-16 cursor-pointer">
                                </div>
                                <div class="w-px h-8 bg-slate-300 dark:bg-slate-600 mx-1"></div>
                                <div class="flex flex-wrap gap-2">
                                    @php $themeColors = ['#0ea5e9', '#8b5cf6', '#10b981', '#f59e0b', '#f43f5e', '#334155']; @endphp
                                    @foreach($themeColors as $color)
                                        <button type="button" onclick="selectColor('{{ $color }}')"
                                            class="h-10 w-10 rounded-xl shadow-sm border-2 border-white dark:border-slate-800 hover:scale-110 hover:ring-2 hover:ring-slate-300 dark:hover:ring-slate-500 transition"
                                            style="background-color: {{ $color }};"></button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Botão Salvar Geral -->
                        <div class="flex items-center justify-end gap-4 pt-4">
                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                    class="text-sm font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i> {{ __('Perfil atualizado!') }}
                                </p>
                            @endif
                            <button type="submit" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> {{ __('Salvar Alterações') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ABA 2: SEGURANÇA & SENHA -->
            <div x-show="tab === 'seguranca'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
                    <h4 class="text-lg font-extrabold text-slate-900 dark:text-slate-100 mb-1">{{ __('Atualizar Senha') }}</h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8">{{ __('Garanta que sua conta utilize uma senha forte com letras e números para se manter segura.') }}</p>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl">
                        @csrf
                        @method('put')

                        <div>
                            <label for="update_password_current_password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Senha Atual') }}</label>
                            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-500 text-sm font-medium" />
                        </div>

                        <div>
                            <label for="update_password_password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Nova Senha') }}</label>
                            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-500 text-sm font-medium" />
                        </div>

                        <div>
                            <label for="update_password_password_confirmation" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Confirmar Nova Senha') }}</label>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 focus:border-brand-500 focus:ring focus:ring-brand-500/20 shadow-sm transition bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-500 text-sm font-medium" />
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t border-slate-100 dark:border-slate-700 transition-colors">
                            <button type="submit" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-md hover:shadow-lg transition flex items-center gap-2">
                                <i class="fa-solid fa-lock"></i> {{ __('Alterar Senha') }}
                            </button>
                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check"></i> {{ __('Senha salva!') }}
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- ABA 3: ZONA DE PERIGO -->
            <div x-show="tab === 'perigo'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-rose-200 dark:border-rose-900/50 relative overflow-hidden transition-colors">
                    <div class="absolute top-0 left-0 w-2 h-full bg-rose-500"></div>
                    
                    <h4 class="text-lg font-extrabold text-slate-900 dark:text-slate-100 mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> {{ __('Deletar Conta') }}
                    </h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 max-w-2xl">
                        {{ __('Assim que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir, certifique-se de baixar qualquer informação que deseje reter.') }}
                    </p>

                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="px-6 py-3 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 font-bold rounded-xl shadow-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-trash-can"></i> {{ __('Excluir Minha Conta') }}
                    </button>

                    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 dark:bg-slate-800">
                            @csrf
                            @method('delete')

                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <i class="fa-solid fa-skull text-rose-500"></i> {{ __('Você tem certeza absoluta?') }}
                            </h2>
                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                                {{ __('Esta ação é irreversível. Digite sua senha atual para confirmar a exclusão permanente da conta.') }}
                            </p>

                            <div class="mt-6">
                                <label for="password" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Sua Senha') }}</label>
                                <input id="password" name="password" type="password" placeholder="{{ __('Digite sua senha para confirmar') }}"
                                    class="w-full sm:w-3/4 px-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 focus:border-rose-500 focus:ring focus:ring-rose-500/20 shadow-sm transition bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100" />
                                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-500 text-sm font-medium" />
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                                    {{ __('Cancelar') }}
                                </button>
                                <button type="submit" class="px-6 py-2.5 rounded-xl font-bold text-white bg-rose-600 hover:bg-rose-700 transition shadow-md flex items-center gap-2">
                                    <i class="fa-solid fa-bomb"></i> {{ __('Sim, Deletar Conta') }}
                                </button>
                            </div>
                        </form>
                    </x-modal>
                </div>
            </div>

        </div>
    </div>
    <script>
        function selectColor(color) {
            const picker = document.getElementById('theme_color_picker');
            if (picker) {
                picker.value = color;
                document.documentElement.style.setProperty('--brand-primary', color);
            }
        }

        document.getElementById('theme_color_picker')?.addEventListener('input', function(e) {
            document.documentElement.style.setProperty('--brand-primary', e.target.value);
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    const fallback = document.getElementById('avatarFallback');

                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (fallback) fallback.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>