<?php

namespace App\Observers;

use App\Models\Sale;
use App\Models\LeadActivity;

class SaleObserver
{
    /**
     * Handle the Sale "created" event.
     */
    public function created(Sale $sale): void
    {
        // 1. Log Activity
        LeadActivity::create([
            'lead_id' => $sale->lead_id,
            'user_id' => $sale->user_id,
            'type' => 'sale_closed',
            'description' => "Sale closed by " . ($sale->user->name ?? 'User') . " for ৳" . number_format($sale->amount, 2),
            'properties' => [
                'sale_id' => $sale->id,
                'amount' => $sale->amount
            ]
        ]);

        // 2. Automate Lead Status -> Closed
        if ($sale->lead) {
            $sale->lead->update([
                'status' => \App\Models\Lead::STATUS_CLOSED,
                'next_followup_at' => null // No more follow-ups needed for a won lead
            ]);
        }
    }
}
