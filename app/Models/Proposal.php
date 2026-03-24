<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proposal extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'service_id',
        'service_package_id',
        'amount',
        'status',
        'pdf_path',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function servicePackage(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class);
    }

    /**
     * Scope a query to only include accessible proposals.
     */
    public function scopeAccessible($query)
    {
        $user = auth()->user();
        
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasPrivilege('proposals.view_all')) {
            return $query;
        }

        if ($user->hasPrivilege('proposals.view_team')) {
            $teamMemberIds = $user->teamMembers()->pluck('id')->push($user->id);
            return $query->whereIn('user_id', $teamMemberIds);
        }

        return $query->where('user_id', $user->id);
    }
}
