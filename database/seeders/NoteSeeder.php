<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;
use App\Models\Lead;
use App\Models\User;

class NoteSeeder extends Seeder
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

        foreach ($leads as $lead) {
            Note::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'content' => 'Initial assessment note for ' . $lead->company_name . '. The client seems interested in our service packages but needs more technical details.',
            ]);

            Note::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'content' => 'Follow-up regarding the previous discussion. Sent the requested documentation and waiting for their feedback.',
            ]);
        }
    }
}
