<?php

namespace App\Observers;

use App\Models\SalesCall;
use App\Models\LeadActivity;
use Illuminate\Support\Str;

class SalesCallObserver
{
    /**
     * Handle the SalesCall "created" event.
     */
    public function created(SalesCall $salesCall): void
    {
        LeadActivity::create([
            'lead_id' => $salesCall->lead_id,
            'user_id' => $salesCall->user_id,
            'type' => 'call_logged',
            'description' => "Sales call logged by " . ($salesCall->user->name ?? 'User') . " with outcome: " . Str::headline($salesCall->outcome),
            'properties' => [
                'call_id' => $salesCall->id,
                'outcome' => $salesCall->outcome,
                'notes' => Str::limit($salesCall->notes, 100)
            ]
        ]);
    }
}
