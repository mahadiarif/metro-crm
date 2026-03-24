<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Models\Privilege;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Granular Privileges
        $privileges = [
            // Leads
            'leads.view_all'    => 'View all leads',
            'leads.view_team'   => 'View team leads',
            'leads.view_own'    => 'View assigned leads',
            'leads.create'      => 'Create new leads',
            'leads.edit_all'    => 'Edit any lead',
            'leads.edit_team'   => 'Edit team leads',
            'leads.edit_own'    => 'Edit assigned leads',
            'leads.delete'      => 'Delete leads',
            'leads.assign'      => 'Assign leads to users',

            // Visits & Followups
            'visits.view_all'     => 'View all visits',
            'visits.view_team'    => 'View team visits',
            'visits.view_own'     => 'View own visits',
            'visits.create'       => 'Log visits',
            'followups.view_all'  => 'View all followups',
            'followups.view_team' => 'View team followups',
            'followups.view_own'  => 'View own followups',
            'followups.create'    => 'Schedule followups',

            // Proposals
            'proposals.view_all'    => 'View all proposals',
            'proposals.view_team'   => 'View team proposals',
            'proposals.view_own'    => 'View own proposals',
            'proposals.create'      => 'Create proposals',
            'proposals.send'        => 'Send proposals via email',
            'proposals.approve'     => 'Approve proposals',

            // Reports & Dashboard
            'reports.view'          => 'Access reports section',
            'reports.performance'   => 'View executive performance',
            'dashboard.analytics'   => 'View management analytics',

            // Admin
            'users.manage'          => 'Manage users and roles',
            'config.manage'         => 'System configuration',
        ];

        foreach ($privileges as $slug => $name) {
            Privilege::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // Role Mapping
        $superAdmin = Role::where('slug', 'super-admin')->first();
        if ($superAdmin) {
            $ids = Privilege::whereIn('slug', array_keys($privileges))->pluck('id')->toArray();
            $superAdmin->privileges()->sync($ids);
        }

        $manager = Role::where('slug', 'manager')->first();
        if ($manager) {
            $managerPrivs = [
                'leads.view_all', 'leads.edit_all', 'leads.assign',
                'visits.view_all', 'followups.view_all',
                'proposals.view_all', 'proposals.approve',
                'reports.view', 'reports.performance', 'dashboard.analytics'
            ];
            $ids = Privilege::whereIn('slug', $managerPrivs)->pluck('id')->toArray();
            $manager->privileges()->sync($ids);
        }

        $teamLeader = Role::firstOrCreate(['name' => 'Team Leader', 'slug' => 'team-leader']);
        if ($teamLeader) {
            $tlPrivs = [
                'leads.view_team', 'leads.edit_team', 'leads.assign',
                'visits.view_team', 'followups.view_team',
                'proposals.view_team', 'proposals.create', 'proposals.send',
                'reports.view', 'dashboard.analytics'
            ];
            $ids = Privilege::whereIn('slug', $tlPrivs)->pluck('id')->toArray();
            $teamLeader->privileges()->sync($ids);
        }

        $sales = Role::where('slug', 'marketing-executive')->first();
        if ($sales) {
            $salesPrivs = [
                'leads.view_own', 'leads.create', 'leads.edit_own',
                'visits.view_own', 'visits.create',
                'followups.view_own', 'followups.create',
                'proposals.view_own', 'proposals.create', 'proposals.send'
            ];
            $ids = Privilege::whereIn('slug', $salesPrivs)->pluck('id')->toArray();
            $sales->privileges()->sync($ids);
        }
    }
}
