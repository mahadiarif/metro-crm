<?php

namespace App\Domains\Visits\Actions;

use App\Models\Visit;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class CaptureVisitDetailsAction
{
    /**
     * Capture detailed visit information and update lead info.
     */
    public function execute(Visit $visit, array $clientInfo, array $interests): void
    {
        DB::transaction(function () use ($visit, $clientInfo, $interests) {
            // Update Lead with detailed info
            $visit->lead->update([
                'contact_person' => $clientInfo['contact_person'] ?? $visit->lead->contact_person,
                'designation' => $clientInfo['designation'] ?? $visit->lead->designation,
                'phone' => $clientInfo['phone'] ?? $visit->lead->phone,
                'email' => $clientInfo['email'] ?? $visit->lead->email,
                'address' => $clientInfo['address'] ?? $visit->lead->address,
                'existing_provider' => $clientInfo['existing_provider'] ?? $visit->lead->existing_provider,
                'current_usage' => $clientInfo['current_usage'] ?? $visit->lead->current_usage,
                'source' => $clientInfo['source'] ?? $visit->lead->source,
            ]);

            // Sync Service Interests
            $visit->interests()->delete();
            foreach ($interests as $interest) {
                $visit->interests()->create([
                    'service_type' => $interest['type'],
                    'interest_status' => $interest['status'],
                ]);
            }
        });
    }
}
