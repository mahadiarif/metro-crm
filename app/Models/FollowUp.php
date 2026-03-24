<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('role_access', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();
                $currentStr = strtolower($user->role ?? '');
                $tyroRoles = method_exists($user, 'tyroRoleSlugs') ? $user->tyroRoleSlugs() : [];
                
                $isSuperAdmin = in_array($currentStr, ['super admin', 'super_admin', 'super-admin', 'admin']) || in_array('super_admin', $tyroRoles) || in_array('super-admin', $tyroRoles) || in_array('admin', $tyroRoles);
                $isManager    = in_array($currentStr, ['manager']) || in_array('manager', $tyroRoles);
                $isTeamLeader = in_array($currentStr, ['team leader', 'team_leader', 'team-leader']) || in_array('team_leader', $tyroRoles) || in_array('team-leader', $tyroRoles);
                $isExecutive  = in_array($currentStr, ['marketing executive', 'marketing_executive', 'marketing-executive']) || in_array('marketing_executive', $tyroRoles) || in_array('marketing-executive', $tyroRoles);
                
                if ($isSuperAdmin || $isManager) {
                    // Sees everything
                } elseif ($isTeamLeader) {
                    $teamMemberIds = $user->teamMembers()->pluck('id')->push($user->id)->toArray();
                    $builder->whereIn('user_id', $teamMemberIds);
                } else {
                    // Default fallback for Executives or unrecognized users: ONLY see their own data
                    $builder->where('user_id', $user->id);
                }
            }
        });
    }

    protected $fillable = ['lead_id', 'user_id', 'scheduled_at', 'completed_at', 'notes'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include accessible followups.
     */
    public function scopeAccessible($query)
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasPrivilege('followups.view_all')) {
            return $query;
        }

        if ($user->hasPrivilege('followups.view_team')) {
            $teamMemberIds = $user->teamMembers()->pluck('id')->push($user->id);
            return $query->whereIn('user_id', $teamMemberIds);
        }

        return $query->where('user_id', $user->id);
    }
}
