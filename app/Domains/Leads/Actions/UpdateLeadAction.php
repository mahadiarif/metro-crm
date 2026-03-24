<?php

namespace App\Domains\Leads\Actions;

use App\Domains\Leads\DTOs\LeadData;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;

class UpdateLeadAction
{
    public function __construct(
        protected LeadRepositoryInterface $repository
    ) {}

    public function execute(int $id, LeadData $data): bool
    {
        return $this->repository->update($id, $data->toArray());
    }
}
