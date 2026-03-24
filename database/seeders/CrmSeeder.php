<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\Lead;
use App\Models\User;
use HasinHayder\Tyro\Models\Role;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            'Broadband Internet (FDX)' => ['Home Basic', 'Office Premium', 'Enterprise Dedicated'],
            'P2P Data Links' => ['Inter-city Link', 'Intra-city Link'],
            'Microsoft 365' => ['Business Standard', 'Business Premium', 'Enterprise E3'],
            'Cloud Platform' => ['AWS Starter', 'AWS Pro', 'Azure Enterprise'],
        ];

        $marExeRole = Role::where('slug', 'marketing-executive')->first();
        if (!$marExeRole) {
            $marExeRole = Role::create(['name' => 'Marketing Executive', 'slug' => 'marketing-executive']);
        }

        $marExe = User::firstOrCreate(
        ['email' => 'marketing@salescrm.com'],
        ['name' => 'Marketing Executive', 'password' => bcrypt('password')]
        );
        $marExe->assignRole($marExeRole);

        foreach ($services as $serviceName => $packages) {
            $service = Service::create([
                'name' => $serviceName,
                'description' => "High quality $serviceName solutions.",
            ]);

            foreach ($packages as $packageName) {
                ServicePackage::create([
                    'service_id' => $service->id,
                    'name' => $packageName,
                    'price' => rand(50, 500),
                    'description' => "Standard $packageName for $serviceName.",
                ]);
            }

            // Create some leads
            for ($i = 0; $i < 3; $i++) {
                Lead::create([
                    'company_name' => "Company $i $serviceName",
                    'client_name' => "Person $i",
                    'phone' => "0170000000$i",
                    'email' => "contact$i@company.com",
                    'service_id' => $service->id,
                    'status' => ['new', 'contacted', 'interested'][rand(0, 2)],
                    'assigned_user' => $marExe->id,
                ]);
            }
        }
    }
}
