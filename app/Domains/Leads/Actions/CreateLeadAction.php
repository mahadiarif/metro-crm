<?php

namespace App\Domains\Leads\Actions;

use App\Domains\Leads\DTOs\LeadData;
use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Models\Lead;

class CreateLeadAction
{
    public function __construct(
        protected LeadRepositoryInterface $repository
    ) {}

    public function execute(LeadData $data): Lead
    {
        return $this->repository->create($data->toArray());
    }
}
