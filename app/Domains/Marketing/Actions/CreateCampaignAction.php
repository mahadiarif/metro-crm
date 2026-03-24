<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\DTO\CampaignData;
use App\Domains\Marketing\Models\Campaign;
use App\Domains\Marketing\Models\CampaignRecipient;
use Illuminate\Support\Collection;

class CreateCampaignAction
{
    public function execute(CampaignData $data, Collection $recipients): Campaign
    {
        $campaign = Campaign::create($data->toArray());

        $recipientData = [];
        foreach ($recipients as $lead) {
            $recipientData[] = [
                'campaign_id' => $campaign->id,
                'lead_id' => $lead->id,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($recipientData)) {
            CampaignRecipient::insert($recipientData);
        }

        return $campaign;
    }
}
