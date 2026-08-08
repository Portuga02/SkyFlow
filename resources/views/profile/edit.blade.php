<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Meu Perfil') }}</h2>
        <p class="text-sm text-brand-600 mt-1">{{ __('Personalize sua conta e aparência.') }}</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status') === 'profile-updated')
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ __('Perfil atualizado com sucesso!') }}</span>
                </div>
            @elseif (session('status') === 'avatar-updated')
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ __('Avatar atualizado com sucesso!') }}</span>
                </div>
            @elseif (session('status') === 'theme-updated')
                <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ __('Tema atualizado com sucesso!') }}</span>
                </div>
            @endif

            <!-- Avatar Section -->
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <h3 class="font-bold text-brand-950 mb-4">{{ __('Avatar') }}</h3>

                <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                    @if ($user->avatar_path)
                        <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}"
                            class="h-24 w-24 rounded-full object-cover border-4 border-brand-100">
                    @else
                        <div class="h-24 w-24 rounded-full bg-brand-600 text-white flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="flex-1">
                        @csrf
                        <label class="block">
                            <span class="text-sm font-semibold text-brand-600 mb-2 block">{{ __('Upload nova foto') }}</span>
                            <input type="file" name="avatar" accept="image/*" required
                                class="text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-600 file:text-sm file:font-semibold hover:file:bg-brand-100">
                        </label>
                        <button type="submit" class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold">
                            <i class="fa-solid fa-upload"></i> {{ __('Atualizar') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Theme Section -->
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <h3 class="font-bold text-brand-950 mb-4">{{ __('Tema') }}</h3>

                <form method="POST" action="{{ route('profile.theme') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="theme_color">{{ __('Cor do Tema') }}</x-input-label>
                        <div class="flex items-center gap-3 mt-2">
                            <input type="color" name="theme_color" id="theme_color"
                                value="{{ $user->theme_color ?? '#0c8fe6' }}"
                                class="h-12 w-16 rounded-lg border border-brand-200 cursor-pointer p-1">
                            <input type="text" value="{{ $user->theme_color ?? '#0c8fe6' }}" disabled
                                class="text-sm rounded-lg border-brand-200 bg-gray-50 px-3 py-2">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Esta cor será aplicada em todos os elementos principais da interface.') }}</p>
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold">
                        <i class="fa-solid fa-floppy-disk"></i> {{ __('Salvar tema') }}
                    </button>
                </form>
            </div>

            <!-- Profile Info Section -->
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 p-6">
                <h3 class="font-bold text-brand-950 mb-4">{{ __('Informações da Conta') }}</h3>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div>
                        <x-input-label for="name">{{ __('Nome') }}</x-input-label>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="email">{{ __('E-mail') }}</x-input-label>
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                            value="{{ old('email', $user->email) }}" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                            <i class="fa-solid fa-check"></i> {{ __('Salvar') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete Account Section -->
            <div class="bg-white rounded-2xl shadow-card border border-rose-50 p-6">
                <h3 class="font-bold text-rose-950 mb-4">{{ __('Zona de Risco') }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ __('Excluir sua conta é permanente. Todos os seus dados serão deletados.') }}</p>

                <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
                    onsubmit="return confirm('{{ __('Tem certeza? Esta ação não pode ser desfeita.') }}')">
                    @csrf
                    @method('delete')

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Excluir conta') }}</h3>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Uma vez que sua conta foi deletada, não há como voltar atrás. Por favor tenha certeza.') }}
                    </p>

                    <div class="mt-6">
                        <x-input-label for="password" value="{{ __('Senha') }}" class="sr-only" />

                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                            placeholder="{{ __('Senha') }}" required />

                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-danger-button>{{ __('Excluir conta') }}</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
