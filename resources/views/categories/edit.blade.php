<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
                {{ __('Editar Categoria') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">

                @if ($errors->any())
                    <div class="mx-6 mt-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3" role="alert">
                        <ul class="text-sm text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('categories.update', $category->id) }}" class="p-6 space-y-5"
                    x-data="{ color: '{{ old('color', $category->color) }}', icon: '{{ old('icon', $category->icon) }}' }">
                    @method('PUT')
                    @csrf

                    <div>
                        <x-input-label for="name">{{ __('Nome') }}</x-input-label>
                        <x-text-input id="name" class="block w-full" type="text" name="name"
                            value="{{ old('name', $category->name) }}" required autofocus />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="color">{{ __('Cor') }}</x-input-label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="color" name="color" id="color"
                                    class="h-10 w-14 rounded-lg border border-brand-200 cursor-pointer p-1">
                                <input type="text" x-model="color"
                                    class="block w-full text-sm rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500"
                                    maxlength="7">
                            </div>
                        </div>

                        <div>
                            <x-input-label for="icon">{{ __('Ícone') }}</x-input-label>
                            <select x-model="icon" name="icon" id="icon"
                                class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                                <option value="fa-solid fa-layer-group">📁 {{ __('Geral') }}</option>
                                <option value="fa-solid fa-briefcase">💼 {{ __('Trabalho') }}</option>
                                <option value="fa-solid fa-house">🏠 {{ __('Pessoal') }}</option>
                                <option value="fa-solid fa-book">📚 {{ __('Estudos') }}</option>
                                <option value="fa-solid fa-heart-pulse">❤️ {{ __('Saúde') }}</option>
                                <option value="fa-solid fa-dollar-sign">💰 {{ __('Financeiro') }}</option>
                                <option value="fa-solid fa-users">👥 {{ __('Time') }}</option>
                                <option value="fa-solid fa-star">⭐ {{ __('Prioridade') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="flex items-center gap-3 rounded-lg border border-dashed border-brand-200 p-4">
                        <div class="h-11 w-11 shrink-0 rounded-xl flex items-center justify-center text-white"
                            :style="`background-color: ${color}`">
                            <i :class="icon"></i>
                        </div>
                        <span class="text-sm text-gray-500">{{ __('Pré-visualização') }}</span>
                    </div>

                    @if ($parentOptions->isNotEmpty())
                        <div>
                            <x-input-label for="parent_id">{{ __('Categoria pai (opcional)') }}</x-input-label>
                            <select name="parent_id" id="parent_id"
                                class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                                <option value="">{{ __('Nenhuma — é uma categoria principal') }}</option>
                                @foreach ($parentOptions as $option)
                                    <option value="{{ $option->id }}" @selected(old('parent_id', $category->parent_id) == $option->id)>
                                        {{ $option->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('categories.index') }}"
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