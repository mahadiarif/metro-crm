<?php

namespace App\Domains\Visits\Actions;

use App\Models\Lead;
use App\Models\DailySalesVisit;
use App\Models\SalesVisitEntry;
use App\Models\PipelineStage;
use App\Models\LeadServiceStatus;
use Illuminate\Support\Facades\DB;

class LogSalesVisitAction
{
    /**
     * Execute the sales visit logging or update action.
     *
     * @param int $leadId
     * @param array $data
     * @param int|null $entryId Optional entry ID for editing
     * @return SalesVisitEntry
     */
    public function execute(int $leadId, array $data, ?int $entryId = null): SalesVisitEntry
    {
        return DB::transaction(function () use ($leadId, $data, $entryId) {
            $lead = Lead::findOrFail($leadId);

            // 1. Update Lead master profile with the latest data from the visit report
            $lead->update([
                'address'           => $data['address'] ?? $lead->address,
                'client_name'       => $data['contact_person'] ?? $lead->client_name,
                'designation'       => $data['designation'] ?? $lead->designation,
                'phone'             => $data['phone'] ?? $lead->phone,
                'email'             => $data['email'] ?? $lead->email,
                'source'            => $data['source'] ?? $lead->source,
                'existing_provider' => $data['existing_provider'] ?? $lead->existing_provider,
                'current_usage'     => $data['current_usage'] ?? $lead->current_usage,
                'lead_status'       => $data['lead_status'] ?? $lead->lead_status ?? 'qualified',
                'visit_count'       => ($lead->visit_count ?? 0) + ($entryId ? 0 : 1),
            ]);

            // 2. Sync master record (DailySalesVisit)
            $dailyVisit = DailySalesVisit::updateOrCreate(
                ['lead_id' => $leadId],
                [
                    'user_id'                => auth()->id(),
                    'company_name'           => $lead->company_name,
                    'address'                => $lead->address,
                    'latitude'               => $data['latitude'] ?? null,
                    'longitude'              => $data['longitude'] ?? null,
                    'contact_person'         => $lead->client_name,
                    'designation'            => $lead->designation ?? '',
                    'contact_number'         => $lead->phone,
                    'email'                  => $lead->email,
                    'lead_source'            => $lead->source ?? '',
                    'competitor_provider'    => $lead->existing_provider ?? '',
                    'current_service_usage'  => $lead->current_usage ?? '',
                    'visit_image'            => $data['visit_image'] ?? null,
                    'lead_temperature'       => $data['lead_temperature'] ?? $lead->lead_temperature ?? 'warm',
                    'pain_points'            => $data['pain_points'] ?? null,
                    'visit_status'           => $data['visit_status'] ?? null,
                    'next_followup_date'     => $data['next_followup_date'] ?? null,
                    'followup_type'          => $data['followup_type'] ?? null,
                    'notes'                  => $data['notes'] ?? null,
                ]
            );

            // Sync Lead Temperature back to Lead
            if (isset($data['lead_temperature'])) {
                $lead->update(['lead_temperature' => $data['lead_temperature']]);
            }

            // 3. Prepare the visit entry data with historical snapshots
            $entryData = [
                'daily_sales_visit_id' => $dailyVisit->id,
                'visit_number'         => $data['visit_number'] ?? 1,
                'visit_date'           => $data['visit_date'] ?? now(),
                'service_type'         => $data['service_type'] ?? 'Multi-Service',
                'status'               => $data['status'],
                'notes'                => $data['notes'] ?? null,
                'location'             => $data['location'] ?? null,
                'next_followup_at'     => $data['next_followup_at'] ?? null,
                'marketing_exe_id'     => $data['marketing_exe_id'] ?? auth()->id(),
                'visit_stage'          => $data['visit_stage'] ?? (($data['visit_number'] ?? 1) . ' Visit'),
                'address'              => $data['address'] ?? null,
                'contact_person'       => $data['contact_person'] ?? null,
                'designation'          => $data['designation'] ?? null,
                'phone'                => $data['phone'] ?? null,
                'email'                => $data['email'] ?? null,
                'source'               => $data['source'] ?? null,
                'existing_provider'    => $data['existing_provider'] ?? null,
                'current_usage'        => $data['current_usage'] ?? null,
                'visit_status'         => $data['visit_status'] ?? null,
                'next_followup_date'   => $data['next_followup_date'] ?? null,
                'followup_type'        => $data['followup_type'] ?? null,
            ];

            if ($entryId) {
                $entry = SalesVisitEntry::findOrFail($entryId);
                $entry->update($entryData);
                // Clear old usages for fresh sync
                $entry->serviceUsages()->delete();
            } else {
                $entry = SalesVisitEntry::create($entryData);
            }

            // 4. Save New Dynamic Multi-Service Usages (expert intelligence)
            if (isset($data['dynamic_services']) && is_array($data['dynamic_services'])) {
                foreach ($data['dynamic_services'] as $svc) {
                    if (empty($svc['service_type'])) continue;

                    $entry->serviceUsages()->create([
                        'service_type' => $svc['service_type'],
                        'competitor'   => $svc['competitor'] ?? null,
                        'details'      => [
                            'value' => $svc['details'] ?? null,
                            'recorded_at' => now()->toDateTimeString(),
                        ],
                    ]);
                }
            }

            // 5. Native Multi-Service Status Updates & Dynamic Interested Services
            if (isset($data['interested_services']) && is_array($data['interested_services'])) {
                foreach ($data['interested_services'] as $svc) {
                    if (empty($svc['service_id'])) continue;
                    
                    LeadServiceStatus::updateOrCreate(
                        ['lead_id' => $leadId, 'service_id' => $svc['service_id']],
                        [
                            'status' => $svc['status'] ?? 'interested',
                            'current_usage' => $svc['details'] ?? null
                        ]
                    );
                }
            }

            if (isset($data['service_statuses'])) {
                foreach ($data['service_statuses'] as $serviceId => $serviceData) {
                    // Handle both flat string status (legacy/simple) and structured array (expert)
                    $status = is_array($serviceData) ? ($serviceData['status'] ?? 'n/a') : $serviceData;
                    
                    if ($status === 'n/a') continue;
                    
                    $updateData = ['status' => $status];
                    
                    if (is_array($serviceData)) {
                        $updateData['competitor_name'] = $serviceData['competitor_name'] ?? null;
                        $updateData['current_usage'] = $serviceData['current_usage'] ?? null;
                    }
                    
                    LeadServiceStatus::updateOrCreate(
                        ['lead_id' => $leadId, 'service_id' => $serviceId],
                        $updateData
                    );
                }
            }

            // 6. Automated Workflow Logic (Aggregate Pipeline Promotion)
            $this->updateLeadPipeline($lead, $data);

            return $entry;
        });
    }

