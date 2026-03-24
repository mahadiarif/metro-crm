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
        LeadActivity::create([
            'lead_id' => $sale->lead_id,
            'user_id' => $sale->user_id,
            'type' => 'sale_closed',
            'description' => "Sale closed by " . ($sale->user->name ?? 'User') . " for $" . number_format($sale->amount, 2),
            'properties' => [
                'sale_id' => $sale->id,
                'amount' => $sale->amount
            ]
        ]);
    }
}
