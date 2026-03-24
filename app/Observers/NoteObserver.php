<?php

namespace App\Observers;

use App\Models\Note;
use App\Models\LeadActivity;
use Illuminate\Support\Str;

class NoteObserver
{
    /**
     * Handle the Note "created" event.
     */
    public function created(Note $note): void
    {
        LeadActivity::create([
            'lead_id' => $note->lead_id,
            'user_id' => $note->user_id,
            'type' => 'note_added',
            'description' => "Note added by " . ($note->user->name ?? 'User'),
            'properties' => [
                'note_id' => $note->id,
                'content' => Str::limit($note->content, 100)
            ]
        ]);
    }
}
