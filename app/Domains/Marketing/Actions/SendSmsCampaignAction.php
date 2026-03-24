<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Models\Campaign;
use App\Domains\Marketing\Jobs\SendSmsJob;

class SendSmsCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->load('recipients');
        
        foreach ($campaign->recipients as $recipient) {
            $message = str_replace('{name}', $recipient->lead->client_name, $campaign->message);
            SendSmsJob::dispatch($recipient, $message);
        }

        $campaign->update(['status' => 'sent']);
    }
}
