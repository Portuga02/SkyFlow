<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Auth::user()->notes()->latest('updated_at')->get();

        return view('notes.index', compact('notes'));
    }

    public function store()
    {
        $note = Auth::user()->notes()->create([
            'title'   => 'Nova nota',
            'content' => '',
        ]);

        return to_route('notes.index')->with('open_note', $note->id);
    }

    // Autosave: chamado via fetch() a cada pausa de digitação
    public function update(Request $request, Note $note)
    {
        abort_if($note->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $note->update([
            'title'   => $request->title ?: 'Sem título',
            'content' => $request->content,
        ]);

        return response()->json(['status' => 'saved', 'updated_at' => $note->updated_at->diffForHumans()]);
    }

    public function destroy(Note $note)
    {
        abort_if($note->user_id !== Auth::id(), Response::HTTP_FORBIDDEN);

        $note->delete();

        return back()->with('alert-success', 'Nota excluída!');
    }
}
