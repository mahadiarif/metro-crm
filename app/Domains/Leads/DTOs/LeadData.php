<?php

namespace App\Domains\Leads\DTOs;

use Illuminate\Http\Request;

readonly class LeadData
{
    public function __construct(
        public string $company_name,
        public string $client_name,
        public string $phone,
        public ?string $email = null,
        public ?int $service_id = null,
        public string $status = 'new',
        public ?int $assigned_user = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            company_name: $request->validated('company_name'),
            client_name: $request->validated('client_name'),
            phone: $request->validated('phone'),
            email: $request->validated('email'),
            service_id: $request->validated('service_id'),
            status: $request->validated('status', 'new'),
            assigned_user: $request->validated('assigned_user'),
        );
    }

    public function toArray(): array
    {
        return [
            'company_name' => $this->company_name,
            'client_name' => $this->client_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'service_id' => $this->service_id,
            'status' => $this->status,
            'assigned_user' => $this->assigned_user,
        ];
    }
}
