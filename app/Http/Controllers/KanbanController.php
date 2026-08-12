<?php

namespace App\Http\Controllers;

use App\Models\KanbanColumn;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class KanbanController extends Controller
{
    public function index()
    {
        // Busca as colunas do usuário logado
        $columns = KanbanColumn::where('user_id', Auth::id())
                    ->orderBy('order')
                    ->get();

        // Se o usuário não tiver colunas, cria as 3 básicas automaticamente
        if ($columns->isEmpty()) {
            $defaultColumns = [
                ['name' => 'A Fazer', 'color' => '#f59e0b', 'slug' => 'todo'],
                ['name' => 'Em Andamento', 'color' => '#3b82f6', 'slug' => 'in_progress'],
                ['name' => 'Concluído', 'color' => '#10b981', 'slug' => 'done'],
            ];

            foreach ($defaultColumns as $index => $col) {
                KanbanColumn::create([
                    'user_id' => Auth::id(),
                    'name'    => $col['name'],
                    'slug'    => $col['slug'],
                    'color'   => $col['color'],
                    'order'   => $index,
                ]);
            }

            $columns = KanbanColumn::where('user_id', Auth::id())->orderBy('order')->get();
        }

        // Busca as tarefas do usuário logado
        $todos = Todo::where('user_id', Auth::id())->get();

        return view('kanban.index', compact('columns', 'todos'));
    }
   public function storeColumn(Request $request)
    {
        $request->validate(array(
            'name'  => 'required|string|max:255',
            'color' => 'required|string',
            'icon'  => 'nullable|string'
        ));

        $column = $request->user()->kanbanColumns()->create(array(
            'name'  => $request->name,
            'slug'  => \Illuminate\Support\Str::slug($request->name),
            'color' => $request->color,
            'icon'  => $request->icon ?? 'fa-layer-group', // Salva o ícone escolhido!
            'order' => $request->user()->kanbanColumns()->count()
        ));

        return response()->json(array('success' => true, 'column' => $column));
    }

    public function move(Request $request)
    {
        try {
            $todo = Todo::findOrFail($request->id);

            if ($todo->user_id !== Auth::id()) {
                return response()->json(['success' => false], 403);
            }

            $todo->update([
                'status' => $request->status
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
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
    public function createColumn(Request $request)
    {
        $request->validate(array(
            'name'  => 'required|string|max:255',
            'color' => 'required|string',
            'icon'  => 'nullable|string' 
        ));

      
        $column = $request->user()->kanbanColumns()->create(array(
            'name'  => $request->name,
            'slug'  => \Illuminate\Support\Str::slug($request->name),
            'color' => $request->color,
            'icon'  => $request->icon ?? 'fa-layer-group',
            'order' => $request->user()->kanbanColumns()->count()
        ));

        return response()->json(array('success' => true, 'column' => $column));
    }
}
