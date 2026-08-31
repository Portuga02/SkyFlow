<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-slate-400 transition-colors duration-300">
        {{ __('Esta é uma área segura do aplicativo. Confirme sua senha antes de continuar.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Senha')" class="dark:text-slate-200" />

            <x-text-input id="password" class="block mt-1 w-full dark:bg-slate-900 dark:border-slate-700 dark:text-slate-100 transition-colors"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button class="bg-brand-600 hover:bg-brand-700 dark:bg-brand-600 dark:hover:bg-brand-700 text-white">
                {{ __('Confirmar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>