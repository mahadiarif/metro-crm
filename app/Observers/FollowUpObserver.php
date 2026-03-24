<?php

namespace App\Observers;

use App\Models\FollowUp;
use App\Models\LeadActivity;

class FollowUpObserver
{
    /**
     * Handle the FollowUp "created" event.
     */
    public function created(FollowUp $followUp): void
    {
        LeadActivity::create([
            'lead_id' => $followUp->lead_id,
            'user_id' => $followUp->user_id,
            'type' => 'followup_added',
            'description' => "Follow-up scheduled by " . ($followUp->user->name ?? 'User') . " for " . $followUp->scheduled_at->format('Y-m-d H:i'),
            'properties' => [
                'follow_up_id' => $followUp->id,
                'notes' => $followUp->notes
            ]
        ]);
    }
}
