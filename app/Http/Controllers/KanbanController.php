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

    // AQUI ESTÁ O MÉTODO STORECOLUMN! 👇
    // Ele é o responsável por receber os dados do seu Modal e salvar no banco
    public function storeColumn(Request $request)
    {
        try {
            $request->validate([
                'name'  => 'required|string|max:255',
                'color' => 'required|string|max:10',
            ]);

            // Transforma o nome digitado em um slug (ex: "Em Revisão" vira "em-revisao")
            $slug = Str::slug($request->name);

            // Evita colunas com slugs repetidos para o mesmo usuário
            $count = KanbanColumn::where('user_id', Auth::id())
                        ->where('slug', 'LIKE', "{$slug}%")
                        ->count();
            if ($count > 0) {
                $slug = $slug . '-' . $count;
            }

            // Descobre a ordem da última coluna para colocar a nova no final
            $maxOrder = KanbanColumn::where('user_id', Auth::id())->max('order');

            KanbanColumn::create([
                'user_id' => Auth::id(),
                'name'    => $request->name,
                'slug'    => $slug,
                'color'   => $request->color,
                'order'   => $maxOrder !== null ? $maxOrder + 1 : 0,
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Erro interno ao salvar coluna.'
            ], 500);
        }
    }

    // O método que move os cards de uma coluna para outra
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
    // O método que apaga a coluna do banco de dados
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
