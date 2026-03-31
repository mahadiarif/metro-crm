<?php

namespace App\Domains\Leads\DTOs;

readonly class LeadData
{
    public function __construct(
        public string $company_name,
        public string $client_name,
        public string $phone,
        public ?string $email = null,
        public ?string $contact_person = null,
        public ?string $designation = null,
        public ?string $address = null,
        public ?string $existing_provider = null,
        public ?string $current_usage = null,
        public ?int $service_id = null,
        public ?int $service_package_id = null,
        public string $status = 'new',
        public ?int $assigned_user = null,
        public ?int $stage_id = null,
        public ?string $lead_date = null,
        public ?string $zone = null,
    ) {}

    public function toArray(): array
    {
        return [
            'company_name' => $this->company_name,
            'client_name' => $this->client_name,
            'contact_person' => $this->contact_person,
            'designation' => $this->designation,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'existing_provider' => $this->existing_provider,
            'current_usage' => $this->current_usage,
            'service_id' => $this->service_id,
            'service_package_id' => $this->service_package_id,
            'status' => $this->status,
            'assigned_user' => $this->assigned_user,
            'stage_id' => $this->stage_id,
            'lead_date' => $this->lead_date,
            'zone' => $this->zone,
        ];
    }
}
