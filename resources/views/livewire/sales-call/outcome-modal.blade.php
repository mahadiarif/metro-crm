<div class="sales-call-form">
    <style>
        .sales-call-form { font-family: inherit; }
        .call-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(320px, 0.75fr);
            gap: 1rem;
        }
        .call-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
            margin-bottom: 1rem;
            overflow: visible;
            color: var(--card-foreground);
        }
        .call-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .call-card-title {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--foreground);
            margin: 0;
        }
        .call-card-body { padding: 1.25rem; }
        .call-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--muted);
            color: var(--muted-foreground);
            flex: 0 0 auto;
        }
        .call-icon svg, .simple-icon { width: 18px; height: 18px; }
        .call-field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .call-field-full { grid-column: 1 / -1; }
        .call-label {
            display: block;
            margin-bottom: 0.45rem;
            color: var(--muted-foreground);
            font-size: 0.75rem;
            font-weight: 600;
        }
        .call-search-results {
            position: absolute;
            width: 100%;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: var(--shadow-lg, 0 10px 25px rgba(15, 23, 42, 0.12));
            z-index: 50;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .call-lead-result {
            padding: 0.75rem 0.875rem;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--foreground);
            background: var(--card);
        }
        .call-lead-result:hover { background: var(--muted); }
        .call-service-card {
            border: 1px solid var(--border);
            background: var(--muted);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }
        .call-service-grid {
            display: grid;
            grid-template-columns: 1.1fr 1fr 1fr auto;
            gap: 0.75rem;
            align-items: end;
        }
        .call-outcome-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }
        .call-outcome {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--card);
            color: var(--muted-foreground);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 44px;
            padding: 0.625rem;
            font-size: 0.8125rem;
            font-weight: 700;
        }
        .call-outcome.active {
            border-color: var(--primary);
            background: var(--primary);
            color: var(--primary-foreground);
        }
        .call-roadmap {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }
        .call-step {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            background: var(--muted);
            color: var(--muted-foreground);
            font-size: 0.75rem;
            font-weight: 700;
        }
        .call-step.active {
            background: var(--foreground);
            border-color: var(--foreground);
            color: var(--background);
        }
        .call-step.done {
            background: var(--success);
            border-color: var(--success);
            color: var(--success-foreground);
        }
        .call-step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--card);
            color: inherit;
            flex: 0 0 auto;
        }
        .dark .call-service-card,
        .dark .call-step {
            background: var(--secondary);
        }
        .dark .call-step.active {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--primary-foreground);
        }
        .dark .call-step.done .call-step-number {
            background: color-mix(in srgb, var(--success-foreground) 12%, transparent);
        }
        .call-actions { padding: 0 1rem 1rem; }
        .call-alert {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid color-mix(in srgb, var(--destructive) 35%, var(--border));
            background: color-mix(in srgb, var(--destructive) 8%, var(--card));
            color: var(--destructive);
            border-radius: 8px;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 700;
        }
        @media (max-width: 1100px) {
            .call-dashboard-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 760px) {
            .call-field-grid,
            .call-service-grid,
            .call-outcome-grid,
            .call-roadmap { grid-template-columns: 1fr; }
        }
    </style>

    <div class="call-dashboard-grid">
        @if ($errors->any())
            <div class="call-alert">
                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0z" />
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <div class="call-card">
                <div class="call-card-header">
                    <h3 class="call-card-title">
                        <span class="call-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M6 20V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14M9 8h1M14 8h1M9 12h1M14 12h1M9 16h1M14 16h1" />
                            </svg>
                        </span>
                        Client Profile
                    </h3>
                </div>
                <div class="call-card-body">
                    <div class="call-field-grid">
                        <div class="call-field-full">
                            <label class="call-label">Company / Lead</label>
                            @if($isStandalone)
                                <div style="position: relative;">
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input" placeholder="Search company, client, or phone">
                                    @if(!empty($leads))
                                        <div class="call-search-results">
                                            @foreach($leads as $l)
                                                <div wire:click="selectLead({{ $l['id'] }})" class="call-lead-result">
                                                    {{ $l['company_name'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="form-input" style="height: auto; background: var(--muted);">{{ $leadName }}</div>
                            @endif
                        </div>
                        <div class="call-field-full">
                            <label class="call-label">Address</label>
                            <input type="text" wire:model="address" class="form-input" placeholder="Client address">
                        </div>
                        <div>
                            <label class="call-label">Contact Person</label>
                            <input type="text" wire:model="contactPerson" class="form-input" placeholder="Contact name">
                        </div>
                        <div>
                            <label class="call-label">Designation</label>
                            <input type="text" wire:model="designation" class="form-input" placeholder="IT Manager">
                        </div>
                        <div>
                            <label class="call-label">Phone</label>
                            <input type="text" wire:model="phone" class="form-input" placeholder="+880 1xxx-xxxxxx">
                        </div>
                        <div>
                            <label class="call-label">Email</label>
                            <input type="email" wire:model="email" class="form-input" placeholder="name@company.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="call-card">
                <div class="call-card-header">
                    <h3 class="call-card-title">
                        <span class="call-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5M8 19V9M12 19V7M16 19v-5M20 19V4" />
                            </svg>
                        </span>
                        Market Intelligence
                    </h3>
                    <button type="button" wire:click="addServiceRow" class="btn btn-sm btn-secondary">Add Product</button>
                </div>
                <div class="call-card-body">
                    @foreach($dynamicServices as $index => $svc)
                        <div class="call-service-card">
                            <div class="call-service-grid">
                                <div>
                                    <label class="call-label">Service Type</label>
                                    <select wire:model.live="dynamicServices.{{ $index }}.service_type" class="form-input">
                                        <option value="internet">Dedicated Internet</option>
                                        <option value="m365">Microsoft 365</option>
                                        <option value="ip_phone">IP Solution</option>
                                        <option value="sms">SMS Gateway</option>
                                        <option value="cloud">Cloud PBX / ERP</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="call-label">Competitor</label>
                                    <input type="text" wire:model="dynamicServices.{{ $index }}.competitor" class="form-input" placeholder="Current vendor">
                                </div>
                                <div>
                                    @php
                                        $sType = $dynamicServices[$index]['service_type'] ?? 'internet';
                                        $label = match($sType) {
                                            'internet' => 'Bandwidth',
                                            'm365' => 'Licenses',
                                            'ip_phone' => 'Channels',
                                            'sms' => 'Rate',
                                            'cloud' => 'Spec',
                                            default => 'Usage'
                                        };
                                    @endphp
                                    <label class="call-label">{{ $label }}</label>
                                    <input type="text" wire:model="dynamicServices.{{ $index }}.details" class="form-input" placeholder="Details">
                                </div>
                                <button type="button" wire:click="removeServiceRow({{ $index }})" class="btn btn-icon btn-ghost text-danger" title="Remove product">
                                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="call-card">
                <div class="call-card-header">
                    <h3 class="call-card-title">
                        <span class="call-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5h14v10H8l-3 3V5z" />
                            </svg>
                        </span>
                        Call Notes
                    </h3>
                </div>
                <div class="call-card-body">
                    <textarea wire:model="notes" rows="4" class="form-input" style="resize: vertical;" placeholder="Conversation summary and customer priorities"></textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="call-card">
                <div class="call-card-header">
                    <h3 class="call-card-title">
                        <span class="call-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h4l2 5-3 2a13 13 0 0 0 6 6l2-3 5 2v4h-2A15 15 0 0 1 2 6V5h1z" />
                            </svg>
                        </span>
                        Call Outcome
                    </h3>
                </div>
                <div class="call-card-body">
                    <div class="call-outcome-grid">
                        @foreach(['follow_up' => 'Follow Up', 'service_request' => 'Service Request', 'not_interested' => 'Not Interested'] as $val => $label)
                            <button type="button" wire:click="$set('outcome', '{{ $val }}')" class="call-outcome {{ $outcome === $val ? 'active' : '' }}">
                                @if($val === 'follow_up')
                                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M21 12a9 9 0 1 1-3-6.7" /></svg>
                                @elseif($val === 'service_request')
                                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5" /></svg>
                                @else
                                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" /></svg>
                                @endif
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="call-card">
                <div class="call-card-header">
                    <h3 class="call-card-title">
                        <span class="call-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M4 11h16M6 5h12a2 2 0 0 1 2 2v13H4V7a2 2 0 0 1 2-2z" />
                            </svg>
                        </span>
                        Follow Up
                    </h3>
                </div>
                <div class="call-card-body">
                    <div class="call-field-grid">
                        <div>
                            <label class="call-label">Next Call</label>
                            <input type="datetime-local" class="form-input" wire:model="nextCallAt">
                        </div>
                        <div>
                            <label class="call-label">Status</label>
                            <select wire:model="callStatus" class="form-input">
                                <option value="">Select status</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                        </div>
                    </div>

                    @if($outcome === 'not_interested')
                        <div style="margin-top: 1rem;">
                            <label class="call-label">Close Reason</label>
                            <textarea class="form-input" rows="3" wire:model="closeReason" style="resize: vertical;" placeholder="Why the lead is not interested"></textarea>
                        </div>
                    @endif
                </div>
            </div>

            <div class="call-card">
                <div class="call-card-header">
                    <h3 class="call-card-title">
                        <span class="call-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h4l3 8 4-16 3 8h2" />
                            </svg>
                        </span>
                        Call Roadmap
                    </h3>
                </div>
                <div class="call-card-body">
                    <div class="call-roadmap">
                        @for($i = 1; $i <= 3; $i++)
                            <div class="call-step {{ $i < $callCount ? 'done' : ($i === $callCount ? 'active' : '') }}">
                                <span class="call-step-number">{{ $i }}</span>
                                Call {{ $i }}
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="call-actions">
                <button type="button" wire:click="saveOutcome" wire:loading.attr="disabled" class="btn btn-primary" style="width: 100%; justify-content: center; min-height: 44px;">
                    <span wire:loading.remove>Save Sales Call</span>
                    <span wire:loading>Saving...</span>
                    <svg wire:loading.remove class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
