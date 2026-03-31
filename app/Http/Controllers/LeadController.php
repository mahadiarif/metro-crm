<?php

namespace App\Http\Controllers;

use App\Domains\Leads\Actions\CreateLeadAction;
use App\Domains\Leads\Actions\UpdateLeadAction;
use App\Domains\Leads\DTOs\LeadData;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\LeadAssignmentLog;
use App\Models\User;
use App\Models\Service;
use App\Models\PipelineStage;
use App\Notifications\LeadAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LeadController extends Controller
{
    public function __construct(
        protected LeadRepositoryInterface $repository
    ) {}

    /**
     * Display a listing of the resource with multi-filtering.
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'pipeline_stage_id',
            'service_id',
            'assigned_user_id',
            'start_date',
            'end_date',
            'keyword'
        ]);

        $leads = $this->repository->filter($filters);
        
        $stages = PipelineStage::all();
        $services = Service::all();
        
        $user = auth()->user();
        if ($user->hasAnyRole(['super admin', 'manager'])) {
            $users = User::all();
        } elseif ($user->hasRole('team leader')) {
            $users = $user->teamMembers()->get()->push($user);
        } else {
            $users = collect([$user]);
        }

        return view('leads.index', compact('leads', 'stages', 'services', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Lead::class);
        $services = Service::all();
        $stages = PipelineStage::all();
        return view('leads.create', compact('services', 'stages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeadRequest $request, CreateLeadAction $createLeadAction): RedirectResponse
    {
        try {
            $leadData = new LeadData(...$request->validated());
            $lead = $createLeadAction->execute($leadData);

            return redirect()->route('leads.index')
                ->with('success', 'Lead created successfully.');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Database error: ' . $this->getHumanFriendlyErrorMessage($e));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $lead = $this->repository->find($id);
        $this->authorize('update', $lead);
        
        $services = Service::all();
        $stages = PipelineStage::all();
        
        return view('leads.edit', compact('lead', 'services', 'stages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeadRequest $request, int $id, UpdateLeadAction $updateLeadAction): RedirectResponse
    {
        $lead = $this->repository->find($id);
        $this->authorize('update', $lead);

        try {
            $leadData = new LeadData(...$request->validated());
            $updateLeadAction->execute($id, $leadData);

            return redirect()->route('leads.index')
                ->with('success', 'Lead updated successfully.');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Database error: ' . $this->getHumanFriendlyErrorMessage($e));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An unexpected error occurred.');
        }
    }

    /**
     * Assign lead to a user and log history.
     */
    public function assign(Request $request, int $id): RedirectResponse
    {
        $lead = $this->repository->find($id);
        $this->authorize('update', $lead);

        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $user = auth()->user();
        $assigneeId = $request->input('assigned_to');
        
        // RBAC check for assignment options
        if ($user->hasRole('team leader')) {
            $allowedUserIds = $user->teamMembers()->pluck('id')->push($user->id)->toArray();
            if (!in_array($assigneeId, $allowedUserIds)) {
                return back()->with('error', 'You can only assign leads to your team members.');
            }
        }

        DB::beginTransaction();
        try {
            $oldAssignee = $lead->assigned_user;
            $lead->assigned_user = $assigneeId;
            $lead->save();

            // Log entry
            LeadAssignmentLog::create([
                'lead_id' => $lead->id,
                'assigned_from' => $oldAssignee,
                'assigned_to' => $assigneeId,
                'assigned_by' => $user->id,
            ]);

            DB::commit();

            // Notify assignee
            $assignee = User::find($assigneeId);
            $assignee->notify(new LeadAssignedNotification($lead, $user));

            return back()->with('success', 'Lead assigned successfully to ' . $assignee->name);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign lead: ' . $e->getMessage());
        }
    }

    /**
     * Helper to parse query exceptions.
     */
    protected function getHumanFriendlyErrorMessage(QueryException $e): string
    {
        if ($e->getCode() == 23000) {
            return 'A record with similar details already exists (Unique constraint violation).';
        }
        return 'Data integrity violation or constraint error.';
    }
}
