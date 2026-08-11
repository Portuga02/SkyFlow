<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Nova Tarefa') }}</h2>
            <p class="text-sm text-brand-600 mt-1">{{ __('Descreva o que precisa ser feito.') }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if (session('alert-success'))
                <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ session('alert-success') }}</span>
                </div>
            @endif

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

                <form method="POST" action="{{ route('todos.store') }}" class="p-6 space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="title">{{ __('Título') }}</x-input-label>
                        <x-text-input id="title" class="block w-full" type="text" name="title"
                            placeholder="{{ __('Ex: Enviar relatório semanal') }}" value="{{ old('title') }}" required autofocus />
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Descrição') }}</x-input-label>
                        <textarea id="description" name="description" rows="4"
                            placeholder="{{ __('Detalhe a atividade...') }}"
                            class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-brand-200 focus:ring-brand-500 focus:border-brand-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Dropdown Customizado de Prioridades (Estilo Jira/Bitbucket) -->
                        <div x-data="{
                                open: false,
                                selected: '{{ old('priority', 'high') }}',
                                options: {
                                    'highest': { label: 'Mais Alta (Highest)', icon: 'fa-solid fa-angles-up', color: 'text-rose-500' },
                                    'high':    { label: 'Alta (High)', icon: 'fa-solid fa-angle-up', color: 'text-rose-500' },
                                    'low':     { label: 'Baixa (Low)', icon: 'fa-solid fa-angle-down', color: 'text-blue-500' },
                                    'lowest':  { label: 'Mais Baixa (Lowest)', icon: 'fa-solid fa-angles-down', color: 'text-blue-500' }
                                }
                            }" class="relative">
                            
                            <x-input-label>{{ __('Prioridade') }}</x-input-label>
                            
                            <!-- Input Oculto -->
                            <input type="hidden" name="priority" :value="selected">

                            <!-- Botão Principal -->
                            <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full flex items-center justify-between px-3 py-2 mt-1 bg-white border border-brand-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition shadow-sm">
                                <span class="flex items-center gap-2">
                                    <i :class="options[selected].icon + ' ' + options[selected].color"></i>
                                    <span x-text="options[selected].label" class="text-brand-950 font-medium"></span>
                                </span>
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Lista Suspensa -->
                            <div x-show="open" x-transition x-cloak
                                class="absolute z-10 w-full mt-1 bg-white border border-brand-100 rounded-lg shadow-lg overflow-hidden">
                                <template x-for="(option, key) in options" :key="key">
                                    <div @click="selected = key; open = false"
                                        class="flex items-center gap-2 px-3 py-2.5 cursor-pointer hover:bg-brand-50 transition"
                                        :class="selected === key ? 'bg-brand-50' : ''">
                                        
                                        <i :class="option.icon + ' ' + option.color" class="w-4 text-center"></i>
                                        <span x-text="option.label" class="text-sm font-medium text-brand-950"></span>
                                        
                                        <i x-show="selected === key" class="fa-solid fa-check ml-auto text-brand-600 text-xs"></i>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Data e Hora com datetime-local -->
                        <div>
                            <x-input-label for="due_date">{{ __('Prazo e Hora (opcional)') }}</x-input-label>
                            <x-text-input id="due_date" class="block w-full text-sm mt-1" type="datetime-local" name="due_date" value="{{ old('due_date') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="category_id">{{ __('Categoria (opcional)') }}</x-input-label>
                            <select id="category_id" name="category_id" class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm mt-1">
                                <option value="">{{ __('Nenhuma') }}</option>
                                @foreach (\App\Models\Category::orderBy('name')->get() as $cat)
                                    <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="assigned_to">{{ __('Responsável (opcional)') }}</x-input-label>
                            <select id="assigned_to" name="assigned_to" class="block w-full rounded-lg border-brand-200 focus:border-brand-500 focus:ring-brand-500 text-sm mt-1">
                                <option value="">{{ __('Ninguém') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('todos.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                            <i class="fa-solid fa-floppy-disk"></i> {{ __('Salvar tarefa') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>