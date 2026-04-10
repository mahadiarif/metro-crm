<?php

namespace App\Livewire\SalesCall;

use Livewire\Component;
use App\Models\Lead;
use App\Models\SalesCall;
use Carbon\Carbon;

class OutcomeModal extends Component
{
    public $leadId;
    public $leadName;
    
    // Profile Fields
    public $address = '';
    public $contactPerson = '';
    public $designation = '';
    public $phone = '';
    public $email = '';
    public $leadSource = '';
    public $existingProvider = '';
    public $currentUsage = '';

    public $outcome = '';
    public $notes = '';
    public $nextCallAt = '';
    public $closeReason = '';

    // New Additive CRM Fields
    public $callStatus;
    public $nextFollowupDate;

    public $isModalOpen = false;
    public int $callCount = 0;
    public $isStandalone = false;
    public $search = '';
    public $leads = [];

    // Expert Multi-Service Intelligence
    public $dynamicServices = [];
    public $editingId = null; 

    protected $listeners = ['openOutcomeModal' => 'open'];

    protected function rules()
    {
        return [
            'outcome' => 'required|string|in:follow_up,service_request,not_interested',
            'notes' => 'required_if:outcome,follow_up|nullable|string',
            'nextCallAt' => 'required_if:outcome,follow_up|nullable|date|after_or_equal:today',
            'closeReason' => 'required_if:outcome,not_interested|nullable|string',
            // Profile Rules
            'address' => 'nullable|string',
            'contactPerson' => 'nullable|string',
            'designation' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'leadSource' => 'nullable|string',
            'existingProvider' => 'nullable|string',
            'currentUsage' => 'nullable|string',
            // New Additive Rules
            'callStatus' => 'nullable|string',
            'nextFollowupDate' => 'nullable|date|after_or_equal:today',
        ];
    }

    /**
     * Open the modal for a specific lead or an existing call entry (edit).
     */
    public function open($leadId = null, $callId = null)
    {
        $this->reset(['leadId', 'leadName', 'address', 'contactPerson', 'designation', 'phone', 'email', 'leadSource', 'existingProvider', 'currentUsage', 'outcome', 'notes', 'nextCallAt', 'closeReason', 'dynamicServices', 'editingId']);
        
        if ($callId) {
            $this->editingId = $callId;
            $call = SalesCall::with('serviceUsages')->findOrFail($callId);
            $this->leadId = $call->lead_id;
            $this->leadName = $call->lead->company_name ?? 'Lead';
            $this->outcome = $call->outcome;
            $this->notes = $call->notes;
            $this->callStatus = $call->call_status;
            // Load snapshots stored in the call record
            $this->address = $call->address;
            $this->contactPerson = $call->contact_person;
            $this->designation = $call->designation;
            $this->phone = $call->phone;
            $this->email = $call->email;
            $this->leadSource = $call->source;
            $this->existingProvider = $call->existing_provider;
            $this->currentUsage = $call->current_usage;

            foreach ($call->serviceUsages as $svc) {
                $this->dynamicServices[] = [
                    'service_type' => $svc->service_type,
                    'competitor'   => $svc->competitor,
                    'details'      => $svc->details['value'] ?? '',
                ];
            }
        } elseif ($leadId) {
            if (is_numeric($leadId)) {
                $this->loadLeadData($leadId);
            } else {
                $this->leadId = $leadId->id;
                $this->loadLeadData($this->leadId);
            }
            $this->addServiceRow();
        } else {
            $this->isStandalone = true;
            $this->addServiceRow();
        }

        $this->isModalOpen = true;
    }

    protected function loadLeadData($id)
    {
        $lead = Lead::find($id);
        if ($lead) {
            $this->leadId = $lead->id;
            $this->leadName = $lead->company_name;
            $this->address = $lead->address;
            $this->contactPerson = $lead->client_name;
            $this->designation = $lead->designation;
            $this->phone = $lead->phone;
            $this->email = $lead->email;
            $this->leadSource = $lead->source;
            $this->existingProvider = $lead->existing_provider;
            $this->currentUsage = $lead->current_usage;
            $this->callCount = $lead->salesCalls()->count() + 1;
        }
    }

    public function close()
    {
        $this->isModalOpen = false;
        $this->reset(['leadId', 'leadName', 'outcome', 'notes', 'nextCallAt', 'closeReason', 'dynamicServices']);
    }

