<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    public function index()
    {
        $todos = Auth::user()->todos()->latest()->get();
        return view('kanban.index', compact('todos'));
    }

    public function move()
    {
        try {
            $validated = request()->validate([
                'id' => 'required|exists:todos,id',
                'status' => 'required|in:todo,in_progress,done'
            ]);

            $todo = Todo::findOrFail($validated['id']);
            abort_if($todo->user_id !== Auth::id(), 403);

            $todo->update(['status' => $validated['status']]);

            return response()->json(['success' => true, 'message' => 'Card movido com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function columns()
    {
        $user = Auth::user();
        $customColumns = $user->kanban_columns ?? [
            'todo' => ['label' => 'A Fazer', 'icon' => 'fa-circle-notch', 'color' => '#f59e0b'],
            'in_progress' => ['label' => 'Em Andamento', 'icon' => 'fa-circle-play', 'color' => '#0c8fe6'],
            'done' => ['label' => 'Concluído', 'icon' => 'fa-circle-check', 'color' => '#10b981'],
        ];

        return response()->json($customColumns);
    }

    public function createColumn()
    {
        $validated = request()->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $user = Auth::user();
        $columns = $user->kanban_columns ?? [];
        
        $columnKey = strtolower(str_replace(' ', '_', $validated['name']));
        $columns[$columnKey] = [
            'label' => $validated['name'],
            'icon' => 'fa-circle',
            'color' => $validated['color']
        ];

        $user->update(['kanban_columns' => $columns]);

        return response()->json(['success' => true, 'columnKey' => $columnKey, 'column' => $columns[$columnKey]]);
    }

    public function deleteColumn()
    {
        $validated = request()->validate(['columnKey' => 'required|string']);

        $user = Auth::user();
        $columns = $user->kanban_columns ?? [];
        
        if (isset($columns[$validated['columnKey']])) {
            unset($columns[$validated['columnKey']]);
            $user->update(['kanban_columns' => $columns]);
        }

        return response()->json(['success' => true]);
    }
}