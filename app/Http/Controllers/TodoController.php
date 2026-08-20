<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $todoList = Todo::with(['category', 'assignedUsers'])
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                ->orWhereHas('assignedUsers', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            })
            ->latest()
            ->get();

        return view('auth.todo', compact('todoList'));
    }

    public function create()
    {
        $users = User::where('team_id', Auth::user()->team_id)
            ->orderBy('name')
            ->get();

        return view('auth.create-todo', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|string',
            'due_date'    => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
        ]);

        $todo = Todo::create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority'    => $validated['priority'],
            'due_date'    => $validated['due_date'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'user_id'     => Auth::id(),
        ]);
        if (!empty($validated['assigned_to'])) {
            $todo->assignedUsers()->sync($validated['assigned_to']);
        }

        return redirect()->route('todos.index')->with('alert-success', 'Tarefa criada com sucesso!');
    }

    public function show(Todo $todo)
    {
        $this->authorizeTodoAccess($todo);

        try {
         
            $users = User::where('team_id', Auth::user()->team_id)
                         ->orderBy('name')
                         ->get();

            return view('auth.showTodo', compact('todo', 'users'));
        } catch (\Throwable $th) {
            return response()->json(array(
                'status'  => 'error',
                'message' => 'Não foi possível carregar os dados.',
                'error'   => $th->getMessage()
            ), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function edit(Todo $todo)
    {
        $this->authorizeTodoAccess($todo);

        try {

            $users = User::where('team_id', Auth::user()->team_id)
                         ->orderBy('name')
                         ->get();

            return view('auth.edit-todo', compact('todo', 'users'));
        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Erro ao abrir a tela de edição.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(Request $request, Todo $todo)
    {
        $this->authorizeTodoAccess($todo);

        $validated = $request->validate(array(
                'title'        => 'required|string|max:255',
                'description'  => 'nullable|string',
                'priority'     => 'required|string',
                'due_date'     => 'nullable|date',
                'category_id'  => 'nullable|exists:categories,id',
                'is_completed' => 'required|boolean',
                'assigned_to'  => 'nullable|array',
                // TRAVA DE SEGURANÇA NA EDIÇÃO
                'assigned_to.*' => 'exists:users,id,team_id,' . Auth::user()->team_id,
            ));

        $todo->update([
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'priority'     => $validated['priority'],
            'due_date'     => $validated['due_date'] ?? null,
            'category_id'  => $validated['category_id'] ?? null,
            'is_completed' => $validated['is_completed'],
        ]);

        // Atualiza a lista de responsáveis na tabela pivot
        $todo->assignedUsers()->sync($request->input('assigned_to', []));

        return redirect()->route('todos.index')->with('alert-success', 'Tarefa atualizada com sucesso!');
    }

    public function toggle(Todo $todo)
    {
        $this->authorizeTodoAccess($todo);

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
        $this->authorizeTodoAccess($todo);

        try {
            $todo->delete();

            return redirect()->route('todos.index')
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
        $this->authorizeTodoAccess($todo);

        $request->validate(['text' => 'required|string|max:255']);

        $checklist = $todo->checklist ?? [];
        $checklist[] = ['text' => $request->text, 'done' => false];

        $todo->update(['checklist' => $checklist]);

        return back()->with('alert-success', 'Item adicionado ao checklist!');
    }

    public function checklistToggle(Todo $todo, int $index)
    {
        $this->authorizeTodoAccess($todo);

        $checklist = $todo->checklist ?? [];

        if (isset($checklist[$index])) {
            $checklist[$index]['done'] = !($checklist[$index]['done'] ?? false);
            $todo->update(['checklist' => $checklist]);
        }

        return back();
    }

    public function checklistDestroy(Todo $todo, int $index)
    {
        $this->authorizeTodoAccess($todo);

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
        $this->authorizeTodoAccess($todo);

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
        $this->authorizeTodoAccess($todo);

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
        $this->authorizeTodoAccess($todo);

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
        $this->authorizeTodoAccess($todo);

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
        $this->authorizeTodoAccess($todo);

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

    /**
     * Valida se o usuário logado é o autor ou faz parte da equipe da tarefa.
     */
    private function authorizeTodoAccess(Todo $todo): void
    {
        $userId = Auth::id();

        // É o autor da tarefa?
        $isAuthor = $todo->user_id === $userId;

        // Faz parte dos responsáveis atribuídos à tarefa?
        $isAssigned = $todo->assignedUsers()->where('users.id', $userId)->exists();

        // Se não for o autor nem estiver na equipe, bloqueia o acesso
        abort_if(!$isAuthor && !$isAssigned, Response::HTTP_FORBIDDEN);
    }
    public function storeTeamMember(Request $request)
    {
        // Valida os dados que você digitou
        $validated = $request->validate(array(
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ));

        // Cria o usuário já amarrado à sua equipe
        User::create(array(
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            // A mágica acontece aqui: ele herda o seu ID de equipe!
            'team_id'  => Auth::user()->team_id,
        ));

        return back()->with('alert-success', 'Membro da equipe criado com sucesso!');
    }
}
