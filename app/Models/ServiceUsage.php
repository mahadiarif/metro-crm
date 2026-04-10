<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceUsage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_usages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sales_visit_entry_id',
        'service_type',
        'competitor',
        'details',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get the visit entry that owns the service usage record.
     */
    public function salesVisitEntry(): BelongsTo
    {
        return $this->belongsTo(SalesVisitEntry::class, 'sales_visit_entry_id');
    }

    /**
     * Get the call that owns the service usage record.
     */
    public function salesCall(): BelongsTo
    {
        return $this->belongsTo(SalesCall::class, 'sales_call_id');
    }
}
