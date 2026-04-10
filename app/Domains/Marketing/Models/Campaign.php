<?php

namespace App\Domains\Marketing\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
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
                    $builder->whereIn('created_by', $teamMemberIds);
                } else {
                    $builder->where('created_by', $user->id);
                }
            }
        });
    }
    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    protected $fillable = [
        'name',
        'type',
        'message',
        'status',
        'scheduled_at',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(CampaignRecipient::class);
    }
}
