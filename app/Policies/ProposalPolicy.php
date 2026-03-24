<?php

namespace App\Policies;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProposalPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPrivilege('proposals.view_all') || 
               $user->hasPrivilege('proposals.view_team') || 
               $user->hasPrivilege('proposals.view_own');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Proposal $proposal): bool
    {
        if ($user->hasPrivilege('proposals.view_all')) {
            return true;
        }

        if ($user->hasPrivilege('proposals.view_team')) {
            return $proposal->user && $proposal->user->team_leader_id === $user->id;
        }

        return $user->hasPrivilege('proposals.view_own') && $user->id === $proposal->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPrivilege('proposals.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Proposal $proposal): bool
    {
        // For proposals, usually we only allow updating drafts
        if ($proposal->status !== 'draft') {
            return false;
        }

        if ($user->hasPrivilege('proposals.approve')) {
            return true;
        }

        return $user->hasPrivilege('proposals.create') && $user->id === $proposal->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Proposal $proposal): bool
    {
        return $user->hasPrivilege('proposals.delete');
    }

    /**
     * Determine whether the user can send the proposal.
     */
    public function send(User $user, Proposal $proposal): bool
    {
        if ($user->hasPrivilege('proposals.send')) {
            if ($user->id === $proposal->user_id || $user->hasPrivilege('proposals.approve')) {
                return true;
            }
            // Add team check for sending if needed, but usually send is for own or manager
            if ($user->hasPrivilege('proposals.view_team') && $proposal->user && $proposal->user->team_leader_id === $user->id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Proposal $proposal): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Proposal $proposal): bool
    {
        return false;
    }
}
