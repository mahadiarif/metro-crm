<?php

namespace App\Domains\Proposals\Actions;

use App\Models\Proposal;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateProposalPdfAction
{
    /**
     * Generate a PDF for the given proposal and return the storage path.
     */
    public function execute(Proposal $proposal): string
    {
        $proposal->load(['lead', 'service', 'servicePackage', 'user']);
        
        $data = [
            'proposal' => $proposal,
            'lead' => $proposal->lead,
            'date' => now()->format('d M Y'),
        ];

        $pdf = Pdf::loadView('emails.proposals.pdf-template', $data);
        
        $filename = 'proposals/proposal_' . $proposal->id . '_' . time() . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        $proposal->update(['pdf_path' => $filename]);

        return $filename;
    }
}
