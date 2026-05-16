<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    public function index($workspaceId)
    {
        $notes = Note::where('workspace_id', $workspaceId)->get();

        return view('notes.index', compact('notes', 'workspaceId'));
    }

    public function update(Request $request)
    {
        $note = Note::findOrFail($request->note_id);

        $note->content = $request->content;

        $note->save();

        return response()->json($note);
    }
}