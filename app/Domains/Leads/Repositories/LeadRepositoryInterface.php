<?php

namespace App\Domains\Leads\Repositories;

use App\Models\Lead;
use Illuminate\Support\Collection;

interface LeadRepositoryInterface
{
    public function all(): Collection;
    public function filter(array $filters): Collection;
    public function find(int $id): ?Lead;
    public function create(array $data): Lead;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function updateStatus(int $id, string $status): bool;
    public function updateStage(int $id, int $stageId): bool;
}
