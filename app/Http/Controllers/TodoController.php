<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
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
        return view('auth.create-todo');
    }

    public function store(TodoRequest $request)
    {
        try {
            // Criação limpa pegando apenas os dados validados pelo TodoRequest
            Todo::create([
                ...$request->validated(),
                'is_completed' => false,
            ]);

            return to_route('todos.index')
                ->with('alert-success', 'Atividade criada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível criar a atividade.',
                'error'   => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // O Laravel já injeta o $todo automaticamente por causa da URL /{todo}
    public function show(Todo $todo)
    {
        try {
            return view('auth.showTodo', compact('todo'));

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível carregar os dados.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Mudei o nome de 'editTodo' para 'edit' (Padrão absoluto do mercado)
    public function edit(Todo $todo)
    {
        try {
            return view('auth.edit-todo', compact('todo'));

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Erro ao abrir a tela de edição.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível atualizar a atividade.',
                'error'   => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Alterna rapidamente o status concluído/pendente direto na listagem
    public function toggle(Todo $todo)
    {
        try {
            $todo->update([
                'is_completed' => $todo->is_completed ? 0 : 1,
            ]);

            return back()->with('alert-success', 'Status da atividade atualizado!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível atualizar o status.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Todo $todo)
    {
        try {
            $todo->delete();

            return to_route('todos.index')
                ->with('alert-success', 'Atividade deletada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível deletar o item.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
