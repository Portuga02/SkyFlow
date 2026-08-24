<?php

namespace App\Http\Controllers;

use App\Models\KanbanColumn;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KanbanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        $columns = \App\Models\KanbanColumn::where('user_id', $userId)
            ->orWhereNull('user_id')
            ->orderBy('id')
            ->get();

        $todos = \App\Models\Todo::where(function ($query) use ($userId, $user) {
            $query->where('user_id', $userId)
                  ->orWhereHas('assignedUsers', function ($q) use ($userId) {
                      $q->where('users.id', $userId);
                  });
        })
            ->get()
            ->map(function ($todo) {
                // Normaliza o status caso esteja vazio no banco
                if (empty($todo->status)) {
                    $todo->status = $todo->is_completed ? 'concluido' : 'a-fazer';
                }
                return $todo;
            });

        return view('kanban.index', compact('columns', 'todos'));
    }

    public function storeColumn(Request $request)
    {
        try {
            $request->validate([
                'name'  => 'required|string|max:255',
                'color' => 'required|string',
                'icon'  => 'nullable|string'
            ]);

            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug;

            // Evita duplicação de slugs para o mesmo usuário
            $count = 1;
            while (KanbanColumn::where('user_id', Auth::id())->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            $column = $request->user()->kanbanColumns()->create([
                'name'  => $request->name,
                'slug'  => $slug,
                'color' => $request->color,
                'icon'  => $request->icon ?? 'fa-layer-group',
                'order' => $request->user()->kanbanColumns()->count()
            ]);

            return response()->json(['success' => true, 'column' => $column]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function move(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:todos,id',
            'status' => 'required|string|max:255',
        ]);

        $todo = \App\Models\Todo::findOrFail($request->id);

        $todo->status = $request->status;

        if (in_array($request->status, ['concluido', 'done', 'completo'])) {
            $todo->is_completed = true;
        } else {
            $todo->is_completed = false;
        }

        $todo->save();

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!'
        ]);
    }

    public function deleteColumn($columnKey)
    {
        try {
            $column = KanbanColumn::where('user_id', Auth::id())->findOrFail($columnKey);
            $column->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Não foi possível excluir a coluna.'
            ], 500);
        }
    }
}
