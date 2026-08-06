<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
            {{ __('Perfil') }}
        </h2>
        <p class="text-sm text-brand-600 mt-1">{{ __('Gerencie suas informações de conta e segurança.') }}</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white shadow-card border border-brand-50 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-card border border-brand-50 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-card border border-rose-100 rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>