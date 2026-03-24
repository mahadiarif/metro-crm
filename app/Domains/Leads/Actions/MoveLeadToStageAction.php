<?php

namespace App\Domains\Leads\Actions;

use App\Domains\Leads\Repositories\LeadRepositoryInterface;
use App\Models\Lead;

class MoveLeadToStageAction
{
    public function __construct(
        protected LeadRepositoryInterface $repository
    ) {}

    public function execute(int $leadId, int $stageId): bool
    {
        return $this->repository->updateStage($leadId, $stageId);
    }
}
