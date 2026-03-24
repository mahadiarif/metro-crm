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
