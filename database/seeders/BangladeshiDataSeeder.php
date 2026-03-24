<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Visit;
use App\Models\Sale;
use App\Models\User;
use App\Models\Service;
use App\Models\ServicePackage;
use Carbon\Carbon;

class BangladeshiDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $services = Service::all();
        
        if (!$user || $services->isEmpty()) {
            return;
        }

        $companies = [
            ['name' => 'Grameenphone Ltd.', 'contact' => 'Mr. Arif Ahmed', 'address' => 'GP House, Bashundhara, Dhaka', 'phone' => '01711001122'],
            ['name' => 'Robi Axiata Ltd.', 'contact' => 'Ms. Nasrin Akter', 'address' => 'Robi Corporate Office, Gulshan, Dhaka', 'phone' => '01819001122'],
            ['name' => 'Banglalink Digital', 'contact' => 'Mr. Kamal Hossain', 'address' => 'Tigers Den, Gulshan, Dhaka', 'phone' => '01911001122'],
            ['name' => 'BRAC Bank PLC', 'contact' => 'Ms. Farhana Islam', 'address' => 'Tejgaon I/A, Dhaka', 'phone' => '01713004455'],
            ['name' => 'Dutch-Bangla Bank', 'contact' => 'Mr. Sajid Rahman', 'address' => 'Motijheel C/A, Dhaka', 'phone' => '01714005566'],
            ['name' => 'Beximco Pharma', 'contact' => 'Ms. Tania Sultana', 'address' => 'Dhanmondi, Dhaka', 'phone' => '01715006677'],
            ['name' => 'Square Pharma', 'contact' => 'Mr. Monirul Islam', 'address' => 'Uttara, Dhaka', 'phone' => '01716007788'],
            ['name' => 'Walton Hi-Tech', 'contact' => 'Ms. Rumana Ahmed', 'address' => 'Chandra, Gazipur', 'phone' => '01717008899'],
            ['name' => 'PRAN-RFL Group', 'contact' => 'Mr. Ashraful Alam', 'address' => 'Badda, Dhaka', 'phone' => '01718009900'],
            ['name' => 'Akij Group', 'contact' => 'Ms. Jesmin Ara', 'address' => 'Tejgaon, Dhaka', 'phone' => '01719000011'],
        ];

        foreach ($companies as $index => $data) {
            $service = $services->random();
            $package = ServicePackage::where('service_id', $service->id)->first();
            
            // 1. Create Lead
            $lead = Lead::create([
                'company_name' => $data['name'],
                'client_name' => $data['contact'],
                'contact_person' => $data['contact'],
                'designation' => 'Manager/Director',
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => strtolower(str_replace(' ', '', $data['name'])) . '@example.com',
                'service_id' => $service->id,
                'service_package_id' => $package ? $package->id : null,
                'status' => 'closed', // Set all to closed to ensure 10 sales records
                'assigned_user' => $user->id,
                'zone' => 'Dhaka Central',
                'lead_date' => Carbon::now()->subDays(rand(10, 30)),
            ]);

            // 2. Create Visit
            Visit::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'visit_number' => 1,
                'visit_stage' => 'Initial Discussion',
                'service_id' => $service->id,
                'visit_date' => Carbon::now()->subDays(rand(5, 15)),
                'meeting_notes' => 'Detailed discussion with ' . $data['contact'] . ' from ' . $data['name'] . ' regarding ' . $service->name . ' solutions.',
                'interest_summary_status' => 'proposal_request',
                'next_followup_date' => Carbon::now()->addDays(rand(1, 10)),
                'location' => $data['address'],
            ]);

            // 3. Create Sale
            Sale::create([
                'lead_id' => $lead->id,
                'amount' => rand(50000, 200000),
                'remarks' => 'Strategic partnership finalized with ' . $data['name'] . '.',
                'service_id' => $service->id,
                'service_package_id' => $package ? $package->id : null,
                'closed_at' => Carbon::now()->subDays(rand(1, 5)),
                'user_id' => $user->id,
            ]);
        }
    }
}
