<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Lead;
use App\Models\Visit;
use App\Domains\Visits\Actions\CaptureVisitDetailsAction;

class VisitForm extends Component
{
    /** @var \App\Models\Lead */
    public $lead;
    
    // Client Information
    public $contact_person, $designation, $phone, $email, $address;
    
    // Existing Provider & Usage
    public $existing_provider, $current_usage;
    
    // Service Interest (Checklist)
    public $service_interests = []; // ['Internet', 'Data', ...]
    
    // Status & Notes
    public $interest_summary_status = 'follow_up'; // follow_up, proposal_request, closed
    public $visit_stage = '1st Visit';
    public $meeting_notes;

    protected $rules = [
        'contact_person' => 'required|string|max:255',
        'designation' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string',
        'existing_provider' => 'nullable|string|max:255',
        'current_usage' => 'nullable|string',
        'service_interests' => 'array',
        'interest_summary_status' => 'required|in:follow_up,proposal_request,closed',
        'visit_stage' => 'required|in:1st Visit,2nd Visit,3rd Visit,4th Visit',
        'meeting_notes' => 'nullable|string',
    ];

    public function mount(Lead $lead)
    {
        $this->authorize('view', $lead);
        
        $this->lead = $lead;
        $this->contact_person = $lead->contact_person;
        $this->designation = $lead->designation;
        $this->phone = $lead->phone;
        $this->email = $lead->email;
        $this->address = $lead->address;
        $this->existing_provider = $lead->existing_provider;
        $this->current_usage = $lead->current_usage;
        
        // Load latest visit info if exists
        $latestVisit = $lead->visits()->latest()->first();
        if ($latestVisit) {
            $this->visit_stage = $this->getNextVisitStage($latestVisit->visit_stage);
            $this->service_interests = $latestVisit->interests()->pluck('service_type')->toArray();
        }
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

        // Transform interests for the action
        $interests = array_map(function($type) {
            return [
                'type' => $type,
                'status' => $this->interest_summary_status === 'proposal_request' ? 'proposal_requested' : 'interested'
            ];
        }, $this->service_interests);

        $captureAction->execute($visit, [
            'contact_person' => $this->contact_person,
            'designation' => $this->designation,
            'existing_provider' => $this->existing_provider,
            'current_usage' => $this->current_usage,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ], $interests);

        session()->flash('success', 'Visit details captured successfully for ' . $this->lead->company_name);
        return redirect()->route('tyro-dashboard.resources.index', 'leads');
    }

    public function render()
    {
        return view('livewire.dashboard.visit-form');
    }
}