    /**
     * Promote lead to pipeline if ANY service (static or dynamic) is requested.
     */
    protected function updateLeadPipeline(Lead $lead, array $data): void
    {
        $hasServiceRequest = false;
        
        // 1. Check Legacy Matrix Statuses
        if (isset($data['service_statuses'])) {
            foreach ($data['service_statuses'] as $status) {
                if ($status === 'service_request') {
                    $hasServiceRequest = true;
                    break;
                }
            }
        }

        // 2. Check New Dynamic Service Entries (Unified Intelligence)
        if (!$hasServiceRequest && isset($data['dynamic_services'])) {
            foreach ($data['dynamic_services'] as $svc) {
                // If a user adds a row without choosing a type, skip it
                if (empty($svc['service_type'])) continue;
                
                // Currently, a simple existence in dynamic services could imply interest, 
                // but we check if it's purposefully added. 
                // Enhanced logic: any dynamic service logged via Visit is treated as a warm lead/interest.
                $hasServiceRequest = true; 
                break;
            }
        }

        if ($hasServiceRequest) {
            $salesStage = PipelineStage::whereRaw('LOWER(name) LIKE ?', ['%sales%'])
                ->orWhereRaw('LOWER(name) LIKE ?', ['%proposal%'])
                ->first();
                
            $lead->update([
                'stage_id' => $salesStage?->id ?? $lead->stage_id, 
                'status' => Lead::STATUS_IN_PIPELINE,
                'last_called_at' => now()
            ]);
        } elseif (($data['status'] ?? null) === 'not_interested') {
             $lead->update(['status' => Lead::STATUS_CLOSED, 'last_called_at' => now()]);
        } else {
             $lead->update(['status' => Lead::STATUS_ACTIVE, 'last_called_at' => now()]);
        }
    }
}
