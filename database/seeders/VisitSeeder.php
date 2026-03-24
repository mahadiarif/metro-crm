<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visit;
use App\Models\Lead;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $leads = Lead::all();
        $service = Service::first();

        if ($leads->isEmpty() || !$user || !$service) {
            return;
        }

        foreach ($leads as $index => $lead) {
            Visit::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'visit_number' => 1,
                'visit_stage' => '1st Visit',
                'service_id' => $service->id,
                'visit_date' => Carbon::now()->subDays(rand(1, 10)),
                'meeting_notes' => 'Discussed initial requirements for ' . $lead->company_name . '. Interest shown in ' . $service->name . '.',
                'interest_summary_status' => $index % 2 == 0 ? 'follow_up' : 'proposal_request',
                'next_followup_date' => Carbon::now()->addDays(7),
                'location' => $lead->address ?? 'Client Office',
            ]);

            // Add a second visit for the first lead
            if ($index === 0) {
                Visit::create([
                    'lead_id' => $lead->id,
                    'user_id' => $user->id,
                    'visit_number' => 2,
                    'visit_stage' => '2nd Visit',
                    'service_id' => $service->id,
                    'visit_date' => Carbon::now()->subDays(2),
                    'meeting_notes' => 'Follow-up meeting. Presentation given. Client requested a detailed technical proposal.',
                    'interest_summary_status' => 'proposal_request',
                    'next_followup_date' => Carbon::now()->addDays(3),
                    'location' => $lead->address ?? 'Client Office',
                ]);
            }
        }
    }
}
