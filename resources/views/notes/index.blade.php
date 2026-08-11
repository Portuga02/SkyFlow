<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950">{{ __('Bloco de Notas') }}</h2>
                <p class="text-sm text-brand-600 mt-1">{{ __('Suas anotações rápidas e coloridas.') }}</p>
            </div>
            <form method="POST" action="{{ route('notes.store') }}" class="flex-shrink-0">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                    <i class="fa-solid fa-circle-plus"></i> {{ __('Nova Nota') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if ($notes->isEmpty())
            <div class="text-center py-12">
                <i class="fa-solid fa-note-sticky text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">{{ __('Nenhuma nota ainda. Crie uma!') }}</p>
            </div>
        @else
            <!-- Grid de Post-Its -->
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                @foreach ($notes as $note)
                    <div class="break-inside-avoid bg-white rounded-lg shadow-card border border-brand-50 overflow-hidden hover:shadow-lg transition cursor-pointer group"
                        style="background-color: {{ $note->color }}; opacity: 0.95;"
                        @click="open{{ $note->id }} = true"
                        x-data="{ open{{ $note->id }}: false }">

                        <!-- Card View (Closed) -->
                        <div class="p-4 min-h-32 flex flex-col" x-show="!open{{ $note->id }}">
                            <h3 class="font-bold text-sm text-gray-900 line-clamp-2 mb-2">{{ $note->title ?: 'Sem título' }}</h3>
                            <p class="text-xs text-gray-700 line-clamp-4 flex-1">{{ $note->content ?: 'Clique para editar...' }}</p>
                            <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition">
                                <button @click.stop="open{{ $note->id }} = true"
                                    class="flex-1 py-1.5 text-xs font-semibold bg-black/10 hover:bg-black/20 rounded text-gray-900 transition">
                                    Editar
                                </button>
                                <form method="POST" action="{{ route('notes.destroy', $note->id) }}" class="flex-1" @click.stop>
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full py-1.5 text-xs font-semibold bg-red-500/20 hover:bg-red-500/30 rounded text-red-900 transition">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Modal (Open) -->
                        <div x-show="open{{ $note->id }}" @click.stop @click.outside="open{{ $note->id }} = false"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-transition>

                            <div class="w-full max-w-2xl rounded-xl shadow-lg overflow-hidden" @click.stop
                                :style="`background-color: {{ $note->color }}`">

                                <!-- Header -->
                                <div class="flex items-center justify-between p-4 border-b border-black/10 bg-black/5">
                                    <h3 class="font-bold text-gray-900">{{ __('Editar Nota') }}</h3>
                                    <button @click="open{{ $note->id }} = false" class="text-gray-600 hover:text-gray-900">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>

                                <!-- Form -->
                                <form method="POST" action="{{ route('notes.update', $note->id) }}" x-data="noteForm{{ $note->id }}()" @submit.prevent="submit()" class="p-6 space-y-4">
                                    @csrf @method('PATCH')

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Título') }}</label>
                                        <input type="text" name="title" x-model="title"
                                            class="w-full px-3 py-2 rounded-lg border border-black/10 focus:border-black/30 outline-none bg-white/90 text-gray-900">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Conteúdo') }}</label>
                                        <textarea name="content" x-model="content" rows="6"
                                            class="w-full px-3 py-2 rounded-lg border border-black/10 focus:border-black/30 outline-none bg-white/90 text-gray-900 resize-none"></textarea>
                                    </div>

                                    <!-- Color Picker -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">{{ __('Cor do Post-It') }}</label>
                                        <div class="flex items-center gap-3">
                                            <input type="color" name="color" x-model="color" class="h-10 w-16 rounded-lg cursor-pointer border border-black/10">
                                            <div class="flex gap-2">
                                                <button type="button" @click="color = '#fef08a'" class="h-8 w-8 rounded-lg bg-yellow-200 border-2 border-gray-300 hover:border-gray-900" title="Amarelo"></button>
                                                <button type="button" @click="color = '#fecaca'" class="h-8 w-8 rounded-lg bg-red-200 border-2 border-gray-300 hover:border-gray-900" title="Vermelho"></button>
                                                <button type="button" @click="color = '#bfdbfe'" class="h-8 w-8 rounded-lg bg-blue-200 border-2 border-gray-300 hover:border-gray-900" title="Azul"></button>
                                                <button type="button" @click="color = '#bbf7d0'" class="h-8 w-8 rounded-lg bg-green-200 border-2 border-gray-300 hover:border-gray-900" title="Verde"></button>
                                                <button type="button" @click="color = '#e9d5ff'" class="h-8 w-8 rounded-lg bg-purple-200 border-2 border-gray-300 hover:border-gray-900" title="Roxo"></button>
                                                <button type="button" @click="color = '#f5d5e8'" class="h-8 w-8 rounded-lg bg-pink-200 border-2 border-gray-300 hover:border-gray-900" title="Rosa"></button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-3 justify-end pt-4 border-t border-black/10">
                                        <button type="button" @click="open{{ $note->id }} = false"
                                            class="px-4 py-2 rounded-lg font-semibold text-gray-900 bg-black/10 hover:bg-black/20 transition">
                                            {{ __('Cancelar') }}
                                        </button>
                                        <button type="submit"
                                            class="px-4 py-2 rounded-lg font-semibold text-white bg-brand-600 hover:bg-brand-700 transition">
                                            {{ __('Salvar') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        @foreach ($notes as $note)
            function noteForm{{ $note->id }}() {
                return {
                    title: '{{ $note->title }}',
                    content: '{{ addslashes($note->content) }}',
                    color: '{{ $note->color }}',
                    async submit() {
                        try {
                            const res = await fetch('{{ route('notes.update', $note->id) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-HTTP-Method-Override': 'PATCH'
                                },
                                body: JSON.stringify({
                                    title: this.title,
                                    content: this.content,
                                    color: this.color
                                })
                            });
                            if (res.ok) {
                                open{{ $note->id }} = false;
                                location.reload();
                            }
                        } catch (err) {
                            alert('Erro ao salvar nota');
                        }
                    }
                }
            }
        @endforeach
    </script>
</x-app-layout>