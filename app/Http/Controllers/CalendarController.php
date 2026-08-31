<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        // Pega todas as tarefas do usuário logado (que tenham data) e carrega as categorias
        $todos = Todo::where('user_id', auth()->id())
                     ->whereNotNull('due_date')
                     ->with('category') // Carrega a cor e nome da categoria
                     ->get();

        // Envia as tarefas para a view do calendário
        return view('calendar.index', compact('todos'));
    }


    public function events()
    {
        $todos = Auth::user()->todos()
            ->whereNotNull('due_date')
            ->latest('due_date')
            ->get();

        $events = $todos->map(function ($todo) {
            return [
                'id' => $todo->id,
                'title' => $todo->title,
                'start' => $todo->due_date->toDateString(),
                'backgroundColor' => $todo->category?->color ?? '#0c8fe6',
                'borderColor' => $todo->category?->color ?? '#0c8fe6',
                'textColor' => '#fff',
                'classNames' => $todo->is_completed ? 'completed' : '',
                'extendedProps' => [
                    'category' => $todo->category?->name,
                    'priority' => $todo->priority,
                    'completed' => $todo->is_completed,
                    'description' => $todo->description,
                ],
            ];
        });

        return response()->json($events);
    }

    public function reschedule(\Illuminate\Http\Request $request)
    {
        // Valida se mandou o ID e a data certa
        $request->validate([
            'id'       => 'required|exists:todos,id',
            'due_date' => 'required|date'
        ]);

        // Acha a tarefa garantindo que é do usuário logado e atualiza só a data
        \App\Models\Todo::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->update([
                'due_date' => $request->due_date
            ]);

        return response()->json(['success' => true]);
    }

}
