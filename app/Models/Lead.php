<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

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
                    $builder->whereIn('assigned_user', $teamMemberIds);
                } else {
                    // Default fallback for Executives or unrecognized users: ONLY see their own data
                    $builder->where('assigned_user', $user->id);
                }
            }
        });
    }

    protected $fillable = [
        'company_name',
        'client_name',
        'contact_person',
        'designation',
        'address',
        'phone',
        'email',
        'existing_provider',
        'current_usage',
        'service_id',
        'service_package_id',
        'status',
        'assigned_user',
        'stage_id',
        'lead_date',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class, 'stage_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function servicePackage(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class , 'assigned_user');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    /**
     * Get the sale associated with the lead.
     */
    public function sale(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Sale::class);
    }

    /**
     * Get the activities for the lead.
     */
    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadActivity::class)->latest();
    }

    /**
     * Get the notes for the lead.
     */
    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Note::class)->latest();
    }

    /**
     * Get the proposals for the lead.
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class)->latest();
    }

    /**
     * Scope a query to only include accessible leads.
     */
    public function scopeAccessible($query)
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasPrivilege('leads.view_all')) {
            return $query;
        }

        if ($user->hasPrivilege('leads.view_team')) {
            $teamMemberIds = $user->teamMembers()->pluck('id')->push($user->id);
            return $query->whereIn('assigned_user', $teamMemberIds);
        }

        return $query->where('assigned_user', $user->id);
    }
}
