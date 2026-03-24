<?php

namespace App\Domains\Proposals\Actions;

use App\Models\Proposal;
use App\Mail\ServiceProposalMailable;
use Illuminate\Support\Facades\Mail;

class SendProposalEmailAction
{
    /**
     * Send the proposal PDF to the client via email.
     */
    public function execute(Proposal $proposal): void
    {
        if (!$proposal->pdf_path) {
            return;
        }

        Mail::to($proposal->lead->email)->send(new ServiceProposalMailable($proposal));

        $proposal->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }
}
