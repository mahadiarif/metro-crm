<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Lead;
use App\Domains\Proposals\Services\ProposalService;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function __construct(
        protected ProposalService $proposalService
    ) {}

    /**
     * Store a new proposal.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Proposal::class);
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'service_id' => 'required|exists:services,id',
            'service_package_id' => 'nullable|exists:service_packages,id',
            'amount' => 'required|numeric',
        ]);

        $proposal = Proposal::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'status' => 'draft',
        ]));

        return response()->json([
            'success' => true,
            'proposal' => $proposal,
        ]);
    }

    /**
     * Finalize and send the proposal.
     */
    public function send(Proposal $proposal)
    {
        $this->authorize('send', $proposal);
        $this->proposalService->processProposal($proposal);

        return response()->json([
            'success' => true,
            'message' => 'Proposal sent successfully.',
        ]);
    }

    /**
     * Update proposal status (Accepted/Rejected).
     */
    public function updateStatus(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal);
        $request->validate(['status' => 'required|in:accepted,rejected']);

        $this->proposalService->updateStatus($proposal, $request->status);

        return response()->json(['success' => true]);
    }
}
