<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Visit;
use App\Models\DailySalesVisit;
use App\Models\SalesVisitEntry;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class MigrateSalesVisitsExpert extends Command
{
    protected $signature = 'migrate:sales-visits-expert';
    protected $description = 'Migrates legacy visit data to the new master-detail schema';

    public function handle()
    {
        $this->info('Starting Expert Data Migration...');
        
        $visits = Visit::all();
        $bar = $this->output->createProgressBar(count($visits));

        DB::beginTransaction();
        try {
            foreach ($visits as $legacyVisit) {
                $lead = Lead::find($legacyVisit->lead_id);
                if (!$lead) continue;

                // 1. Create/Find Master Record
                $master = DailySalesVisit::firstOrCreate(
                    ['lead_id' => $lead->id],
                    [
                        'user_id' => $legacyVisit->user_id,
                        'company_name' => $lead->company_name,
                        'address' => $lead->address,
                        'contact_person' => $lead->client_name,
                        'contact_number' => $lead->phone,
                        'email' => $lead->email,
                    ]
                );

                // 2. Create Entry
                SalesVisitEntry::create([
                    'daily_sales_visit_id' => $master->id,
                    'visit_number' => $legacyVisit->visit_number ?: 1,
                    'visit_date' => $legacyVisit->visit_date,
                    'service_type' => $legacyVisit->service->name ?? 'Internet',
                    'status' => $this->mapStatus($legacyVisit->interest_summary_status),
                    'notes' => $legacyVisit->meeting_notes,
                    'created_at' => $legacyVisit->created_at,
                    'updated_at' => $legacyVisit->updated_at,
                ]);

                $bar->advance();
            }

            DB::commit();
            $bar->finish();
            $this->newLine();
            $this->info('Migration completed successfully!');
            $this->warn('NOTE: Legacy records are still in the "visits" table but can now be accessed via the new schema.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Migration failed: ' . $e->getMessage());
        }
    }

    protected function mapStatus($status)
    {
        return match($status) {
            'proposal_request' => 'service_request',
            'no_interest' => 'not_interested',
            default => 'follow_up',
        };
    }
}
