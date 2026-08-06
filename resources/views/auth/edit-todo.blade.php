<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
                {{ __('Editar Tarefa') }}
            </h2>
            <p class="text-sm text-brand-600 mt-1">{{ __('Atualize as informações da atividade.') }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                <div class="px-6 pt-6 pb-2 flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600">
                        <i class="fa-solid fa-pen"></i>
                    </div>
                    <h3 class="font-bold text-lg text-brand-950">
                        {{ __('Editar a tarefa existente') }}
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

                <form method="POST" action="{{ route('todos.update', $todo->id) }}" class="p-6 space-y-5">
                    @method('PUT')
                    @csrf

                    <div>
                        <x-input-label for="title">{{ __('Título') }}</x-input-label>
                        <x-text-input id="title" class="block w-full" type="text" name="title" required
                            autocomplete="title" value="{{ old('title', $todo->title) }}" />
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Descrição') }}</x-input-label>
                        <textarea id="description" name="description" rows="5"
                            class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-brand-200 focus:ring-brand-500 focus:border-brand-500">{{ old('description', $todo->description) }}</textarea>
                    </div>
                    <!-- Seletor de Categoria (Adicionado) -->
                    <div>
                        <x-input-label for="category_id">{{ __('Categoria') }} <span
                                class="text-xs text-brand-400 font-normal ml-1">({{ __('Opcional') }})</span></x-input-label>
                        <select id="category_id" name="category_id"
                            class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-brand-200 focus:ring-brand-500 focus:border-brand-500 mt-1">
                            <option value="">{{ __('Sem categoria') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $todo->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="is_completed">{{ __('Status') }}</x-input-label>
                        <select id="is_completed" name="is_completed"
                            class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-brand-200 focus:ring-brand-500 focus:border-brand-500">
                            <option value="1" @selected($todo->is_completed)> {{ __('Completo') }}</option>
                            <option value="0" @selected(!$todo->is_completed)> {{ __('Pendente') }}</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('todos.index') }}"
                            class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                            <i class="fa-solid fa-check"></i> {{ __('Salvar alterações') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
