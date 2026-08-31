<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-thumbtack text-amber-500"></i> {{ __('Bloco de Notas') }}
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Suas ideias, rascunhos e links rápidos.') }}
                </p>
            </div>
            <!-- Botão dispara o Modal de Criação (Sem <form> ao redor) -->
            <button type="button" @click="$dispatch('open-create')"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all active:scale-95 flex-shrink-0">
                <i class="fa-solid fa-plus"></i> {{ __('Nova Nota') }}
            </button>
        </div>
    </x-slot>

    <!-- Import SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 min-h-screen">

        @if ($notes->isEmpty())
            <div
                class="flex flex-col items-center justify-center py-20 px-4 text-center bg-white rounded-3xl border border-dashed border-slate-300 shadow-sm">
                <div class="h-24 w-24 bg-amber-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                    <i class="fa-regular fa-note-sticky text-5xl text-amber-400 transform -rotate-6"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">{{ __('Nenhuma nota encontrada') }}</h3>
                <p class="text-slate-500 mb-8 max-w-md">
                    {{ __('O seu quadro de ideias está vazio. Clique no botão abaixo para criar seu primeiro post-it colorido.') }}
                </p>
                <button type="button" @click="$dispatch('open-create')"
                    class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl shadow-md transition-all">
                    Criar Primeira Nota
                </button>
            </div>
        @else
            <!-- Grid de Notas -->
            <div id="notes-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                @foreach ($notes as $note)
                    <div class="h-72 flex flex-col rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 border border-black/5 overflow-hidden transition-all group relative"
                        style="background-color: {{ $note->color ?? '#fef08a' }};" @click="open{{ $note->id }} = true"
                        x-data="{ open{{ $note->id }}: false }">

                        <div class="drag-handle absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity cursor-grab active:cursor-grabbing p-1 text-black/30 hover:text-black/60 z-10"
                            title="Arraste para reordenar">
                            <i class="fa-solid fa-grip-vertical text-lg"></i>
                        </div>

                        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-black/10 shadow-inner">
                        </div>

                        <div class="p-5 flex flex-col h-full relative" x-show="!open{{ $note->id }}">
                            <button type="button" @click.stop="
                                            navigator.clipboard.writeText($refs.noteContent.innerText); 
                                            $el.innerHTML = '<i class=\'fa-solid fa-check\'></i>'; 
                                            $el.classList.add('bg-emerald-500', 'text-white');
                                            setTimeout(() => { 
                                                $el.innerHTML = '<i class=\'fa-regular fa-copy\'></i>'; 
                                                $el.classList.remove('bg-emerald-500', 'text-white');
                                            }, 2000)
                                        "
                                class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 text-slate-600 hover:text-slate-900 bg-white/40 hover:bg-white/80 backdrop-blur-sm rounded-lg p-2 shadow-sm z-10"
                                title="Copiar texto">
                                <i class="fa-regular fa-copy"></i>
                            </button>

                            <h3 class="font-extrabold text-lg text-slate-900 mt-2 mb-3 pr-8 leading-tight truncate">
                                {{ $note->title ?: 'Sem título' }}
                            </h3>

                            <div x-ref="noteContent"
                                class="text-sm text-slate-800 flex-1 prose-sm prose-slate max-w-none line-clamp-5 overflow-hidden">
                                {!! $note->content ?: '<span class="text-black/40 italic">Clique para escrever...</span>' !!}
                            </div>

                            <div
                                class="mt-auto pt-4 border-t border-black/10 flex items-center justify-between text-xs font-semibold text-black/40">
                                <span><i class="fa-regular fa-calendar mr-1"></i>
                                    {{ $note->created_at->format('d/m/Y') }}</span>
                            </div>

                            <div
                                class="absolute bottom-0 left-0 right-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-gradient-to-t from-black/20 to-transparent flex gap-2 z-10">
                                <button @click.stop="open{{ $note->id }} = true"
                                    class="flex-1 py-2 text-xs font-bold bg-white/70 hover:bg-white backdrop-blur-md rounded-xl text-slate-800 transition shadow-sm flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </button>

                                <form method="POST" action="{{ route('notes.destroy', $note->id) }}" class="flex-1" @click.stop
                                    onsubmit="return confirm('Tem certeza que deseja apagar esta anotação?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full py-2 text-xs font-bold bg-rose-500/80 hover:bg-rose-500 backdrop-blur-md rounded-xl text-white transition shadow-sm flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-trash-can"></i> Apagar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- MODAL DE EDIÇÃO -->
                        <template x-teleport="body">
                            <div x-show="open{{ $note->id }}" x-cloak
                                class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95">

                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
                                    @click="open{{ $note->id }} = false"></div>

                                <div class="relative w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-500 flex flex-col max-h-[90vh]"
                                    x-data="noteForm{{ $note->id }}()" :style="`background-color: ${color}`">

                                    <div
                                        class="flex items-center justify-between px-6 py-4 border-b border-black/10 bg-black/5">
                                        <h3 class="font-extrabold text-xl text-slate-900 flex items-center gap-2">
                                            <i class="fa-solid fa-pen-nib text-black/50"></i> {{ __('Editando Nota') }}
                                        </h3>
                                        <button @click="open{{ $note->id }} = false"
                                            class="h-8 w-8 flex items-center justify-center rounded-full bg-black/5 hover:bg-black/20 text-slate-700 transition">
                                            <i class="fa-solid fa-xmark text-lg"></i>
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ route('notes.update', $note->id) }}"
                                        @submit.prevent="submit()"
                                        class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1">
                                        @csrf @method('PATCH')

                                        <div>
                                            <input type="text" name="title" x-model="title" placeholder="Título da Nota"
                                                class="w-full px-0 py-2 border-0 border-b-2 border-black/10 focus:border-black/40 focus:ring-0 bg-transparent text-3xl font-extrabold text-slate-900 placeholder-black/30 transition">
                                        </div>

                                        <!-- Toolbar e Editor HTML -->
                                        <div
                                            class="rounded-xl border border-black/10 focus-within:border-black/30 bg-white/80 backdrop-blur-sm shadow-sm overflow-hidden transition flex flex-col">
                                            <div
                                                class="flex items-center gap-1 p-2 bg-white border-b border-slate-200 flex-wrap shrink-0">
                                                <button type="button" @click="document.execCommand('bold', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif font-bold transition">B</button>
                                                <button type="button" @click="document.execCommand('italic', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif italic transition">I</button>
                                                <button type="button" @click="document.execCommand('underline', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif underline transition">U</button>
                                                <button type="button"
                                                    @click="document.execCommand('strikeThrough', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif line-through transition">S</button>

                                                <div class="w-px h-5 bg-slate-200 mx-1"></div>

                                                <!-- BOTÃO COR DO TEXTO (NOVO) -->
                                                <div class="relative w-8 h-8 rounded hover:bg-slate-100 flex items-center justify-center transition overflow-hidden"
                                                    title="Cor do Texto">
                                                    <input type="color"
                                                        @input="document.execCommand('foreColor', false, $event.target.value)"
                                                        class="absolute -top-2 -left-2 w-12 h-12 cursor-pointer opacity-0">
                                                    <i class="fa-solid fa-droplet text-slate-700 pointer-events-none"></i>
                                                </div>

                                                <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                                <button type="button"
                                                    @click="document.execCommand('insertUnorderedList', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                                        class="fa-solid fa-list-ul"></i></button>
                                                <button type="button"
                                                    @click="document.execCommand('insertOrderedList', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                                        class="fa-solid fa-list-ol"></i></button>
                                                <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                                <button type="button" @click="document.execCommand('justifyLeft', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                                        class="fa-solid fa-align-left"></i></button>
                                                <button type="button"
                                                    @click="document.execCommand('justifyCenter', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                                        class="fa-solid fa-align-center"></i></button>
                                                <button type="button" @click="document.execCommand('justifyRight', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                                        class="fa-solid fa-align-right"></i></button>
                                                <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                                <button type="button" @click="document.execCommand('removeFormat', false, null)"
                                                    class="w-8 h-8 rounded hover:bg-rose-50 text-rose-600 transition ml-auto"><i
                                                        class="fa-solid fa-eraser text-sm"></i></button>
                                            </div>
                                            <div class="w-full px-5 py-4 min-h-[250px] outline-none text-slate-800 prose-sm prose-slate max-w-none"
                                                contenteditable="true" x-init="$el.innerHTML = content"
                                                @input="content = $event.target.innerHTML">
                                            </div>
                                        </div>
                                        <input type="hidden" name="content" x-model="content">

                                        <!-- Paleta de Cores e Color Picker -->
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-slate-800 mb-3 uppercase tracking-wider">{{ __('Cor do Post-It') }}</label>
                                            <div class="flex flex-wrap gap-3 items-center">
                                                @php
                                                    $colors = ['#fef08a', '#fecaca', '#bfdbfe', '#bbf7d0', '#e9d5ff', '#f5d5e8', '#fed7aa', '#99f6e4', '#d9f99d', '#c7d2fe', '#e2e8f0'];
                                                @endphp
                                                @foreach($colors as $hex)
                                                    <button type="button" @click="color = '{{ $hex }}'"
                                                        class="h-10 w-10 rounded-full shadow-sm transition-all duration-200 relative"
                                                        style="background-color: {{ $hex }};"
                                                        :class="color === '{{ $hex }}' ? 'ring-4 ring-black/20 scale-110' : 'hover:scale-110 border border-black/10'">
                                                        <i class="fa-solid fa-check text-black/40 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-sm"
                                                            x-show="color === '{{ $hex }}'"></i>
                                                    </button>
                                                @endforeach

                                                <div class="w-px h-6 bg-black/10 mx-1"></div>

                                                <!-- BOTÃO COR CUSTOMIZADA (NOVO) -->
                                                <div class="relative h-10 w-10 rounded-full shadow-sm border border-black/20 hover:scale-110 transition-all cursor-pointer overflow-hidden flex items-center justify-center bg-white"
                                                    title="Escolher cor personalizada">
                                                    <input type="color" x-model="color"
                                                        class="absolute -top-4 -left-4 w-20 h-20 cursor-pointer opacity-0">
                                                    <i class="fa-solid fa-eye-dropper text-slate-600 pointer-events-none"
                                                        :style="!['#fef08a', '#fecaca', '#bfdbfe', '#bbf7d0', '#e9d5ff', '#f5d5e8', '#fed7aa', '#99f6e4', '#d9f99d', '#c7d2fe', '#e2e8f0'].includes(color) ? `color: ${color}` : ''"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between pt-6 mt-6 border-t border-black/10">
                                            <div class="text-xs font-semibold text-black/40">
                                                <i class="fa-regular fa-clock"></i> Atualizado em
                                                {{ $note->updated_at->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="flex gap-3">
                                                <button type="button" @click="open{{ $note->id }} = false"
                                                    class="px-5 py-2.5 rounded-xl font-bold text-slate-700 bg-white/50 hover:bg-white border border-black/10 transition shadow-sm">
                                                    {{ __('Cancelar') }}
                                                </button>
                                                <button type="submit"
                                                    class="px-6 py-2.5 rounded-xl font-bold text-white bg-slate-900 hover:bg-slate-800 transition shadow-lg hover:shadow-xl flex items-center gap-2">
                                                    <i class="fa-solid fa-floppy-disk"></i> {{ __('Salvar') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- MODAL GLOBAL DE CRIAÇÃO -->
    <div x-data="createNoteForm()"
        @open-create.window="open = true; title = ''; content = ''; color = '#fef08a'; setTimeout(() => { $refs.createEditor.innerHTML = '' }, 50)">
        <template x-teleport="body">
            <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

                <div class="relative w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-500 flex flex-col max-h-[90vh]"
                    :style="`background-color: ${color}`">

                    <div class="flex items-center justify-between px-6 py-4 border-b border-black/10 bg-black/5">
                        <h3 class="font-extrabold text-xl text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-plus text-black/50"></i> {{ __('Nova Anotação') }}
                        </h3>
                        <button @click="open = false"
                            class="h-8 w-8 flex items-center justify-center rounded-full bg-black/5 hover:bg-black/20 text-slate-700 transition">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('notes.store') }}" @submit.prevent="submit()"
                        class="p-6 space-y-6 overflow-y-auto custom-scrollbar flex-1">

                        <div>
                            <input type="text" x-model="title" placeholder="Título da Nota"
                                class="w-full px-0 py-2 border-0 border-b-2 border-black/10 focus:border-black/40 focus:ring-0 bg-transparent text-3xl font-extrabold text-slate-900 placeholder-black/30 transition">
                        </div>

                        <div
                            class="rounded-xl border border-black/10 focus-within:border-black/30 bg-white/80 backdrop-blur-sm shadow-sm overflow-hidden transition flex flex-col">
                            <div
                                class="flex items-center gap-1 p-2 bg-white border-b border-slate-200 flex-wrap shrink-0">
                                <button type="button" @click="document.execCommand('bold', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif font-bold transition">B</button>
                                <button type="button" @click="document.execCommand('italic', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif italic transition">I</button>
                                <button type="button" @click="document.execCommand('underline', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif underline transition">U</button>
                                <button type="button" @click="document.execCommand('strikeThrough', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 font-serif line-through transition">S</button>

                                <div class="w-px h-5 bg-slate-200 mx-1"></div>

                                <!-- BOTÃO COR DO TEXTO (NOVO) -->
                                <div class="relative w-8 h-8 rounded hover:bg-slate-100 flex items-center justify-center transition overflow-hidden"
                                    title="Cor do Texto">
                                    <input type="color"
                                        @input="document.execCommand('foreColor', false, $event.target.value)"
                                        class="absolute -top-2 -left-2 w-12 h-12 cursor-pointer opacity-0">
                                    <i class="fa-solid fa-droplet text-slate-700 pointer-events-none"></i>
                                </div>

                                <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                <button type="button" @click="document.execCommand('insertUnorderedList', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                        class="fa-solid fa-list-ul"></i></button>
                                <button type="button" @click="document.execCommand('insertOrderedList', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                        class="fa-solid fa-list-ol"></i></button>
                                <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                <button type="button" @click="document.execCommand('justifyLeft', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                        class="fa-solid fa-align-left"></i></button>
                                <button type="button" @click="document.execCommand('justifyCenter', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                        class="fa-solid fa-align-center"></i></button>
                                <button type="button" @click="document.execCommand('justifyRight', false, null)"
                                    class="w-8 h-8 rounded hover:bg-slate-100 text-slate-700 transition"><i
                                        class="fa-solid fa-align-right"></i></button>
                                <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                <button type="button" @click="document.execCommand('removeFormat', false, null)"
                                    class="w-8 h-8 rounded hover:bg-rose-50 text-rose-600 transition ml-auto"><i
                                        class="fa-solid fa-eraser text-sm"></i></button>
                            </div>
                            <div class="w-full px-5 py-4 min-h-[250px] outline-none text-slate-800 prose-sm prose-slate max-w-none"
                                contenteditable="true" x-ref="createEditor" @input="content = $event.target.innerHTML">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-bold text-slate-800 mb-3 uppercase tracking-wider">{{ __('Cor do Post-It') }}</label>
                            <div class="flex flex-wrap gap-3 items-center">
                                @php
                                    $colors = ['#fef08a', '#fecaca', '#bfdbfe', '#bbf7d0', '#e9d5ff', '#f5d5e8', '#fed7aa', '#99f6e4', '#d9f99d', '#c7d2fe', '#e2e8f0'];
                                @endphp
                                @foreach($colors as $hex)
                                    <button type="button" @click="color = '{{ $hex }}'"
                                        class="h-10 w-10 rounded-full shadow-sm transition-all duration-200 relative"
                                        style="background-color: {{ $hex }};"
                                        :class="color === '{{ $hex }}' ? 'ring-4 ring-black/20 scale-110' : 'hover:scale-110 border border-black/10'">
                                        <i class="fa-solid fa-check text-black/40 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-sm"
                                            x-show="color === '{{ $hex }}'"></i>
                                    </button>
                                @endforeach

                                <div class="w-px h-6 bg-black/10 mx-1"></div>

                                <!-- BOTÃO COR CUSTOMIZADA (NOVO) -->
                                <div class="relative h-10 w-10 rounded-full shadow-sm border border-black/20 hover:scale-110 transition-all cursor-pointer overflow-hidden flex items-center justify-center bg-white"
                                    title="Escolher cor personalizada">
                                    <input type="color" x-model="color"
                                        class="absolute -top-4 -left-4 w-20 h-20 cursor-pointer opacity-0">
                                    <i class="fa-solid fa-eye-dropper text-slate-600 pointer-events-none"
                                        :style="!['#fef08a', '#fecaca', '#bfdbfe', '#bbf7d0', '#e9d5ff', '#f5d5e8', '#fed7aa', '#99f6e4', '#d9f99d', '#c7d2fe', '#e2e8f0'].includes(color) ? `color: ${color}` : ''"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-6 mt-6 border-t border-black/10 gap-3">
                            <button type="button" @click="open = false"
                                class="px-5 py-2.5 rounded-xl font-bold text-slate-700 bg-white/50 hover:bg-white border border-black/10 transition shadow-sm">
                                {{ __('Cancelar') }}
                            </button>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-bold text-white bg-slate-900 hover:bg-slate-800 transition shadow-lg hover:shadow-xl flex items-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i> {{ __('Criar Anotação') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <!-- Scripts (Sortable e Alpine Models) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notesGrid = document.getElementById('notes-grid');
            if (notesGrid) {
                new Sortable(notesGrid, {
                    animation: 250,
                    handle: '.drag-handle',
                    ghostClass: 'opacity-40',
                    dragClass: 'shadow-2xl',
                    onEnd: function (evt) {
                        console.log(`Nota movida da posição ${evt.oldIndex} para ${evt.newIndex}`);
                    }
                });
            }
        });

        @foreach ($notes as $note)
            function noteForm{{ $note->id }}() {
                return {
                    title: '{{ addslashes($note->title) }}',
                    content: {!! json_encode($note->content ?? '') !!},
                    color: '{{ $note->color ?? '#fef08a' }}',
                    async submit() {
                        try {
                            const res = await fetch('{{ route('notes.update', $note->id) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-HTTP-Method-Override': 'PATCH'
                                },
                                body: JSON.stringify({ title: this.title, content: this.content, color: this.color })
                            });
                            if (res.ok) {
                                this.open{{ $note->id }} = false;
                                location.reload();
                            }
                        } catch (err) {
                            alert('Erro de conexão ao tentar salvar.');
                        }
                    }
                }
            }
        @endforeach

        function createNoteForm() {
            return {
                open: false,
                title: '',
                content: '',
                color: '#fef08a',
                async submit() {
                    try {
                        const res = await fetch('{{ route('notes.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ title: this.title, content: this.content, color: this.color })
                        });
                        if (res.ok) {
                            this.open = false;
                            location.reload();
                        } else {
                            alert('Erro ao criar a nota. Verifique os dados.');
                        }
                    } catch (err) {
                        alert('Erro de conexão ao tentar criar.');
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.2);
        }

        .prose-sm ul {
            list-style-type: disc !important;
            padding-left: 1.5rem !important;
            margin: 0.5rem 0 !important;
        }

        .prose-sm ol {
            list-style-type: decimal !important;
            padding-left: 1.5rem !important;
            margin: 0.5rem 0 !important;
        }

        .prose-sm li {
            display: list-item !important;
        }

        .prose-sm p {
            margin-bottom: 0.75em !important;
        }
    </style>
</x-app-layout>