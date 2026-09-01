<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
       
        $todos = Todo::where('user_id', auth()->id())
                     ->whereNotNull('due_date')
                     ->with('category') 
                     ->get();
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
        $request->validate([
            'id'       => 'required|exists:todos,id',
            'due_date' => 'required|date'
        ]);

    
        \App\Models\Todo::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->update([
                'due_date' => $request->due_date
            ]);

        return response()->json(['success' => true]);
    }

}
