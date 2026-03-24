<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Lead;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\User;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $leads = Lead::all();
        $services = Service::all();

        if ($leads->isEmpty() || $services->isEmpty() || !$user) {
            return;
        }

        foreach ($leads->take(3) as $index => $lead) {
            $service = $services->random();
            $package = ServicePackage::where('service_id', $service->id)->first();

            Sale::create([
                'lead_id' => $lead->id,
                'amount' => rand(5000, 50000),
                'remarks' => 'Sale closed successfully for ' . $lead->company_name . '.',
                'service_id' => $service->id,
                'service_package_id' => $package ? $package->id : null,
                'closed_at' => Carbon::now()->subDays(rand(1, 10)),
                'user_id' => $user->id,
            ]);
        }
    }
}
