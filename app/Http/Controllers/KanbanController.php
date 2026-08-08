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

    public function columns()
    {
        $columns = [
            'todo' => ['label' => 'A Fazer', 'icon' => 'fa-circle-notch', 'color' => '#f59e0b'],
            'in_progress' => ['label' => 'Em Andamento', 'icon' => 'fa-circle-play', 'color' => '#0c8fe6'],
            'done' => ['label' => 'Concluído', 'icon' => 'fa-circle-check', 'color' => '#10b981'],
        ];

        $todos = Auth::user()->todos()->latest()->get()->groupBy('status');

        $data = [];
        foreach ($columns as $key => $col) {
            $data[$key] = [
                'label' => $col['label'],
                'icon' => $col['icon'],
                'color' => $col['color'],
                'todos' => $todos->get($key, collect())->values(),
            ];
        }

        return response()->json($data);
    }

    public function move()
    {
        request()->validate(['id' => 'required|exists:todos,id', 'status' => 'required|in:todo,in_progress,done']);

        $todo = Todo::findOrFail(request('id'));
        abort_if($todo->user_id !== Auth::id(), 403);

        $todo->update(['status' => request('status')]);

        return response()->json(['status' => 'ok']);
    }
}
