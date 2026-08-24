<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Meu Perfil') }}</h2>
                <p class="text-sm text-brand-600 mt-1">
                    {{ __('Gerencie seus dados pessoais, segurança e aparência do sistema.') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6" x-data="{ tab: 'geral' }">

        <!-- Card de Resumo do Usuário & Workspace -->
        <div
            class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    @if (Auth::user()->avatar_path)
                        <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="{{ Auth::user()->name }}"
                            class="h-16 w-16 rounded-2xl object-cover shadow-sm ring-2 ring-brand-100">
                    @else
                        <div
                            class="h-16 w-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-2xl font-bold shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-bold text-brand-950">{{ Auth::user()->name }}</h3>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="px-3.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700">
                    <i class="fa-solid fa-building-user text-slate-400 mr-1.5"></i>
                    Workspace: <strong class="text-brand-950">{{ Auth::user()->team->name ?? 'Pessoal' }}</strong>
                </div>

                @if (Auth::user()->role === 'admin')
                    <span
                        class="px-3 py-1.5 rounded-xl bg-purple-50 border border-purple-200 text-purple-700 text-xs font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-crown text-purple-600"></i> Admin
                    </span>
                @else
                    <span
                        class="px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-user text-blue-600"></i> Membro
                    </span>
                @endif
            </div>
        </div>

        <!-- Navegação por Abas -->
        <div class="flex border-b border-slate-200 gap-6">
            <button @click="tab = 'geral'"
                :class="tab === 'geral' ? 'border-brand-600 text-brand-600 font-bold' :
                    'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 border-b-2 text-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-user-gear text-xs"></i> {{ __('Geral & Aparência') }}
            </button>
            <button @click="tab = 'seguranca'"
                :class="tab === 'seguranca' ? 'border-brand-600 text-brand-600 font-bold' :
                    'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 border-b-2 text-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-shield-halved text-xs"></i> {{ __('Segurança & Senha') }}
            </button>
        </div>

        <!-- ABA 1: GERAL & APARÊNCIA -->
        <div x-show="tab === 'geral'" class="space-y-6">

            <!-- Formulário de Informações Básicas & Avatar -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100">
                <h4 class="text-base font-bold text-brand-950 mb-1">{{ __('Informações Pessoais') }}</h4>
                <p class="text-xs text-slate-500 mb-6">
                    {{ __('Atualize sua foto de exibição, nome e endereço de e-mail.') }}</p>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    @method('patch')

                    <!-- Upload com Preview Dinâmico -->
                    <div>
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">{{ __('Foto de Perfil') }}</label>
                        <div class="flex items-center gap-5">
                            <div id="avatarPreviewContainer" class="relative shrink-0">
                                @if (Auth::user()->avatar_path)
                                    <img id="avatarPreview" src="{{ asset('storage/' . Auth::user()->avatar_path) }}"
                                        class="h-16 w-16 rounded-2xl object-cover ring-2 ring-brand-100 shadow-xs">
                                @else
                                    <div id="avatarFallback"
                                        class="h-16 w-16 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-xl font-bold shadow-xs">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <img id="avatarPreview"
                                        class="h-16 w-16 rounded-2xl object-cover ring-2 ring-brand-100 shadow-xs hidden">
                                @endif
                            </div>

                            <div class="flex-1">
                                <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden"
                                    onchange="previewImage(this)">
                                <label for="avatarInput"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold cursor-pointer transition">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i> {{ __('Escolher nova foto') }}
                                </label>
                                <p class="text-[11px] text-slate-400 mt-1.5">PNG, JPG ou WEBP até 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="name" :value="__('Nome')"
                                class="text-xs font-bold text-slate-600 uppercase" />
                            <x-text-input id="name" name="name" type="text"
                                class="mt-1 block w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500"
                                :value="old('name', $user->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('E-mail')"
                                class="text-xs font-bold text-slate-600 uppercase" />
                            <x-text-input id="email" name="email" type="email"
                                class="mt-1 block w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500"
                                :value="old('email', $user->email)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <!-- Personalização do Tema com Cores Rápidas -->
                    <div class="pt-4 border-t border-slate-100">
                        <label
                            class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">{{ __('Cor do Tema') }}</label>
                        <p class="text-xs text-slate-400 mb-3">
                            {{ __('Escolha uma cor de destaque para botões, destaques e menus.') }}</p>

                        <div class="flex items-center gap-3">
                            <input type="color" id="theme_color_picker" name="theme_color"
                                value="{{ old('theme_color', $user->theme_color ?? '#0071c4') }}"
                                class="h-10 w-16 rounded-xl cursor-pointer border border-slate-200 p-0.5">

                            <!-- Paleta Pré-definida -->
                            <div class="flex gap-2">
                                <button type="button" onclick="selectColor('#0071c4')"
                                    class="h-8 w-8 rounded-xl bg-[#0071c4] border border-black/10 hover:scale-110 transition shadow-xs"></button>
                                <button type="button" onclick="selectColor('#8b5cf6')"
                                    class="h-8 w-8 rounded-xl bg-[#8b5cf6] border border-black/10 hover:scale-110 transition shadow-xs"></button>
                                <button type="button" onclick="selectColor('#10b981')"
                                    class="h-8 w-8 rounded-xl bg-[#10b981] border border-black/10 hover:scale-110 transition shadow-xs"></button>
                                <button type="button" onclick="selectColor('#f97316')"
                                    class="h-8 w-8 rounded-xl bg-[#f97316] border border-black/10 hover:scale-110 transition shadow-xs"></button>
                                <button type="button" onclick="selectColor('#ec4899')"
                                    class="h-8 w-8 rounded-xl bg-[#ec4899] border border-black/10 hover:scale-110 transition shadow-xs"></button>
                                <button type="button" onclick="selectColor('#334155')"
                                    class="h-8 w-8 rounded-xl bg-[#334155] border border-black/10 hover:scale-110 transition shadow-xs"></button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-md hover:shadow-lg transition">
                            {{ __('Salvar Alterações') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ABA 2: SEGURANÇA & SENHA -->
        <div x-show="tab === 'seguranca'" class="space-y-6" x-cloak>
            <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100">
                <h4 class="text-base font-bold text-brand-950 mb-1">{{ __('Atualizar Senha') }}</h4>
                <p class="text-xs text-slate-500 mb-6">
                    {{ __('Garanta que sua conta utilize uma senha forte com letras e números.') }}</p>

                <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
                    @csrf
                    @method('put')

                    <div>
                        <x-input-label for="update_password_current_password" :value="__('Senha Atual')"
                            class="text-xs font-bold text-slate-600 uppercase" />
                        <x-text-input id="update_password_current_password" name="current_password" type="password"
                            class="mt-1 block w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500"
                            autocomplete="current-password" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password" :value="__('Nova Senha')"
                            class="text-xs font-bold text-slate-600 uppercase" />
                        <x-text-input id="update_password_password" name="password" type="password"
                            class="mt-1 block w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500"
                            autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Nova Senha')"
                            class="text-xs font-bold text-slate-600 uppercase" />
                        <x-text-input id="update_password_password_confirmation" name="password_confirmation"
                            type="password"
                            class="mt-1 block w-full rounded-xl border-slate-200 text-sm focus:border-brand-500 focus:ring-brand-500"
                            autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex justify-end pt-3">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold shadow-md hover:shadow-lg transition">
                            {{ __('Alterar Senha') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Scripts da Página -->
    <script>
        function selectColor(color) {
            const picker = document.getElementById('theme_color_picker');
            if (picker) {
                picker.value = color;
               
                document.documentElement.style.setProperty('--brand-primary', color);
            }
        }

        // Ao mudar no input de cor
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
</x-app-layout>
