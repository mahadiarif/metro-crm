<div class="campaign-create-form">
    <style>
        .campaign-create-form { font-family: inherit; }
        .campaign-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(320px, 0.75fr);
            gap: 1rem;
        }
        .campaign-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            color: var(--card-foreground);
            margin-bottom: 1rem;
            overflow: visible;
        }
        .campaign-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .campaign-card-title {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--foreground);
            margin: 0;
        }
        .campaign-card-body { padding: 1.25rem; }
        .campaign-icon {
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
        .campaign-icon svg,
        .simple-icon { width: 18px; height: 18px; }
        .campaign-field { margin-bottom: 1rem; }
        .campaign-label {
            display: block;
            margin-bottom: 0.45rem;
            color: var(--muted-foreground);
            font-size: 0.75rem;
            font-weight: 600;
        }
        .campaign-channel-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }
        .campaign-channel {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--card);
            color: var(--muted-foreground);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem;
            font-size: 0.875rem;
            font-weight: 700;
        }
        .campaign-channel.active {
            border-color: var(--primary);
            background: var(--primary);
            color: var(--primary-foreground);
        }
        .campaign-template-row {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .campaign-reach-box {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--muted);
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        .campaign-reach-value {
            color: var(--foreground);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.1;
        }
        .campaign-sample-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .campaign-sample-item {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--background);
            color: var(--foreground);
            padding: 0.625rem 0.75rem;
            font-size: 0.8125rem;
            font-weight: 600;
        }
        .campaign-empty {
            border: 1px dashed var(--border);
            border-radius: 8px;
            color: var(--muted-foreground);
            padding: 1rem;
            text-align: center;
            font-size: 0.8125rem;
        }
        .campaign-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.625rem;
            margin-top: 1rem;
        }
        .campaign-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 80;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: color-mix(in srgb, var(--foreground) 35%, transparent);
        }
        .campaign-modal {
            width: 520px;
            max-width: 95vw;
        }
        @media (max-width: 1100px) {
            .campaign-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 760px) {
            .campaign-channel-grid,
            .campaign-template-row { grid-template-columns: 1fr; }
            .campaign-template-row { flex-direction: column; align-items: stretch; }
            .campaign-actions { flex-direction: column-reverse; }
            .campaign-actions .btn { width: 100%; justify-content: center; }
        }
    </style>

    <form wire:submit.prevent="createCampaign">
        <div class="campaign-grid">
            <div>
                <div class="campaign-card">
                    <div class="campaign-card-header">
                        <h3 class="campaign-card-title">
                            <span class="campaign-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 0 1-3.417.592l-2.147-6.15M18 13a3 3 0 1 0 0-6M5.436 13.683A4 4 0 0 1 7 6h2c4 0 7.5-1.2 9-3v14c-1.5-1.8-5-3-9-3H7a4 4 0 0 1-1.564-.317z" />
                                </svg>
                            </span>
                            Campaign Setup
                        </h3>
                    </div>
                    <div class="campaign-card-body">
                        <div class="campaign-field">
                            <label class="campaign-label">Campaign Name</label>
                            <input type="text" wire:model.live="name" class="form-input @error('name') is-invalid @enderror" placeholder="e.g. Summer Discount Outreach">
                            @error('name') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="campaign-field">
                            <label class="campaign-label">Distribution Channel</label>
                            <div class="campaign-channel-grid">
                                <label class="campaign-channel {{ $type === 'email' ? 'active' : '' }}">
                                    <input type="radio" wire:model.live="type" value="email" style="display: none;">
                                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6" />
                                    </svg>
                                    Email Outreach
                                </label>
                                <label class="campaign-channel {{ $type === 'sms' ? 'active' : '' }}">
                                    <input type="radio" wire:model.live="type" value="sms" style="display: none;">
                                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5h14v10H8l-3 3V5z" />
                                    </svg>
                                    SMS Direct
                                </label>
                            </div>
                            @error('type') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="campaign-card">
                    <div class="campaign-card-header">
                        <h3 class="campaign-card-title">
                            <span class="campaign-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5V5a2 2 0 0 1 2-2h10l4 4v12.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M8 13h8M8 17h6" />
                                </svg>
                            </span>
                            Message Content
                        </h3>
                        <div class="campaign-template-row">
                            <select wire:model.live="templateId" class="form-input" style="min-width: 180px;">
                                <option value="">No Template</option>
                                @foreach($templates as $tmpl)
                                    <option value="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" wire:click="openTemplateModal" class="btn btn-icon btn-ghost" title="Save as template">
                                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="campaign-card-body">
                        <textarea wire:model.live="campaign_message" rows="12" class="form-input @error('campaign_message') is-invalid @enderror" style="resize: vertical;" placeholder="Draft your campaign message here..."></textarea>
                        @error('campaign_message') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        @error('recipients') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror

                        @if($type === 'sms')
                            <div style="display: flex; justify-content: flex-end; margin-top: 0.75rem;">
                                <span class="badge {{ strlen($campaign_message) > 160 ? 'badge-danger' : 'badge-secondary' }}">
                                    {{ strlen($campaign_message) }} / 160 Characters
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="campaign-actions">
                    <a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading wire:target="createCampaign" class="spinner-border spinner-border-sm" style="margin-right: 0.5rem;"></span>
                        Create Campaign
                    </button>
                </div>
            </div>

            <div>
                <div class="campaign-card" style="position: sticky; top: 4rem;">
                    <div class="campaign-card-header">
                        <h3 class="campaign-card-title">
                            <span class="campaign-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 0 0-5.36-1.86M17 20H7m10 0v-2c0-.66-.13-1.28-.36-1.86M7 20H2v-2a3 3 0 0 1 5.36-1.86M7 20v-2c0-.66.13-1.28.36-1.86m0 0a5 5 0 0 1 9.28 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                </svg>
                            </span>
                            Audience
                        </h3>
                    </div>
                    <div class="campaign-card-body">
                        <div class="campaign-field">
                            <label class="campaign-label">Pipeline Stage</label>
                            <select wire:model.live="stageId" class="form-input">
                                <option value="">All Stages</option>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="campaign-field">
                            <label class="campaign-label">Market Interest</label>
                            <select wire:model.live="serviceId" class="form-input">
                                <option value="">All Services</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="campaign-field">
                            <label class="campaign-label">Assigned To</label>
                            <select wire:model.live="assignedUser" class="form-input">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="campaign-reach-box">
                            <div class="campaign-label" style="margin-bottom: 0.25rem;">Target Reach</div>
                            <div class="campaign-reach-value">{{ number_format($recipientCount) }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted-foreground); font-weight: 700;">Verified recipients</div>
                        </div>

                        <div>
                            <label class="campaign-label">Segment Samples</label>
                            <div class="campaign-sample-list">
                                @forelse($sampleRecipients as $sample)
                                    <div class="campaign-sample-item">{{ $sample->client_name }}</div>
                                @empty
                                    <div class="campaign-empty">No targets identified in current scope</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if($showTemplateModal)
        <div class="campaign-modal-overlay">
            <div class="campaign-card campaign-modal">
                <div class="campaign-card-header">
                    <h3 class="campaign-card-title">Save Template</h3>
                    <button type="button" wire:click="closeTemplateModal" class="btn btn-icon btn-ghost" title="Close">
                        <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="saveTemplate">
                    <div class="campaign-card-body">
                        <div class="campaign-field">
                            <label class="campaign-label">Template Name</label>
                            <input type="text" wire:model="newTemplateName" class="form-input" placeholder="e.g. Q4 Follow-up Script">
                            @error('newTemplateName') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>
                        <div class="campaign-field" style="margin-bottom: 0;">
                            <label class="campaign-label">Template Content</label>
                            <textarea wire:model="newTemplateContent" class="form-input" rows="7" readonly></textarea>
                        </div>
                    </div>
                    <div class="card-footer" style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                        <button type="button" wire:click="closeTemplateModal" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
