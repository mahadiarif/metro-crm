<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PipelineStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stages = [
            ['name' => 'Qualified', 'slug' => 'qualified', 'order_column' => 1, 'color' => '#3b82f6', 'win_probability' => 20],
            ['name' => 'Contacted', 'slug' => 'contacted', 'order_column' => 2, 'color' => '#06b6d4', 'win_probability' => 40],
            ['name' => 'Proposal Sent', 'slug' => 'proposal-sent', 'order_column' => 3, 'color' => '#f59e0b', 'win_probability' => 60],
            ['name' => 'Negotiation', 'slug' => 'negotiation', 'order_column' => 4, 'color' => '#10b981', 'win_probability' => 80],
            ['name' => 'Closed Won', 'slug' => 'closed-won', 'order_column' => 5, 'color' => '#22c55e', 'win_probability' => 100],
            ['name' => 'Closed Lost', 'slug' => 'closed-lost', 'order_column' => 6, 'color' => '#ef4444', 'win_probability' => 0],
        ];

        foreach ($stages as $stage) {
            \App\Models\PipelineStage::updateOrCreate(['slug' => $stage['slug']], $stage);
        }
    }
}
