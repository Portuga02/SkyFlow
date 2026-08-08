<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Editar Tarefa') }}</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                @if ($errors->any())
                    <div class="mx-6 mt-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3">
                        <ul class="text-sm text-rose-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('todos.update', $todo->id) }}" class="p-6 space-y-5">
                    @method('PUT') @csrf

                    <div>
                        <x-input-label for="title">{{ __('Título') }}</x-input-label>
                        <x-text-input id="title" class="block w-full" type="text" name="title" required value="{{ old('title', $todo->title) }}" />
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Descrição') }}</x-input-label>
                        <textarea id="description" name="description" rows="5"
                            class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-brand-200 focus:ring-brand-500 focus:border-brand-500">{{ old('description', $todo->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="priority">{{ __('Prioridade') }}</x-input-label>
                            <select id="priority" name="priority" class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                                <option value="low" @selected(old('priority', $todo->priority) == 'low')>{{ __('Baixa') }}</option>
                                <option value="medium" @selected(old('priority', $todo->priority) == 'medium')>{{ __('Média') }}</option>
                                <option value="high" @selected(old('priority', $todo->priority) == 'high')>{{ __('Alta') }}</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="due_date">{{ __('Prazo') }}</x-input-label>
                            <x-text-input id="due_date" class="block w-full" type="date" name="due_date"
                                value="{{ old('due_date', $todo->due_date?->format('Y-m-d')) }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="category_id">{{ __('Categoria') }}</x-input-label>
                            <select id="category_id" name="category_id" class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                                <option value="">{{ __('Nenhuma') }}</option>
                                @foreach (\App\Models\Category::orderBy('name')->get() as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id', $todo->category_id) == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="assigned_to">{{ __('Responsável') }}</x-input-label>
                            <select id="assigned_to" name="assigned_to" class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                                <option value="">{{ __('Ninguém') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('assigned_to', $todo->assigned_to) == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="is_completed">{{ __('Status') }}</x-input-label>
                        <select id="is_completed" name="is_completed" class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm">
                            <option value="1" @selected($todo->is_completed)>{{ __('Completo') }}</option>
                            <option value="0" @selected(!$todo->is_completed)>{{ __('Pendente') }}</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('todos.show', $todo->id) }}" class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                            <i class="fa-solid fa-check"></i> {{ __('Salvar alterações') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
