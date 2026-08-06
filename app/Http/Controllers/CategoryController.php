<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function index()
    {
        // Traz só as categorias "raiz" já com as subcategorias e contagem de tarefas carregadas
        $categories = Category::with('children.todos')
            ->withCount('todos')
            ->roots()
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        // Só categorias-raiz podem virar "mãe" de uma subcategoria
        $parentOptions = Category::roots()->orderBy('name')->get();

        return view('categories.create', compact('parentOptions'));
    }

    public function store(CategoryRequest $request)
    {
        try {
            Auth::user()->categories()->create($request->validated());

            return to_route('categories.index')
                ->with('alert-success', 'Categoria criada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível criar a categoria.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit(Category $category)
    {
        $parentOptions = Category::roots()
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'parentOptions'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());

            return to_route('categories.index')
                ->with('alert-success', 'Categoria atualizada com sucesso!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível atualizar a categoria.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Category $category)
    {
        try {
            // As subcategorias e tarefas filhas ficam "órfãs" (category_id vira null) por causa do nullOnDelete()
            $category->delete();

            return to_route('categories.index')
                ->with('alert-success', 'Categoria excluída com sucesso!');

        } catch (\Throwable $th) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Não foi possível excluir a categoria.',
                'error'   => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
