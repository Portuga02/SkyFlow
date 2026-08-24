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
        $user = auth()->user();
        $teamId = $user->team_id;

        // 1. Garante que se o usuário/time não tiver colunas, cria as padrões na hora
        $columns = KanbanColumn::where('team_id', $teamId)
            ->orWhere(function ($query) use ($user) {
                $query->whereNull('team_id')->where('user_id', $user->id);
            })
            ->orderBy('order')
            ->with(['todos' => function ($q) {
                $q->orderBy('order', 'asc');
            }])
            ->get();

        // Se estiver vazio (primeiro acesso em prod), cria as colunas padrão
        if ($columns->isEmpty()) {
            $defaultColumns = [
                ['name' => 'A Fazer', 'color' => '#3b82f6', 'order' => 1],
                ['name' => 'Em Andamento', 'color' => '#f59e0b', 'order' => 2],
                ['name' => 'Em Revisão', 'color' => '#8b5cf6', 'order' => 3],
                ['name' => 'Concluído', 'color' => '#10b981', 'order' => 4],
            ];

            foreach ($defaultColumns as $col) {
                KanbanColumn::create([
                    'user_id' => $user->id,
                    'team_id' => $teamId,
                    'name'    => $col['name'],
                    'slug'    => Str::slug($col['name']), // <-- CORREÇÃO: O Slug estava faltando aqui!
                    'color'   => $col['color'],
                    'order'   => $col['order'],
                ]);
            }

            // Recarrega as colunas criadas
            $columns = KanbanColumn::where('team_id', $teamId)
                ->orWhere(function ($query) use ($user) {
                    $query->whereNull('team_id')->where('user_id', $user->id);
                })
                ->orderBy('order')
                ->with('todos')
                ->get();
        }

        // 2. Busca todas as tarefas do usuário para distribuir nas colunas da view
        $todos = Todo::where('user_id', $user->id)
            ->when($teamId, function ($q) use ($teamId) {
                $q->orWhere('team_id', $teamId);
            })
            ->get();

        // 3. Envia o $columns e o $todos
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
            while (\App\Models\KanbanColumn::where('user_id', Auth::id())->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            // O Laravel vai preencher o user_id automaticamente pelo relacionamento
            $column = $request->user()->kanbanColumns()->create([
                'name'    => $request->name,
                'slug'    => $slug,
                'color'   => $request->color,
                'icon'    => $request->icon ?? 'fa-layer-group',
                'team_id' => $request->user()->team_id ?? 1,
                'order'   => $request->user()->kanbanColumns()->count()
            ]);

            return response()->json(['success' => true, 'column' => $column]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function move(Request $request)
    {
        $request->validate([
            'id'               => 'required|exists:todos,id',
            'status'           => 'required|string|max:255',
            'kanban_column_id' => 'nullable|exists:kanban_columns,id', // <-- Validar a coluna
        ]);

        $todo = \App\Models\Todo::findOrFail($request->id);

        $todo->status = $request->status;

        // <-- Salvar a coluna atrelada quando o card for arrastado!
        if ($request->has('kanban_column_id')) {
            $todo->kanban_column_id = $request->kanban_column_id;
        }

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
