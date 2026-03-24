<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
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

    protected $fillable = [
        'lead_id',
        'user_id',
        'visit_number',
        'visit_stage',
        'service_id',
        'visit_date',
        'meeting_notes',
        'interest_summary_status',
        'next_followup_date',
        'location'
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'next_followup_date' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service interests for the visit.
     */
    public function interests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(VisitServiceInterest::class);
    }

    /**
     * Scope a query to only include accessible visits.
     */
    public function scopeAccessible($query)
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasPrivilege('visits.view_all')) {
            return $query;
        }

        if ($user->hasPrivilege('visits.view_team')) {
            $teamMemberIds = $user->teamMembers()->pluck('id')->push($user->id);
            return $query->whereIn('user_id', $teamMemberIds);
        }

        return $query->where('user_id', $user->id);
    }
}
