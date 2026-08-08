<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
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

    public function reschedule()
    {
        request()->validate(['id' => 'required|exists:todos,id', 'date' => 'required|date']);

        $todo = Todo::findOrFail(request('id'));
        abort_if($todo->user_id !== Auth::id(), 403);

        $todo->update(['due_date' => request('date')]);

        return response()->json(['status' => 'ok']);
    }
}
