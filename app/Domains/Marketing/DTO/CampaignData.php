<?php

namespace App\Domains\Marketing\DTO;

use Illuminate\Support\Carbon;

class CampaignData
{
    public function __construct(
        public string $name,
        public string $type,
        public string $message,
        public ?Carbon $scheduledAt = null,
        public ?int $createdBy = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            type: $data['type'],
            message: $data['message'],
            scheduledAt: isset($data['scheduled_at']) ? Carbon::parse($data['scheduled_at']) : null,
            createdBy: $data['created_by'] ?? auth()->id(),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'message' => $this->message,
            'scheduled_at' => $this->scheduledAt,
            'created_by' => $this->createdBy,
        ];
    }
}
