<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Lead;
use App\Models\Visit;
use App\Domains\Visits\Actions\CaptureVisitDetailsAction;

class VisitForm extends Component
{
    /** @var \App\Models\Lead|null */
    public $lead;
    public $selectedLeadId;
    public $allLeads = [];

    /** @var \App\Models\Visit|null */
    public $previous_visit;
    public $contact_person, $designation, $phone, $email, $address, $source;
    
    // Existing Provider & Usage
    public $existing_provider, $current_usage;
    
    // Service Matrix Outcomes: ['Internet' => 'follow_up', 'Data' => 'service_request', ...]
    public $service_outcomes = []; 
    
    // History Matrix: ['Internet' => ['1st Visit' => 'Follow Up', '2nd Visit' => 'Yes', ...]]
    public $service_history = [];
    public $available_services = ['Internet', 'Data', 'IPTSP', 'SMS', 'VOICE', 'LAN', 'Others'];
    
    // Overall Status & Notes
    public $interest_summary_status = 'follow_up'; 
    public $visit_stage = '1st Visit';
    public $meeting_notes;
    public $next_followup_date;

    protected $rules = [
        'selectedLeadId' => 'required|exists:leads,id',
        'contact_person' => 'required|string|max:255',
        'designation' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string',
        'source' => 'nullable|string|max:255',
        'existing_provider' => 'nullable|string|max:255',
        'current_usage' => 'nullable|string',
        'service_outcomes' => 'array',
        'interest_summary_status' => 'required|in:follow_up,proposal_request,closed',
        'visit_stage' => 'required|in:1st Visit,2nd Visit,3rd Visit,4th Visit',
        'meeting_notes' => 'nullable|string',
        'next_followup_date' => 'required_if:interest_summary_status,follow_up|nullable|date|after_or_equal:today',
    ];

    public function mount(?Lead $lead = null)
    {
        if ($lead && $lead->exists) {
            $this->loadLeadData($lead);
        } else {
            // Standalone mode: Load leads for dropdown
            $this->allLeads = Lead::active()->orderBy('company_name')->get();
        }
    }

    public function updatedSelectedLeadId($value)
    {
        if ($value) {
            $lead = Lead::find($value);
            if ($lead) {
                $this->loadLeadData($lead);
            }
        }
    }

    protected function loadLeadData(Lead $lead)
    {
        $this->authorize('view', $lead);
        
        $this->lead = $lead;
        $this->selectedLeadId = $lead->id;
        $this->contact_person = $lead->contact_person;
        $this->designation = $lead->designation;
        $this->phone = $lead->phone;
        $this->email = $lead->email;
        $this->address = $lead->address;
        $this->source = $lead->source;
        $this->existing_provider = $lead->existing_provider;
        $this->current_usage = $lead->current_usage;
        
        // Initialize service outcomes
        foreach ($this->available_services as $service) {
            $this->service_outcomes[$service] = null;
        }

        // Fetch Visit History for the Matrix
        $allVisits = $lead->visits()->with('interests')->orderBy('visit_date', 'asc')->get();
        foreach ($this->available_services as $service) {
            $this->service_history[$service] = [];
            foreach ($allVisits as $v) {
                $interest = $v->interests->where('service_type', $service)->first();
                if ($interest) {
                    $this->service_history[$service][$v->visit_stage] = $this->formatStatusForDisplay($interest->interest_status);
                }
            }
        }

        // Determine next stage
        $this->previous_visit = $allVisits->last();
        if ($this->previous_visit instanceof Visit) {
            $this->visit_stage = $this->getNextVisitStage($this->previous_visit->visit_stage);
        } else {
            $this->visit_stage = '1st Visit';
        }
    }

    protected function formatStatusForDisplay($status)
    {
        return match($status) {
            'follow_up' => 'Follow Up',
            'service_request' => 'Yes',
            'not_interested' => 'No',
            default => $status
        };
    }

    protected function getNextVisitStage($current)
    {
        return match($current) {
            '1st Visit' => '2nd Visit',
            '2nd Visit' => '3rd Visit',
            '3rd Visit' => '4th Visit',
            '4th Visit' => '4th Visit',
            default => '1st Visit',
        };
    }

    public function save(CaptureVisitDetailsAction $captureAction)
    {
        $this->validate();

        $visit = Visit::create([
            'lead_id' => $this->lead->id,
            'user_id' => auth()->id(),
            'visit_number' => $this->lead->visits()->count() + 1,
            'visit_stage' => $this->visit_stage,
            'visit_date' => now(),
            'meeting_notes' => $this->meeting_notes,
            'interest_summary_status' => $this->interest_summary_status,
        ]);

        // Transform matrix outcomes for the action
        $interests = [];
        foreach ($this->service_outcomes as $type => $status) {
            if ($status) {
                $interests[] = [
                    'type' => $type,
                    'status' => $status
                ];
            }
        }

        $captureAction->execute($visit, [
            'contact_person' => $this->contact_person,
            'designation' => $this->designation,
            'existing_provider' => $this->existing_provider,
            'current_usage' => $this->current_usage,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'source' => $this->source,
        ], $interests);

        // Qualify lead if interest is shown
        $updateData = [];
        if (in_array($this->interest_summary_status, ['follow_up', 'proposal_request'])) {
            $updateData['status'] = $this->interest_summary_status === 'proposal_request' ? Lead::STATUS_IN_PIPELINE : Lead::STATUS_ACTIVE;
            $updateData['next_followup_at'] = $this->next_followup_date ? \Carbon\Carbon::parse($this->next_followup_date) : null;
        } elseif ($this->interest_summary_status === 'closed') {
            $updateData['status'] = Lead::STATUS_CLOSED;
            $updateData['next_followup_at'] = null;
        }
        
        if (!empty($updateData)) {
            $this->lead->update($updateData);
        }

        session()->flash('success', 'Visit details captured successfully for ' . $this->lead->company_name);
        return redirect()->route('tyro-dashboard.sales-visits.index');
    }

    public function render()
    {
        return view('livewire.dashboard.visit-form');
    }
}
