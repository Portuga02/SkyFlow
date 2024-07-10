<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">



                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                @if (count($todoList) > 0)
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            {{ __('Titulo') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            {{ __(' Descrição') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-center">
                                            {{ __(' Status da atividade') }}
                                        </th>

                                        <th scope="col" class="px-6 py-4 text-center">
                                            {{ __('Ações') }}
                                        </th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach ($todoList as $todosLists)
                                    <tr class="bg-white border-b white:bg-gray-800 white:border-gray-700">

                                        <td class="px-6 py-4 text-center">
                                            {{ $todosLists->title }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{ $todosLists->description }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($todosLists->is_completed == 0)
                                                <x-primary-button class="ms-3">
                                                    {{ __(' Completo') }}
                                                </x-primary-button>
                                            @else
                                                <x-secondary-button class="ms-3">
                                                    {{ __(' Pendente') }}
                                                </x-secondary-button>
                                            @endif
                                        </td>

                                        <td class="px-7 py-8 text-center" id="outros">
                                            <button class="bg-green-500  text-white font-bold py-2 px-4 rounded">

                                                <a href="{{ route('todoEdit', $todosLists->id) }}"
                                                    class="group inline-flex items-center hover:text-gray-700 dark:hover:text-black "
                                                    alt="Editar Lista" title="Editar Lista">
                                                    <i class="fa-solid fa-user-pen fa-lg"></i>
                                                </a>
                                            </button>

                                            <button
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
                                                <a href="{{ route('showTodo', $todosLists->id) }}"
                                                    class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white"
                                                    alt="Editar Usuário" title="Editar Usuário">
                                                    <i class="fa-solid fa-file-signature fa-lg"></i>
                                                </a>
                                            </button>

                                            <form method="POST" action="{{ route('todoDestroy') }}"
                                                class=" inline-flex items-center">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="todo_id" value="{{ $todosLists->id }}" />
                                                <button type='submit' alt="Excluir Tarefa"
                                                    class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                                    title="Deletar Tarefa">
                                                    <i class="fa-solid fa-xmark fa-lg"></i>

                                                </button>

                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>


                    </div>
                @else
                    <h4> {{ __(' Não existem usuários com listas de tarefas') }} </h4>
                    @endif
                </div>


            </div>

        </div>
    </div>
    </div>
</x-app-layout>
