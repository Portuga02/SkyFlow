<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-2xl text-brand-950 leading-tight">
                {{ __('Detalhes da Tarefa') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-card border border-brand-50 overflow-hidden">
                <div class="p-6 space-y-6">

                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wide text-brand-400">{{ __('Título da tarefa') }}</span>
                            <h3 class="mt-1 text-xl font-bold text-brand-950">{{ $todo->title }}</h3>
                        </div>

                        @if ($todo->is_completed)
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                <i class="fa-solid fa-circle-check"></i> {{ __('Completo') }}
                            </span>
                        @else
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">
                                <i class="fa-solid fa-hourglass-half"></i> {{ __('Pendente') }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wide text-brand-400">{{ __('Descrição da tarefa') }}</span>
                        <p class="mt-1 text-gray-700 leading-relaxed">{{ $todo->description }}</p>
                    </div>

                    <div class="text-xs text-gray-400 flex items-center gap-4 pt-2 border-t border-brand-50">
                        <span><i class="fa-regular fa-clock mr-1"></i>{{ __('Criada em') }} {{ $todo->created_at->format('d/m/Y H:i') }}</span>
                        <span><i class="fa-solid fa-rotate mr-1"></i>{{ __('Atualizada em') }} {{ $todo->updated_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('todos.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 transition">
                            <i class="fa-solid fa-arrow-rotate-left"></i> {{ __('Voltar') }}
                        </a>
                        <a href="{{ route('todos.edit', $todo->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-soft transition">
                            <i class="fa-solid fa-pen"></i> {{ __('Editar tarefa') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
