<?php

namespace App\Livewire\SalesVisit;

use Livewire\Component;
use App\Models\Lead;
use App\Models\User;
use App\Models\Service;
use App\Models\DailySalesVisit;
use App\Models\SalesVisitEntry;
use App\Models\LeadServiceStatus;
use App\Domains\Visits\Actions\LogSalesVisitAction;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

class VisitModal extends Component
{
    use WithFileUploads;
    // Lead Context
    public $leadId;
    public $companyName;
    public $address;
    public $contactPerson;
    public $designation;
    public $phone;
    public $email;
    public $leadSource;
    public $existingProvider;
    public $currentUsage;
    
    // Visit Entry Data
    public $visitNumber;
    public $visitDate;
    public $visitStage;
    public $marketingExeId;
    
    // Core Visit Data
    public $status = 'follow_up';
    public $notes;
    public $location;
    public $nextFollowupAt;

    // New Additive CRM Fields
    public $visitStatus;
    public $nextFollowupDate;
    public $followupType;

    // Expert Marketing Fields
    public $latitude;
    public $longitude;
    public $visitImage; // For file upload
    public $leadTemperature = 'warm'; // hot, warm, cold
    public $painPoints = []; // ['price', 'support', 'downtime', 'speed', 'coverage', 'relationship']
    
    public $interestedServices = []; // [{service_id, status, details}]
    
    // Multi-Service Matrix
    public $serviceStatusesSelected = []; // [ service_id => status ]
    public $serviceCompetitors = []; // [ service_id => 'Link3' ]
    public $serviceUsages = []; // [ service_id => '100 Mbps' ]
    
    // UI State
    public $isModalOpen = false;
    public int $callCount = 0;
    public $isStandalone = false;
    public $search = '';
    public $leads = [];
    public $editingId = null; // New: Track if we are editing

    // Dynamic Multi-Service Usages (Repeatable Rows)
    public $dynamicServices = []; 

    protected $listeners = [
        'openVisitModal' => 'open',
        'triggerSalesVisitModal' => 'open',
        'setCoordinates' => 'setCoordinates'
    ];

    /**
     * Initialization for both Modal and Full-Page contexts
     */
    public function mount($leadId = null, $isModalOpen = false, $isStandalone = false, $visitId = null)
    {
        $this->leadId = $leadId;
        $this->isModalOpen = $isModalOpen;
        $this->isStandalone = $isStandalone;
        $this->marketingExeId = auth()->id();
        $this->visitDate = now()->format('Y-m-d');
        $this->visitNumber = 1;
        $this->initializeServiceMatrix();
        $this->addServiceRow(); // Expert: Start with one empty row

        if ($visitId) {
            $this->open(null, $visitId);
        } elseif ($this->leadId) {
            $this->loadLeadData($this->leadId);
        }
    }

    public function rules()
    {
        return [
            'leadId'                  => 'nullable|exists:leads,id',
            'companyName'             => 'required_without:leadId|string|max:255',
            'marketingExeId'          => 'required|exists:users,id',
            'visitNumber'             => 'required|integer|min:1|max:10',
            'visitDate'               => 'required|date|before_or_equal:today',
            'status'                  => 'required|string',
            'notes'                   => 'nullable|string',
            'location'                => 'nullable|string|max:255',
            'nextFollowupAt'          => 'nullable|date|after_or_equal:today',
            'serviceStatusesSelected' => 'nullable|array',
            // Profile fields
            'address'                 => 'nullable|string',
            'contactPerson'           => 'nullable|string',
            'designation'             => 'nullable|string',
            'phone'                   => 'nullable|string',
            'email'                   => 'nullable|email',
            'leadSource'              => 'nullable|string',
            'existingProvider'        => 'nullable|string',
            'currentUsage'            => 'nullable|string',
            // Expert fields
            'latitude'                => 'nullable|numeric',
            'longitude'               => 'nullable|numeric',
            'visitImage'              => 'nullable|image|max:5120', // 5MB
            'leadTemperature'         => 'required|in:hot,warm,cold',
            'painPoints'              => 'nullable|array',
            // New Additive Fields
            'visitStatus'             => 'nullable|string',
            'nextFollowupDate'        => 'nullable|date|after_or_equal:today',
            'followupType'            => 'nullable|string',
            'dynamicServices'         => 'nullable|array',
            'dynamicServices.*.service_type' => 'nullable|string',
            'dynamicServices.*.competitor'   => 'nullable|string',
            'dynamicServices.*.details'      => 'nullable|string',
        ];
    }

