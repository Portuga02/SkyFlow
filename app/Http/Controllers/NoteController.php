<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Auth::user()->notes()->latest()->get();
        return view('notes.index', compact('notes'));
    }
    public function store(Request $request)
    {
        // Se a requisição vier vazia (botão Nova Nota), ele cria um amarelinho padrão
        $request->user()->notes()->create([
            'title'   => $request->title ?? 'Nova nota',
            'content' => $request->content ?? 'Clique para editar...',
            'color'   => $request->color ?? '#fef08a', // Cor padrão amarela
        ]);

        // Se for JSON (veio pelo Javascript do Dashboard), devolve JSON
        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function update(Request $request, Note $note)
    {
        // Garante que a nota é do cara logado
        abort_if($note->user_id !== $request->user()->id, 403);

        $note->update([
            'title'   => $request->title,
            'content' => $request->content,
            'color'   => $request->color, // <-- A cor sendo salva aqui!
        ]);

        return response()->json(['success' => true]);
    }



    public function destroy(Note $note)
    {
        abort_if($note->user_id !== Auth::id(), 403);
        $note->delete();

        return back()->with('alert-success', 'Nota deletada!');
    }
}
