<div class="sales-visit-form">
    <style>
        .sales-visit-form {
            font-family: inherit;
        }

        .visit-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(320px, 0.75fr);
            gap: 1rem;
            padding: 0;
        }

        .visit-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
            margin-bottom: 1rem;
            overflow: visible;
            color: var(--card-foreground);
        }

        .visit-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .visit-card-title {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--foreground);
            margin: 0;
        }

        .visit-card-body {
            padding: 1.25rem;
        }

        .visit-icon {
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

        .visit-icon svg,
        .simple-icon {
            width: 18px;
            height: 18px;
        }

        .visit-field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .visit-field-full {
            grid-column: 1 / -1;
        }

        .visit-label {
            display: block;
            margin-bottom: 0.45rem;
            color: var(--muted-foreground);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .visit-lead-result {
            padding: 0.75rem 0.875rem;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--foreground);
            background: var(--card);
        }

        .visit-lead-result:hover {
            background: var(--muted);
        }

        .visit-search-results {
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

        .visit-service-card {
            border: 1px solid var(--border);
            background: var(--muted);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }

        .visit-service-grid {
            display: grid;
            grid-template-columns: 1.1fr 1fr 1fr auto;
            gap: 0.75rem;
            align-items: end;
        }

        .visit-segmented {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }

        .visit-segment {
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

        .visit-segment.active-hot {
            border-color: var(--destructive);
            color: var(--destructive);
            background: color-mix(in srgb, var(--destructive) 8%, var(--card));
        }

        .visit-segment.active-warm {
            border-color: var(--warning, #f59e0b);
            color: var(--warning-foreground);
            background: var(--warning);
        }

        .visit-segment.active-cold {
            border-color: var(--primary);
            color: var(--primary);
            background: color-mix(in srgb, var(--primary) 8%, var(--card));
        }

        .visit-chip-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .visit-chip {
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--muted-foreground);
        }

        .visit-chip.active {
            border-color: var(--primary);
            background: var(--primary);
            color: var(--primary-foreground);
        }

        .visit-upload-box {
            border: 1px dashed var(--border);
            border-radius: 8px;
            background: var(--muted);
            padding: 1rem;
            text-align: center;
        }

        .visit-photo-trigger {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--foreground);
        }

        .visit-status-note {
            margin-top: 0.75rem;
            border-radius: 8px;
            background: var(--muted);
            color: var(--muted-foreground);
            padding: 0.625rem 0.75rem;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .visit-status-note.success {
            background: var(--success);
            color: var(--success-foreground);
        }

        .dark .visit-segment.active-hot,
        .dark .visit-segment.active-cold {
            background: var(--accent);
        }

        .dark .visit-upload-box,
        .dark .visit-service-card,
        .dark .visit-status-note {
            background: var(--secondary);
        }

        .visit-actions {
            padding: 0 1rem 1rem;
        }

        @media (max-width: 1100px) {
            .visit-dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .visit-dashboard-grid {
                padding: 0;
            }

            .visit-field-grid,
            .visit-service-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="visit-dashboard-grid">
        <div>
            <div class="visit-card">
                <div class="visit-card-header">
                    <h3 class="visit-card-title">
                        <span class="visit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M6 20V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14M9 8h1M14 8h1M9 12h1M14 12h1M9 16h1M14 16h1" />
                            </svg>
                        </span>
                        Client Profile
                    </h3>
                </div>

                <div class="visit-card-body">
                    <div class="visit-field-grid">
                        <div class="visit-field-full">
                            <label class="visit-label">Company / Lead</label>
                            @if($isStandalone)
                                <div style="position: relative;">
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input" placeholder="Search company, client, or phone">
                                    @if(!empty($leads))
                                        <div class="visit-search-results">
                                            @foreach($leads as $l)
                                                <div wire:click="selectLead({{ $l['id'] }})" class="visit-lead-result">
                                                    {{ $l['company_name'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="form-input" style="height: auto; background: var(--muted);">
                                    {{ $companyName }}
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="visit-label">Contact Person</label>
                            <input type="text" wire:model="contactPerson" class="form-input" placeholder="Contact name">
                        </div>
                        <div>
                            <label class="visit-label">Designation</label>
                            <input type="text" wire:model="designation" class="form-input" placeholder="IT Manager">
                        </div>
                        <div>
                            <label class="visit-label">Phone</label>
                            <input type="text" wire:model="phone" class="form-input" placeholder="+880 1xxx-xxxxxx">
                        </div>
                        <div>
                            <label class="visit-label">Email</label>
                            <input type="email" wire:model="email" class="form-input" placeholder="name@company.com">
                        </div>
                        <div class="visit-field-full">
                            <label class="visit-label">Visit Location</label>
                            <input type="text" wire:model="location" class="form-input" placeholder="Client office address">
                        </div>
                    </div>
                </div>
            </div>

            <div class="visit-card">
                <div class="visit-card-header">
                    <h3 class="visit-card-title">
                        <span class="visit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5M8 19V9M12 19V7M16 19v-5M20 19V4" />
                            </svg>
                        </span>
                        Service Usage
                    </h3>
                    <button type="button" wire:click="addServiceRow" class="btn btn-sm btn-secondary">
                        Add Service
                    </button>
                </div>

                <div class="visit-card-body">
                    @foreach($dynamicServices as $index => $svc)
                        <div class="visit-service-card">
                            <div class="visit-service-grid">
                                <div>
                                    <label class="visit-label">Service Type</label>
                                    <select wire:model.live="dynamicServices.{{ $index }}.service_type" class="form-input">
                                        <option value="internet">Dedicated Internet</option>
                                        <option value="m365">Microsoft 365</option>
                                        <option value="ip_phone">IP PBX</option>
                                        <option value="sms">SMS Marketing</option>
                                        <option value="cloud">Cloud Infrastructure</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="visit-label">Current Vendor</label>
                                    <input type="text" wire:model="dynamicServices.{{ $index }}.competitor" class="form-input" placeholder="Vendor name">
                                </div>
                                <div>
                                    @php
                                        $sType = $dynamicServices[$index]['service_type'] ?? 'internet';
                                        $label = match($sType) {
                                            'internet' => 'Bandwidth',
                                            'm365' => 'Licenses',
                                            'ip_phone' => 'Channels',
                                            'sms' => 'Cost / Message',
                                            'cloud' => 'Package',
                                            default => 'Usage Details'
                                        };
                                    @endphp
                                    <label class="visit-label">{{ $label }}</label>
                                    <input type="text" wire:model="dynamicServices.{{ $index }}.details" class="form-input" placeholder="Current usage">
                                </div>
                                <button type="button" wire:click="removeServiceRow({{ $index }})" class="btn btn-icon btn-ghost text-danger" title="Remove service">
                                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="visit-card">
                <div class="visit-card-header">
                    <h3 class="visit-card-title">
                        <span class="visit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5h14v10H8l-3 3V5z" />
                            </svg>
                        </span>
                        Visit Notes
                    </h3>
                </div>
                <div class="visit-card-body">
                    <textarea wire:model="notes" rows="4" class="form-input" style="resize: vertical;" placeholder="Meeting summary, customer feedback, and next steps"></textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="visit-card">
                <div class="visit-card-header">
                    <h3 class="visit-card-title">
                        <span class="visit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-4 7-10V5l-7-3-7 3v6c0 6 7 10 7 10z" />
                            </svg>
                        </span>
                        Visit Verification
                    </h3>
                </div>
                <div class="visit-card-body">
                    <div class="visit-upload-box">
                        @if($visitImage)
                            <div class="visit-photo-trigger" style="color: var(--success);">
                                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5" />
                                </svg>
                                Photo attached
                            </div>
                        @else
                            <label class="visit-photo-trigger">
                                <input type="file" wire:model="visitImage" style="display: none;">
                                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h3l2-2h6l2 2h3v12H4V7z" />
                                    <circle cx="12" cy="13" r="3" />
                                </svg>
                                Attach office photo
                            </label>
                        @endif
                    </div>

                    <div class="visit-status-note {{ $latitude ? 'success' : '' }}">
                        GPS: {{ $latitude ? 'Location captured' : 'Waiting for coordinates' }}
                    </div>
                </div>
            </div>

            <div class="visit-card">
                <div class="visit-card-header">
                    <h3 class="visit-card-title">
                        <span class="visit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3M16 7V3M4 11h16M6 5h12a2 2 0 0 1 2 2v13H4V7a2 2 0 0 1 2-2z" />
                            </svg>
                        </span>
                        Follow Up
                    </h3>
                </div>
                <div class="visit-card-body">
                    <div class="visit-field-grid">
                        <div class="visit-field-full">
                            <label class="visit-label">Status</label>
                            <select wire:model="visitStatus" class="form-input">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="visit-label">Mode</label>
                            <select wire:model="followupType" class="form-input">
                                <option value="call">Call</option>
                                <option value="visit">Visit</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                        <div>
                            <label class="visit-label">Target Date</label>
                            <input type="date" wire:model="nextFollowupDate" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="visit-actions">
                <button wire:click="saveVisit" class="btn btn-primary" style="width: 100%; justify-content: center; min-height: 44px;">
                    Save Daily Visit Log
                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