    public function setCoordinates($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function open($leadId = null, $visitId = null)
    {
        $this->reset([
            'leadId', 'companyName', 'address', 'contactPerson', 'designation', 
            'phone', 'email', 'leadSource', 'existingProvider', 'currentUsage',
            'visitNumber', 'visitDate', 'notes', 'location', 'nextFollowupAt', 
            'search', 'leads', 'serviceStatusesSelected',
            'latitude', 'longitude', 'visitImage', 'leadTemperature', 'painPoints',
            'serviceCompetitors', 'serviceUsages',
            'visitStatus', 'nextFollowupDate', 'followupType', 'dynamicServices', 'editingId'
        ]);
        
        if ($visitId) {
            $this->editingId = $visitId;
            $entry = SalesVisitEntry::with('serviceUsages')->findOrFail($visitId);
            $this->leadId = $entry->lead_id;
            $this->companyName = $entry->dailySalesVisit->lead->company_name ?? 'Lead';
            $this->status = $entry->status;
            $this->notes = $entry->notes;
            
            foreach ($entry->serviceUsages as $svc) {
                $this->dynamicServices[] = [
                    'service_type' => $svc->service_type,
                    'competitor'   => $svc->competitor,
                    'details'      => $svc->details['value'] ?? '',
                ];
            }
        } elseif ($leadId) {
            $this->loadLeadData($leadId);
            $this->addServiceRow();
        } else {
            $this->visitNumber = 1;
            $this->addServiceRow();
            $this->addInterestedServiceRow();
        }

        $this->isModalOpen = true;
    }

    protected function initializeServiceMatrix()
    {
        $allServices = Service::all();
        foreach ($allServices as $service) {
            $this->serviceStatusesSelected[$service->id] = 'n/a';
            $this->serviceCompetitors[$service->id] = '';
            $this->serviceUsages[$service->id] = '';
        }
    }

    public function updatedLeadId($value)
    {
        if ($value) {
            $this->loadLeadData($value);
        }
    }

    public function updatedSearch()
    {
        $this->companyName = trim($this->search);
        $this->leadId = null;
        $this->visitNumber = 1;

        if (strlen($this->search) < 2) {
            $this->leads = [];
            return;
        }

        $matchedLeads = Lead::accessible()
            ->where(function($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('client_name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->limit(10)
            ->get();

        $exactLead = $matchedLeads->first(function ($lead) {
            return strcasecmp($lead->company_name, $this->companyName) === 0
                || strcasecmp((string) $lead->client_name, $this->companyName) === 0;
        });

        if ($exactLead) {
            $this->loadLeadData($exactLead->id);
            $this->search = $this->companyName;
            $this->leads = [];
            return;
        }

        $this->leads = $matchedLeads->toArray();
    }

    public function selectLead($id)
    {
        $this->loadLeadData($id);
        $this->leads = [];
        $this->search = $this->companyName;
    }

    protected function loadLeadData($id)
    {
        $lead = Lead::with('serviceStatuses')->findOrFail($id);
        $this->leadId = $lead->id;
        $this->companyName = $lead->company_name;
        $this->address = $lead->address;
        $this->contactPerson = $lead->client_name;
        $this->designation = $lead->designation;
        $this->phone = $lead->phone;
        $this->email = $lead->email;
        $this->leadSource = $lead->source;
        $this->existingProvider = $lead->existing_provider;
        $this->currentUsage = $lead->current_usage;
        $this->location = $lead->address;

        foreach ($lead->serviceStatuses as $ss) {
            $this->serviceStatusesSelected[$ss->service_id] = $ss->status;
            $this->serviceCompetitors[$ss->service_id] = $ss->competitor_name ?? '';
            $this->serviceUsages[$ss->service_id] = $ss->current_usage ?? '';
        }

        $this->visitNumber = SalesVisitEntry::whereHas('dailySalesVisit', function($q) use ($id) {
            $q->where('lead_id', $id);
        })->count() + 1;

        if ($this->visitNumber > 4) $this->visitNumber = 4;
        $this->visitDate = now()->format('Y-m-d');
    }

    public function setServiceStatus($serviceId, $status)
    {
        $this->serviceStatusesSelected[$serviceId] = $status;
    }

    /**
     * Repeatable Row Logic: Add a new service entry
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
     * Repeatable Row Logic: Remove a service entry
     */
    public function removeServiceRow($index)
    {
        unset($this->dynamicServices[$index]);
        $this->dynamicServices = array_values($this->dynamicServices);
    }

    public function close()
    {
        $this->isModalOpen = false;
    }

    public function saveVisit(LogSalesVisitAction $action)
    {
        try {
            $validated = $this->validate();
            $this->ensureLeadExistsForVisit();

            $validated = $this->validate();

            $data = [
                'visit_number'      => $validated['visitNumber'],
                'visit_date'        => $validated['visitDate'],
                'status'            => $validated['status'],
                'notes'             => $validated['notes'],
                'location'          => $validated['location'],
                'next_followup_at'  => $validated['nextFollowupAt'],
                'marketing_exe_id'  => $validated['marketingExeId'],
                'service_statuses'  => $this->getStructuredServiceStates(),
                // Snapshot/Profile data
                'address'           => $validated['address'],
                'contact_person'    => $validated['contactPerson'],
                'designation'       => $validated['designation'],
                'phone'             => $validated['phone'],
                'email'             => $validated['email'],
                'source'            => $validated['leadSource'],
                'existing_provider' => $validated['existingProvider'],
                'current_usage'     => $validated['currentUsage'],
                'lead_temperature'  => $this->leadTemperature,
                'pain_points'       => $this->painPoints,
                // New Additive Fields Mapping
                'visit_status'        => $this->visitStatus,
                'next_followup_date'  => $this->nextFollowupDate,
                'followup_type'       => $this->followupType,
                'dynamic_services'    => $this->dynamicServices,
                'interested_services' => $this->interestedServices,
            ];

            // Handle Image Upload
            if ($this->visitImage) {
                $path = $this->visitImage->store('visits', 'public');
                $data['visit_image'] = $path;
            }

            // Dynamic visit stage
            $data['visit_stage'] = $this->getVisitStage($validated['visitNumber']);

            $action->execute($this->leadId, $data, $this->editingId);
            $this->dispatch('visitLogged');
            $this->resetFormAfterSave();
            session()->flash('success', $this->editingId ? 'Visit successfully updated.' : 'Visit successfully logged.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    protected function ensureLeadExistsForVisit(): void
    {
        if ($this->leadId) {
            return;
        }

        $companyName = trim($this->companyName ?: $this->search);

        if ($companyName === '') {
            throw ValidationException::withMessages([
                'companyName' => 'Please enter a company name or select an existing lead.',
            ]);
        }

        $serviceId = Service::query()->value('id');

        if (!$serviceId) {
            throw ValidationException::withMessages([
                'companyName' => 'Please create at least one service before logging a new visit.',
            ]);
        }

        $lead = Lead::create([
            'company_name'      => $companyName,
            'client_name'       => $this->contactPerson ?: $companyName,
            'contact_person'    => $this->contactPerson ?: null,
            'designation'       => $this->designation,
            'address'           => $this->location ?: $this->address,
            'phone'             => $this->phone ?: 'N/A',
            'email'             => $this->email,
            'existing_provider' => $this->existingProvider,
            'current_usage'     => $this->currentUsage,
            'service_id'        => $serviceId,
            'status'            => Lead::STATUS_ACTIVE,
            'assigned_user'     => auth()->id(),
            'lead_date'         => now()->toDateString(),
            'lead_status'       => 'qualified',
            'lead_temperature'  => $this->leadTemperature,
        ]);

        $this->loadLeadData($lead->id);
    }

    protected function resetFormAfterSave(): void
    {
        $this->reset([
            'leadId', 'companyName', 'address', 'contactPerson', 'designation',
            'phone', 'email', 'leadSource', 'existingProvider', 'currentUsage',
            'visitNumber', 'notes', 'location', 'nextFollowupAt', 'search', 'leads',
            'serviceStatusesSelected', 'latitude', 'longitude', 'visitImage',
            'painPoints', 'serviceCompetitors', 'serviceUsages', 'visitStatus',
            'nextFollowupDate', 'followupType', 'dynamicServices', 'editingId',
        ]);

        $this->status = 'follow_up';
        $this->leadTemperature = 'warm';
        $this->marketingExeId = auth()->id();
        $this->visitDate = now()->format('Y-m-d');
        $this->initializeServiceMatrix();
        $this->addServiceRow();

        if (!$this->isStandalone) {
            $this->close();
        }
    }

    protected function getStructuredServiceStates()
    {
        $structured = [];
        foreach ($this->serviceStatusesSelected as $serviceId => $status) {
            $structured[$serviceId] = [
                'status'          => $status,
                'competitor_name' => $this->serviceCompetitors[$serviceId] ?? null,
                'current_usage'   => $this->serviceUsages[$serviceId] ?? null
            ];
        }
        return $structured;
    }
    public function addInterestedServiceRow()
    {
        $this->interestedServices[] = [
            'service_id' => '',
            'status'     => 'interested',
            'details'    => ''
        ];
    }

    public function removeInterestedServiceRow($index)
    {
        unset($this->interestedServices[$index]);
        $this->interestedServices = array_values($this->interestedServices);
    }

    protected function getVisitStage($num)
    {
        return $num . match((int)$num) {
            1 => 'st',
            2 => 'nd',
            3 => 'rd',
            default => 'th'
        } . ' Visit';
    }

    public function render()
    {
        $executives = [];
        if (auth()->user()->hasAnyRole(['super admin', 'manager', 'team leader'])) {
            $executives = User::all();
        } else {
            $executives = [auth()->user()];
        }

        return view('livewire.sales-visit.visit-modal', [
            'selectedLead'    => $this->leadId ? Lead::find($this->leadId) : null,
            'executives'      => $executives,
            'services'        => Service::all(),
            'serviceStatuses' => $this->serviceStatusesSelected,
        ]);
    }
}
