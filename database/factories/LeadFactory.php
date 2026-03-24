<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Service;
use App\Models\User;
use App\Models\PipelineStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        $industries = ['IT Services', 'Logistics', 'Manufacturing', 'Finance', 'Healthcare', 'Telecommunications', 'E-commerce', 'Construction', 'Real Estate', 'Education'];
        $countries = ['Bangladesh', 'India', 'Singapore', 'UAE'];
        $zones = ['Dhaka', 'Mumbai', 'Singapore Central', 'Dubai Marina', 'Chittagong', 'Bangalore'];

        return [
            'company_name' => $this->faker->company(),
            'client_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'service_id' => Service::inRandomOrder()->first()?->id ?? Service::factory(),
            'status' => $this->faker->randomElement(['new', 'contacted', 'interested', 'closed', 'lost']),
            'assigned_user' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'stage_id' => PipelineStage::inRandomOrder()->first()?->id,
            'zone' => $this->faker->randomElement($zones),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
