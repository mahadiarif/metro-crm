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
    public $outcome = '';
    public $notes = '';
    public $nextCallAt = '';
    public $isModalOpen = false;

    protected $listeners = ['openOutcomeModal' => 'open'];

    protected $rules = [
        'outcome' => 'required|string',
        'notes' => 'nullable|string',
        'nextCallAt' => 'nullable|date|after_or_equal:today',
    ];

    public function open($leadId)
    {
        $this->leadId = $leadId;
        $lead = Lead::find($leadId);
        if ($lead) {
            $this->leadName = $lead->company_name . ' (' . $lead->client_name . ')';
            $this->reset(['outcome', 'notes', 'nextCallAt']);
            $this->isModalOpen = true;
        }
    }

    public function close()
    {
        $this->isModalOpen = false;
        $this->reset(['leadId', 'leadName', 'outcome', 'notes', 'nextCallAt']);
    }

    public function saveOutcome()
    {
        $this->validate();

        SalesCall::create([
            'lead_id' => $this->leadId,
            'user_id' => auth()->id(),
            'outcome' => $this->outcome,
            'notes' => $this->notes,
            'called_at' => now(),
            'next_call_at' => $this->nextCallAt ? Carbon::parse($this->nextCallAt) : null,
        ]);

        // Optional: Update lead status based on outcome
        $lead = Lead::find($this->leadId);
        if (in_array($this->outcome, ['reached', 'answered']) && $lead->status === 'new') {
            $lead->update(['status' => 'contacted']);
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
