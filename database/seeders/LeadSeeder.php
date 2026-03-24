<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\User;
use App\Models\Service;
use App\Models\ServicePackage;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $service = Service::first();
        
        if (!$user || !$service) {
            return;
        }

        $leads = [
            [
                'company_name' => 'Tech Solutions Ltd',
                'client_name' => 'Ahsan Habib',
                'contact_person' => 'Ahsan Habib',
                'designation' => 'IT Manager',
                'address' => 'Gulshan 1, Dhaka',
                'phone' => '01711223344',
                'email' => 'ahsan@techsolutions.com',
                'service_id' => $service->id,
                'status' => 'new',
                'assigned_user' => $user->id,
                'zone' => 'Dhaka North',
                'existing_provider' => 'Link3',
                'current_usage' => '50Mbps Dedicated',
            ],
            [
                'company_name' => 'Metro Garments',
                'client_name' => 'Kamal Hossain',
                'contact_person' => 'Kamal Hossain',
                'designation' => 'Executive Director',
                'address' => 'Uttara Sector 4, Dhaka',
                'phone' => '01822334455',
                'email' => 'kamal@metrogarments.com',
                'service_id' => $service->id,
                'status' => 'interested',
                'assigned_user' => $user->id,
                'zone' => 'Dhaka North',
                'existing_provider' => 'AmberIT',
                'current_usage' => '20Mbps Shared',
            ],
            [
                'company_name' => 'Creative Agency',
                'client_name' => 'Sara Islam',
                'contact_person' => 'Sara Islam',
                'designation' => 'Creative Director',
                'address' => 'Banani, Dhaka',
                'phone' => '01911998877',
                'email' => 'sara@creative.agency',
                'service_id' => $service->id,
                'status' => 'contacted',
                'assigned_user' => $user->id,
                'zone' => 'Dhaka North',
                'existing_provider' => 'None',
                'current_usage' => 'Home Broadband',
            ]
        ];

        foreach ($leads as $leadData) {
            Lead::create($leadData);
        }
    }
}
