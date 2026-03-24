<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Models\Campaign;
use App\Domains\Marketing\Jobs\SendEmailJob;

class SendEmailCampaignAction
{
    public function execute(Campaign $campaign): void
    {
        $campaign->load('recipients');

        foreach ($campaign->recipients as $recipient) {
            $message = str_replace('{name}', $recipient->lead->client_name, $campaign->message);
            $subject = "Marketing Campaign: " . $campaign->name;
            
            SendEmailJob::dispatch($recipient, $subject, $message);
        }

        $campaign->update(['status' => 'sent']);
    }
}
