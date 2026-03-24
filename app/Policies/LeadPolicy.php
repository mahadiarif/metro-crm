<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeadPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPrivilege('leads.view_all') || 
               $user->hasPrivilege('leads.view_team') || 
               $user->hasPrivilege('leads.view_own');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lead $lead): bool
    {
        if ($user->hasPrivilege('leads.view_all')) {
            return true;
        }

        if ($user->hasPrivilege('leads.view_team')) {
            return $lead->assignedUser && $lead->assignedUser->team_leader_id === $user->id;
        }

        return $user->hasPrivilege('leads.view_own') && $user->id === $lead->assigned_user;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPrivilege('leads.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lead $lead): bool
    {
        if ($user->hasPrivilege('leads.edit_all')) {
            return true;
        }

        if ($user->hasPrivilege('leads.edit_team')) {
            return $lead->assignedUser && $lead->assignedUser->team_leader_id === $user->id;
        }

        return $user->hasPrivilege('leads.edit_own') && $user->id === $lead->assigned_user;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lead $lead): bool
    {
        return $user->hasPrivilege('leads.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Lead $lead): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Lead $lead): bool
    {
        return false;
    }
}
