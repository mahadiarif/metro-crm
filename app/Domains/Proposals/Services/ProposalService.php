<?php

namespace App\Domains\Proposals\Services;

use App\Models\Proposal;
use App\Domains\Proposals\Actions\GenerateProposalPdfAction;
use App\Domains\Proposals\Actions\SendProposalEmailAction;

class ProposalService
{
    public function __construct(
        protected GenerateProposalPdfAction $generatePdf,
        protected SendProposalEmailAction $sendEmail
    ) {}

    /**
     * Fully process a proposal: Generate PDF and Send Email.
     */
    public function processProposal(Proposal $proposal): void
    {
        $this->generatePdf->execute($proposal);
        $this->sendEmail->execute($proposal);
    }

    /**
     * Update proposal status.
     */
    public function updateStatus(Proposal $proposal, string $status): void
    {
        $proposal->update(['status' => $status]);
    }
}
