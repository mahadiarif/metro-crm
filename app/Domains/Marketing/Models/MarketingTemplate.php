<?php

namespace App\Domains\Marketing\Models;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'content',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Render the template content for a specific lead.
     */
    public function renderFor(Lead $lead): string
    {
        $placeholders = [
            '{lead_name}'         => $lead->client_name ?? 'Valued Client',
            '{company_name}'      => $lead->company_name ?? 'Your Company',
            '{service_name}'      => $lead->service->name ?? 'our services',
            '{assigned_executive}' => $lead->assignedUser->name ?? 'Our Team',
            '{phone}'             => $lead->phone ?? '',
            '{email}'             => $lead->email ?? '',
        ];

        return str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $this->content
        );
    }
}
