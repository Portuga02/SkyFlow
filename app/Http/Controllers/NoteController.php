<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Auth::user()->notes()->latest()->get();
        return view('notes.index', compact('notes'));
    }

    public function store()
    {
        $note = Auth::user()->notes()->create([
            'title'   => 'Nova nota',
            'content' => '',
            'color'   => '#fef08a', // Amarelo padrão
        ]);

        return to_route('notes.index')->with('open_note', $note->id);
    }

    public function update(Note $note)
    {
        abort_if($note->user_id !== Auth::id(), 403);

        $note->update(request()->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'color'   => 'sometimes|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]));

        return response()->json(['success' => true, 'message' => 'Nota atualizada!']);
    }

    public function destroy(Note $note)
    {
        abort_if($note->user_id !== Auth::id(), 403);
        $note->delete();

        return back()->with('alert-success', 'Nota deletada!');
    }
}