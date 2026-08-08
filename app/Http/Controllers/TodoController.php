<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    public function index()
    {
        $todoList = Auth::user()->todos()->latest()->get();

        return view('auth.todo', compact('todoList'));
    }

    public function create()
    {
        $users = \App\Models\User::orderBy('name')->get();

        return view('auth.create-todo', compact('users'));
    }

    public function store(TodoRequest $request)
    {
        try {
            Auth::user()->todos()->create([
                ...$request->validated(),
                'is_completed' => false,
                'status' => 'todo',
            ]);

            return to_route('todos.index')
                ->with('alert-success', 'Atividade criada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível criar a atividade.',
                'error'   => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        try {
            $users = \App\Models\User::orderBy('name')->get();

            return view('auth.showTodo', compact('todo', 'users'));

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível carregar os dados.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        try {
            $users = \App\Models\User::orderBy('name')->get();

            return view('auth.edit-todo', compact('todo', 'users'));

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
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        try {
            $todo->update($request->validated());

            return to_route('todos.show', $todo->id)
                ->with('alert-success', 'Atividade atualizada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível atualizar a atividade.',
                'error'   => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function toggle(Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        try {
            $todo->update(['is_completed' => $todo->is_completed ? 0 : 1]);

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
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
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

    // Checklist
    public function checklistStore(Request $request, Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $request->validate(['text' => 'required|string|max:255']);

        $checklist = $todo->checklist ?? [];
        $checklist[] = ['text' => $request->text, 'done' => false];

        $todo->update(['checklist' => $checklist]);

        return back()->with('alert-success', 'Item adicionado ao checklist!');
    }

    public function checklistToggle(Todo $todo, int $index)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $checklist = $todo->checklist ?? [];

        if (isset($checklist[$index])) {
            $checklist[$index]['done'] = !($checklist[$index]['done'] ?? false);
            $todo->update(['checklist' => $checklist]);
        }

        return back();
    }

    public function checklistDestroy(Todo $todo, int $index)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $checklist = $todo->checklist ?? [];

        if (isset($checklist[$index])) {
            unset($checklist[$index]);
            $todo->update(['checklist' => array_values($checklist)]);
        }

        return back()->with('alert-success', 'Item removido do checklist!');
    }

    // Comentários
    public function commentStore(Request $request, Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $request->validate(['body' => 'required|string|max:1000']);

        $comments = $todo->comments ?? [];
        $comments[] = [
            'user' => Auth::user()->name,
            'body' => $request->body,
            'at'   => now()->toDateTimeString(),
        ];

        $todo->update(['comments' => $comments]);

        return back()->with('alert-success', 'Comentário adicionado!');
    }

    // Anexos
    public function attachmentStore(Request $request, Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $request->validate(['file' => 'required|file|max:10240']);

        $path = $request->file('file')->store('attachments', 'public');

        $attachments = $todo->attachments ?? [];
        $attachments[] = [
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
        ];

        $todo->update(['attachments' => $attachments]);

        return back()->with('alert-success', 'Anexo enviado com sucesso!');
    }

    public function attachmentDestroy(Todo $todo, int $index)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $attachments = $todo->attachments ?? [];

        if (isset($attachments[$index])) {
            Storage::disk('public')->delete($attachments[$index]['path']);
            unset($attachments[$index]);
            $todo->update(['attachments' => array_values($attachments)]);
        }

        return back()->with('alert-success', 'Anexo removido!');
    }

    // Etiquetas
    public function labelStore(Request $request, Todo $todo)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $request->validate([
            'name'  => 'required|string|max:30',
            'color' => 'required|string|max:7',
        ]);

        $labels = $todo->labels ?? [];
        $labels[] = ['name' => $request->name, 'color' => $request->color];

        $todo->update(['labels' => $labels]);

        return back()->with('alert-success', 'Etiqueta adicionada!');
    }

    public function labelDestroy(Todo $todo, int $index)
    {
        abort_if($todo->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);
        
        $labels = $todo->labels ?? [];

        if (isset($labels[$index])) {
            unset($labels[$index]);
            $todo->update(['labels' => array_values($labels)]);
        }

        return back()->with('alert-success', 'Etiqueta removida!');
    }

    public function toggleViewMode()
    {
        return redirect()->route('kanban.index');
    }
}
