<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Nova Tarefa') }}</h2>
            <p class="text-sm text-brand-600 mt-1">{{ __('Descreva o que precisa ser feito.') }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('alert-success'))
                <div
                    class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-medium">{{ session('alert-success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-card border border-brand-50">
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
                            placeholder="{{ __('Ex: Enviar relatório semanal') }}" value="{{ old('title') }}" required
                            autofocus />
                    </div>

                    <div>
                        <x-input-label for="description">{{ __('Descrição') }}</x-input-label>
                        <textarea id="description" name="description" rows="4" placeholder="{{ __('Detalhe a atividade...') }}"
                            class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-brand-200 focus:ring-brand-500 focus:border-brand-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Dropdown de Prioridades -->
                        <div x-data="{
                            open: false,
                            selected: '{{ old('priority', 'high') }}',
                            options: {
                                'highest': { label: 'Mais Alta (Highest)', icon: 'fa-solid fa-angles-up', color: 'text-rose-600' },
                                'high': { label: 'Alta (High)', icon: 'fa-solid fa-angle-up', color: 'text-rose-500' },
                                'low': { label: 'Baixa (Low)', icon: 'fa-solid fa-angle-down', color: 'text-sky-500' },
                                'lowest': { label: 'Mais Baixa (Lowest)', icon: 'fa-solid fa-angles-down', color: 'text-blue-600' }
                            }
                        }" class="relative">

                            <x-input-label>{{ __('Prioridade') }}</x-input-label>
                            <input type="hidden" name="priority" :value="selected">

                            <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full flex items-center justify-between px-3 py-2 mt-1 bg-white border border-brand-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition shadow-sm">
                                <span class="flex items-center gap-2">
                                    <i :class="options[selected].icon + ' ' + options[selected].color"></i>
                                    <span x-text="options[selected].label" class="text-brand-950 font-medium"></span>
                                </span>
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open" x-transition x-cloak
                                class="absolute z-50 w-full mt-1 bg-white border border-brand-100 rounded-lg shadow-xl overflow-hidden">
                                <template x-for="(option, key) in options" :key="key">
                                    <div @click="selected = key; open = false"
                                        class="flex items-center gap-2 px-3 py-2.5 cursor-pointer hover:bg-brand-50 transition"
                                        :class="selected === key ? 'bg-brand-50' : ''">
                                        <i :class="option.icon + ' ' + option.color" class="w-4 text-center"></i>
                                        <span x-text="option.label" class="text-sm font-medium text-brand-950"></span>
                                        <i x-show="selected === key"
                                            class="fa-solid fa-check ml-auto text-brand-600 text-xs"></i>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Data e Hora -->
                        <div>
                            <x-input-label for="due_date">{{ __('Prazo e Hora (opcional)') }}</x-input-label>
                            <x-text-input id="due_date" class="block w-full text-sm mt-1" type="datetime-local"
                                name="due_date" value="{{ old('due_date') }}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <!-- Dropdown de Categoria Colorida -->
                        <div x-data="{
                            open: false,
                            selected: '{{ old('category_id', '') }}',
                            cats: [
                                { id: '', name: 'Nenhuma', icon: 'fa-solid fa-layer-group', color: 'text-gray-400' },
                                @foreach (\App\Models\Category::orderBy('name')->get() as $index => $cat)
                                        { 
                                            id: '{{ $cat->id }}', 
                                            name: '{{ addslashes($cat->name) }}', 
                                            icon: '{{ $cat->icon ?? 'fa-solid fa-tag' }}', 
                                            color: '@php
                                                $colors = ["text-amber-500", "text-emerald-500", "text-sky-500", "text-indigo-500", "text-rose-500", "text-purple-500", "text-teal-500"];
                                                echo $colors[$index % count($colors)];
                                            @endphp' 
                                        }, @endforeach
                            ],
                            getSelectedName() {
                                let found = this.cats.find(c => c.id == this.selected);
                                return found ? found.name : 'Nenhuma';
                            },
                            getSelectedIcon() {
                                let found = this.cats.find(c => c.id == this.selected);
                                return found ? (found.icon + ' ' + found.color) : 'fa-solid fa-layer-group text-gray-400';
                            }
                        }" class="relative">

                            <x-input-label>{{ __('Categoria (opcional)') }}</x-input-label>
                            <input type="hidden" name="category_id" :value="selected">

                            <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full flex items-center justify-between px-3 py-2 mt-1 bg-white border border-brand-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition shadow-sm">
                                <span class="flex items-center gap-2">
                                    <i :class="getSelectedIcon()" class="text-base"></i>
                                    <span x-text="getSelectedName()" class="text-brand-950 font-medium truncate"></span>
                                </span>
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="open" x-transition x-cloak
                                class="absolute z-50 w-full mt-1 bg-white border border-brand-100 rounded-lg shadow-xl overflow-y-auto max-h-48">
                                <template x-for="cat in cats" :key="cat.id">
                                    <div @click="selected = cat.id; open = false"
                                        class="flex items-center gap-2.5 px-3 py-2.5 cursor-pointer hover:bg-brand-50 transition"
                                        :class="selected == cat.id ? 'bg-brand-50' : ''">
                                        <i :class="cat.icon + ' ' + cat.color" class="w-5 text-center text-base"></i>
                                        <span x-text="cat.name" class="text-sm font-medium text-brand-950"></span>
                                        <i x-show="selected == cat.id"
                                            class="fa-solid fa-check ml-auto text-brand-600 text-xs"></i>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div x-data="{
                            open: false,
                            selected: {{ json_encode(old('assigned_to', [])) }},
                            users: [
                                @foreach ($users as $user)
                {
                    id: {{ $user->id }},
                    name: '{{ addslashes($user->name) }}',
                    avatar: '@php
                        if (!empty($user->avatar_path)) {
                            echo asset('storage/' . $user->avatar_path);
                        } else {
                            echo 'https://api.dicebear.com/7.x/notionists/svg?seed=' . urlencode($user->name) . '&backgroundColor=e0e7ff,fef3c7,dbeafe,fce7f3';
                        }
                    @endphp'
                }, @endforeach
                            ],
                            toggle(id) {
                                if (this.selected.includes(id)) {
                                    this.selected = this.selected.filter(item => item !== id);
                                } else {
                                    this.selected.push(id);
                                }
                            },
                            getUser(id) {
                                return this.users.find(u => u.id === id);
                            }
                        }" class="relative">

                            <x-input-label>{{ __('Responsáveis (Equipe)') }}</x-input-label>

                            <!-- Inputs Hidden para envio do Array no Laravel -->
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="assigned_to[]" :value="id">
                            </template>

                            <!-- Botão Principal -->
                            <button @click="open = !open" @click.outside="open = false" type="button"
                                class="w-full flex items-center justify-between px-3 py-2 mt-1 bg-white border border-brand-200 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm transition shadow-sm min-h-[42px]">

                                <div class="flex items-center gap-2 flex-wrap">
                                    <template x-if="selected.length === 0">
                                        <span class="flex items-center gap-2 text-gray-400">
                                            <i class="fa-solid fa-users text-gray-400"></i>
                                            <span>Ninguém selecionado</span>
                                        </span>
                                    </template>

                                    <template x-if="selected.length > 0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <template x-for="id in selected" :key="id">
                                                <span
                                                    class="inline-flex items-center gap-1.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-semibold px-2 py-0.5 rounded-full shadow-xs">
                                                    <img :src="getUser(id)?.avatar"
                                                        class="w-4 h-4 rounded-full object-cover bg-white">
                                                    <span x-text="getUser(id)?.name"></span>
                                                    <i @click.stop="toggle(id)"
                                                        class="fa-solid fa-xmark text-[10px] hover:text-indigo-900 cursor-pointer ml-0.5"></i>
                                                </span>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-200 ml-2"
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Menu Suspenso -->
                            <div x-show="open" x-transition x-cloak
                                class="absolute z-50 w-full mt-1 bg-white border border-brand-100 rounded-lg shadow-xl overflow-y-auto max-h-56 divide-y divide-gray-50">
                                <template x-for="user in users" :key="user.id">
                                    <div @click="toggle(user.id)"
                                        class="flex items-center justify-between px-3 py-2 cursor-pointer hover:bg-indigo-50/50 transition"
                                        :class="selected.includes(user.id) ? 'bg-indigo-50/40' : ''">

                                        <div class="flex items-center gap-3">
                                            <!-- Foto real ou Ilustração de Personagem -->
                                            <img :src="user.avatar"
                                                class="w-8 h-8 rounded-full object-cover border border-indigo-100 shadow-xs bg-slate-50"
                                                :alt="user.name">
                                            <span x-text="user.name"
                                                class="text-sm font-medium text-brand-950"></span>
                                        </div>

                                        <!-- Checkbox visual -->
                                        <div class="w-5 h-5 rounded-md border flex items-center justify-center transition"
                                            :class="selected.includes(user.id) ? 'bg-indigo-600 border-indigo-600 text-white' :
                                                'border-gray-300 bg-white'">
                                            <i x-show="selected.includes(user.id)"
                                                class="fa-solid fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

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
