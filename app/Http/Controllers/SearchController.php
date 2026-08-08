<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function global()
    {
        $query = request('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = Todo::where('user_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%");
            })
            ->with('category')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($todo) {
                return [
                    'id' => $todo->id,
                    'title' => $todo->title,
                    'description' => $todo->description,
                    'category' => $todo->category?->name,
                    'category_color' => $todo->category?->color,
                    'priority' => $todo->priority,
                    'is_completed' => $todo->is_completed,
                    'url' => route('todos.show', $todo->id),
                ];
            });

        return response()->json($results);
    }
}
