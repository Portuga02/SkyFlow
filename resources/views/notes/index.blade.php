<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">{{ __('Bloco de Notas') }}</h2>
                <p class="text-sm text-brand-600 mt-1">{{ __('Suas anotações rápidas, salvas automaticamente.') }}</p>
            </div>
            <form method="POST" action="{{ route('notes.store') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg shadow-soft transition">
                    <i class="fa-solid fa-circle-plus"></i> {{ __('Nova Nota') }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10" x-data="notesApp()">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if ($notes->isEmpty())
                <div class="bg-white rounded-2xl shadow-card border border-brand-50 py-16 px-6 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-100 text-brand-500">
                        <i class="fa-solid fa-note-sticky text-2xl"></i>
                    </div>
                    <h4 class="mt-4 text-lg font-bold text-brand-950">{{ __('Nenhuma nota ainda') }}</h4>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Crie sua primeira anotação rápida.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($notes as $note)
                        <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden flex flex-col" x-data="{ id: {{ $note->id }} }">
                            <div class="flex items-center justify-between px-4 pt-4">
                                <input type="text" x-ref="title_{{ $note->id }}" value="{{ $note->title }}"
                                    @input.debounce.700ms="save({{ $note->id }}, $refs['title_{{ $note->id }}'].value, $refs['content_{{ $note->id }}'].value)"
                                    class="font-bold text-brand-950 text-sm border-none focus:ring-0 p-0 w-full bg-transparent" />

                                <form method="POST" action="{{ route('notes.destroy', $note->id) }}"
                                    onsubmit="return confirm('{{ __('Excluir esta nota?') }}');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-rose-500 shrink-0 ml-2"><i class="fa-solid fa-trash text-xs"></i></button>
                                </form>
                            </div>

                            <textarea x-ref="content_{{ $note->id }}" rows="6"
                                @input.debounce.700ms="save({{ $note->id }}, $refs['title_{{ $note->id }}'].value, $refs['content_{{ $note->id }}'].value)"
                                placeholder="{{ __('Escreva aqui...') }}"
                                class="flex-1 border-none focus:ring-0 text-sm text-gray-700 p-4 resize-none bg-transparent">{{ $note->content }}</textarea>

                            <div class="px-4 pb-3 flex items-center justify-between text-[11px] text-gray-400">
                                <span x-text="status[id] ?? '{{ __('Atualizada') }} {{ $note->updated_at->diffForHumans() }}'"></span>
                                <i class="fa-solid fa-circle-check text-emerald-400" x-show="status[id] === 'Salvo!'" x-cloak></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function notesApp() {
            return {
                status: {},
                save(id, title, content) {
                    this.status[id] = 'Salvando...';
                    fetch(`/notes/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ title, content }),
                    })
                    .then(r => r.json())
                    .then(() => { this.status[id] = 'Salvo!'; setTimeout(() => this.status[id] = null, 1500); })
                    .catch(() => { this.status[id] = 'Erro ao salvar'; });
                }
            }
        }
    </script>
</x-app-layout>
