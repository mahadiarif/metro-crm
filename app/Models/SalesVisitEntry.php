<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesVisitEntry extends Model
{
    protected static function booted()
    {
        static::addGlobalScope('role_access', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();
                $currentStr = strtolower($user->role ?? '');
                $tyroRoles = method_exists($user, 'tyroRoleSlugs') ? $user->tyroRoleSlugs() : [];
                
                $isSuperAdmin = in_array($currentStr, ['super admin', 'super_admin', 'super-admin', 'admin']) || in_array('super_admin', $tyroRoles) || in_array('super-admin', $tyroRoles) || in_array('admin', $tyroRoles);
                $isManager    = in_array($currentStr, ['manager']) || in_array('manager', $tyroRoles);
                $isTeamLeader = in_array($currentStr, ['team leader', 'team_leader', 'team-leader']) || in_array('team_leader', $tyroRoles) || in_array('team-leader', $tyroRoles);
                
                if ($isSuperAdmin || $isManager) {
                    // Sees everything
                } elseif ($isTeamLeader) {
                    $teamMemberIds = $user->teamMembers()->pluck('id')->push($user->id)->toArray();
                    $builder->whereIn('marketing_exe_id', $teamMemberIds);
                } else {
                    $builder->where('marketing_exe_id', $user->id);
                }
            }
        });
    }
    protected $fillable = [
        'daily_sales_visit_id', 
        'visit_number', 
        'visit_date', 
        'service_type', 
        'status', 
        'notes',
        'location',
        'next_followup_at',
        'marketing_exe_id',
        'visit_stage',
        'address',
        'contact_person',
        'designation',
        'phone',
        'email',
        'source',
        'existing_provider',
        'current_usage'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_followup_at' => 'date',
    ];

    /**
     * Get the service usage records for this specific visit entry.
     */
    public function serviceUsages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ServiceUsage::class, 'sales_visit_entry_id');
    }

    public function dailySalesVisit(): BelongsTo
    {
        return $this->belongsTo(DailySalesVisit::class);
    }

    /**
     * Get the marketing executive who performed the visit.
     */
    public function marketingExe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marketing_exe_id');
    }
}
