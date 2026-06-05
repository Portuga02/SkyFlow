<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        {{ __('ID') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        {{ __('Nome') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        {{ __('E-mail') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center">
                                        {{ __('Ações') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
                                        {{ Auth::user()->id }}
                                    </th>
                                    <td class="px-6 py-4 text-center">
                                        {{ Auth::user()->name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ Auth::user()->email }}
                                    </td>

                                    <!-- Coluna de Ações com Flexbox para alinhar os botões -->
                                    <td class="px-6 py-4 text-center flex justify-center items-center space-x-3">

                                        <!-- Botão: Editar Perfil -->
                                        <a href="{{ route('profile.edit') }}"
                                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150"
                                           title="Ver meu perfil">
                                            <i class="fa-solid fa-circle-user fa-xl"></i>
                                        </a>

                                        <!-- Botão: Lista de Tarefas (Ajustado para todos.index) -->
                                        <a href="{{ route('todos.index') }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150"
                                           title="Ir para suas listas de tarefas">
                                            <i class="fa-solid fa-file-signature"></i>
                                        </a>

                                        <!-- Botão: Deletar Usuário (Ajustado para Formulário) -->
                                        <form action="{{ route('profile.destroy') }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150"
                                                    title="Excluir este usuário"
                                                    onclick="return confirm('Tem certeza que deseja excluir sua conta?');">
                                                <i class="fa-solid fa-xmark fa-sm"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
