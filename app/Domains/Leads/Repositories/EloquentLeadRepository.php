<?php

namespace App\Domains\Leads\Repositories;

use App\Models\Lead;
use Illuminate\Support\Collection;

class EloquentLeadRepository implements LeadRepositoryInterface
{
    public function all(): Collection
    {
        return Lead::accessible()->latest()->get();
    }

    public function filter(array $filters): Collection
    {
        $query = Lead::accessible();

        if (!empty($filters['pipeline_stage_id'])) {
            $query->where('stage_id', $filters['pipeline_stage_id']);
        }

        if (!empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }

        if (!empty($filters['assigned_user_id'])) {
            $query->where('assigned_user', $filters['assigned_user_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword) {
                $q->where('company_name', 'like', "%{$keyword}%")
                  ->orWhere('client_name', 'like', "%{$keyword}%")
                  ->orWhere('contact_person', 'like', "%{$keyword}%");
            });
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?Lead
    {
        return Lead::accessible()->find($id);
    }

    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $lead = Lead::findOrFail($id);
        return $lead->update($data);
    }

    public function delete(int $id): bool
    {
        return Lead::destroy($id) > 0;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $lead = Lead::findOrFail($id);
        $lead->status = $status;
        return $lead->save();
    }

    public function updateStage(int $id, int $stageId): bool
    {
        $lead = Lead::findOrFail($id);
        $lead->stage_id = $stageId;
        return $lead->save();
    }
}
