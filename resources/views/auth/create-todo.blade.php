<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
                {{ __('Nova Tarefa') }}
            </h2>
            <p class="text-sm text-brand-600 mt-1">{{ __('Descreva o que precisa ser feito.') }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('alert-success'))
                <div class="mb-6 animate-fade-in flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ session('alert-success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                <div class="px-6 pt-6 pb-2 flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600">
                        <i class="fa-solid fa-circle-plus"></i>
                    </div>
                    <h3 class="font-bold text-lg text-brand-950">
                        {{ __('Adicionar um novo item') }}
                    </h3>
                </div>

                @if ($errors->any())
                    <div class="mx-6 mt-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3" role="alert">
                        <ul class="text-sm text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('todos.store') }}" class="p-6 space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="title">{{ __('Título') }}</x-input-label>
                        <x-text-input id="title" class="block w-full" type="text" name="title"
                            placeholder="{{ __('Ex: Enviar relatório semanal') }}" value="{{ old('title') }}" required autofocus />
                    </div>

                    <div>
                        <x-input-label for="message">{{ __('Descrição') }}</x-input-label>
                        <textarea id="message" name="description" rows="4"
                            placeholder="{{ __('Detalhe a atividade...') }}"
                            class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-brand-200 focus:ring-brand-500 focus:border-brand-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('todos.index') }}"
                            class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                            <i class="fa-solid fa-floppy-disk"></i> {{ __('Salvar tarefa') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
