<?php

namespace App\Http\Controllers;

use App\Domains\Leads\Actions\CreateLeadAction;
use App\Domains\Leads\Actions\UpdateLeadAction;
use App\Domains\Leads\DTOs\LeadData;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;

class LeadController extends Controller
{
    public function __construct(
        protected LeadRepositoryInterface $repository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $leads = $this->repository->all();
        return response()->json($leads);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request, CreateLeadAction $createLeadAction): JsonResponse
    {
        $this->authorize('create', Lead::class);
        $leadData = new LeadData(...$request->validated());
        $lead = $createLeadAction->execute($leadData);

        return response()->json($lead, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $lead = $this->repository->find($id);
        
        if ($lead) {
            $this->authorize('view', $lead);
        }
        
        if (!$lead) {
            return response()->json(['message' => 'Lead not found'], 404);
        }

        return response()->json($lead->load(['service', 'assignedUser', 'visits', 'followUps']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeadRequest $request, int $id, UpdateLeadAction $updateLeadAction): JsonResponse
    {
        $lead = $this->repository->find($id);
        if ($lead) {
            $this->authorize('update', $lead);
        }
        
        $leadData = new LeadData(...$request->validated());
        $success = $updateLeadAction->execute($id, $leadData);

        if (!$success) {
            return response()->json(['message' => 'Failed to update lead'], 500);
        }

        return response()->json($this->repository->find($id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $lead = $this->repository->find($id);
        if ($lead) {
            $this->authorize('delete', $lead);
        }
        
        $success = $this->repository->delete($id);

        if (!$success) {
            return response()->json(['message' => 'Failed to delete lead'], 500);
        }

        return response()->json(null, 204);
    }
}
