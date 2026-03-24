<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'content' => 'required|string',
        ]);

        Note::create([
            'lead_id' => $request->lead_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    public function destroy(Note $note)
    {
        // Authorization check could be added here
        $note->delete();

        return back()->with('success', 'Note deleted successfully.');
    }
}