    /**
     * Dynamic Row Logic: Add service entry
     */
    public function addServiceRow()
    {
        $this->dynamicServices[] = [
            'service_type' => 'internet',
            'competitor'   => '',
            'details'      => '',
        ];
    }

    /**
     * Dynamic Row Logic: Remove service entry
     */
    public function removeServiceRow($index)
    {
        unset($this->dynamicServices[$index]);
        $this->dynamicServices = array_values($this->dynamicServices);
    }

    public function saveOutcome()
    {
        $this->validate();

        $lead = Lead::findOrFail($this->leadId);

        // 1. Update Lead Profile (Master Sync)
        $lead->update([
            'address' => $this->address,
            'client_name' => $this->contactPerson,
            'designation' => $this->designation,
            'phone' => $this->phone,
            'email' => $this->email,
            'source' => $this->leadSource,
            'existing_provider' => $this->existingProvider,
            'current_usage' => $this->currentUsage,
            'lead_status' => $lead->lead_status ?? 'qualified',
        ]);

        // 2. Log or Update Sales Call with Snapshot
        if ($this->editingId) {
            $call = SalesCall::findOrFail($this->editingId);
            $call->update([
                'outcome' => $this->outcome,
                'call_status' => $this->callStatus,
                'notes' => $this->notes,
                'next_call_at' => $this->outcome === 'follow_up' ? Carbon::parse($this->nextCallAt) : null,
                // Partial snapshots update
                'existing_provider' => $this->existingProvider,
                'current_usage' => $this->currentUsage,
            ]);
            $call->serviceUsages()->delete();
        } else {
            $call = SalesCall::create([
                'lead_id' => $this->leadId,
                'user_id' => auth()->id(),
                'outcome' => $this->outcome,
                'call_status' => $this->callStatus,
                'notes' => $this->notes,
                'called_at' => now(),
                'next_call_at' => $this->outcome === 'follow_up' ? Carbon::parse($this->nextCallAt) : null,
                'next_followup_date' => $this->nextFollowupDate,
                // Snapshots
                'address' => $this->address,
                'contact_person' => $this->contactPerson,
                'designation' => $this->designation,
                'phone' => $this->phone,
                'email' => $this->email,
                'source' => $this->leadSource,
                'existing_provider' => $this->existingProvider,
                'current_usage' => $this->currentUsage,
            ]);
        }

        // 3. Save Unified Market Intelligence (Multi-Service)
        if (count($this->dynamicServices) > 0) {
            foreach ($this->dynamicServices as $svc) {
                if (empty($svc['service_type'])) continue;

                $call->serviceUsages()->create([
                    'service_type' => $svc['service_type'],
                    'competitor'   => $svc['competitor'] ?? null,
                    'details'      => [
                        'value'       => $svc['details'] ?? null,
                        'recorded_at' => now()->toDateTimeString(),
                    ],
                ]);
            }
        }
        
        if ($this->outcome === 'service_request') {
            // Move to Sales pipeline stage
            $salesStage = \App\Models\PipelineStage::whereRaw('LOWER(name) LIKE ?', ['%sales%'])
                ->orWhereRaw('LOWER(name) LIKE ?', ['%proposal%'])
                ->orWhere('name', 'Negotiation') // Fallback
                ->first();
                
            $lead->update([
                'status' => Lead::STATUS_IN_PIPELINE,
                'stage_id' => $salesStage?->id ?? $lead->stage_id,
                'last_called_at' => now(),
                'next_followup_at' => null // No more calls needed from Call module
            ]);
        } elseif ($this->outcome === 'not_interested') {
            // Close the lead
            $lead->update([
                'status' => Lead::STATUS_CLOSED, 
                'close_reason' => $this->closeReason,
                'last_called_at' => now(),
                'next_followup_at' => null
            ]);
        } elseif ($this->outcome === 'follow_up') {
            // Qualify the lead and keep active
            $lead->update([
                'status' => Lead::STATUS_ACTIVE,
                'last_called_at' => now(),
                'next_followup_at' => Carbon::parse($this->nextCallAt)
            ]);
        }

        $this->close();
        $this->dispatch('callLogged');
        session()->flash('success', 'Call outcome saved successfully.');
    }

    public function render()
    {
        return view('livewire.sales-call.outcome-modal');
    }
}
