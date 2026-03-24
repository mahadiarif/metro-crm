<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;

class FollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $leads = Lead::all();

        if ($leads->isEmpty() || !$user) {
            return;
        }

        foreach ($leads as $index => $lead) {
            // Scheduled future follow-up
            FollowUp::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'scheduled_at' => Carbon::now()->addDays(rand(1, 5)),
                'notes' => 'Follow up to discuss project timeline and budget for ' . $lead->company_name . '.',
            ]);

            // Completed past follow-up for some
            if ($index % 2 == 0) {
                FollowUp::create([
                    'lead_id' => $lead->id,
                    'user_id' => $user->id,
                    'scheduled_at' => Carbon::now()->subDays(5),
                    'completed_at' => Carbon::now()->subDays(4),
                    'notes' => 'Initial follow-up call. Client is reviewing the introduction materials.',
                ]);
            }
        }
    }
}
