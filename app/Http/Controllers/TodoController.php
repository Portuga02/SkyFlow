<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use App\Models\Category; // Importando o model de Categorias
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    public function index()
    {
        // Usar latest() garante que as tarefas mais novas apareçam primeiro
        $todoList = Todo::latest()->get();

        return view('auth.todo', compact('todoList'));
    }

    public function create()
    {
        // Buscando todas as categorias para preencher o select no form
        $categories = Category::all();
        return view('auth.create-todo', compact('categories'));
    }

    public function store(TodoRequest $request)
    {
        try {
            // Unindo os dados validados com o status inicial usando array_merge e sintaxe array()
            $data = array_merge($request->validated(), array('is_completed' => false));
            
            Todo::create($data);

            return to_route('todos.index')
                ->with('alert-success', 'Atividade criada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json(array(
                'status'  => 'error',
                'message' => 'Não foi possível criar a atividade.',
                'error'   => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine()
            ), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // O Laravel já injeta o $todo automaticamente por causa da URL /{todo}
    public function show(Todo $todo)
    {
        try {
            return view('auth.showTodo', compact('todo'));

        } catch (\Throwable $th) {
            return response()->json(array(
                'status'  => 'error',
                'message' => 'Não foi possível carregar os dados.',
                'error'   => $th->getMessage()
            ), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mudei o nome de 'editTodo' para 'edit' (Padrão absoluto do mercado)
    public function edit(Todo $todo)
    {
        try {
            // Buscando as categorias para exibir no select de edição
            $categories = Category::all();
            return view('auth.edit-todo', compact('todo', 'categories'));

        } catch (\Throwable $th) {
            return response()->json(array(
                'status'  => 'error',
                'message' => 'Erro ao abrir a tela de edição.',
                'error'   => $th->getMessage()
            ), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(TodoRequest $request, Todo $todo)
    {
        try {
            // Atualiza direto no modelo injetado
            $todo->update($request->validated());

            return to_route('todos.show', $todo->id)
                ->with('alert-success', 'Atividade atualizada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json(array(
                'status'  => 'error',
                'message' => 'Não foi possível atualizar a atividade.',
                'error'   => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine()
            ), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Alterna rapidamente o status concluído/pendente direto na listagem
    public function toggle(Todo $todo)
    {
        try {
            $todo->update(array(
                'is_completed' => $todo->is_completed ? 0 : 1,
            ));

            return back()->with('alert-success', 'Status da atividade atualizado!');

        } catch (\Throwable $th) {
            return response()->json(array(
                'status'  => 'error',
                'message' => 'Não foi possível atualizar o status.',
                'error'   => $th->getMessage()
            ), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Todo $todo)
    {
        try {
            $todo->delete();

            return to_route('todos.index')
                ->with('alert-success', 'Atividade deletada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json(array(
                'status'  => 'error',
                'message' => 'Não foi possível deletar o item.',
                'error'   => $th->getMessage()
            ), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}