<?php

namespace App\Observers;

use App\Models\Visit;
use App\Models\LeadActivity;

class VisitObserver
{
    /**
     * Handle the Visit "created" event.
     */
    public function created(Visit $visit): void
    {
        LeadActivity::create([
            'lead_id' => $visit->lead_id,
            'user_id' => $visit->user_id,
            'type' => 'visit_added',
            'description' => "Visit added by " . ($visit->user->name ?? 'User') . " at " . $visit->visit_date->format('Y-m-d H:i'),
            'properties' => [
                'visit_id' => $visit->id,
                'location' => $visit->location,
                'notes' => $visit->meeting_notes
            ]
        ]);
    }
}
