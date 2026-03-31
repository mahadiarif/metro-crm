<div>
    <div class="page-header">
        <h1 class="page-title">Initiate Campaign</h1>
    </div>

    <form wire:submit.prevent="createCampaign">
        <div class="row g-4">
            {{-- Main Configuration --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label">Campaign Identity</label>
                            <input type="text" wire:model.live="name" class="form-input @error('name') is-invalid @enderror" placeholder="e.g., Summer Discount Outreach">
                            @error('name') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label">Distribution Channel</label>
                            <div class="d-flex gap-4 mt-2">
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model.live="type" value="email">
                                    <span>Email Outreach</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model.live="type" value="sms">
                                    <span>SMS Direct</span>
                                </label>
                            </div>
                            @error('type') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0">Communication Content</label>
                                <div class="d-flex align-items-center gap-2">
                                    <label class="text-xs text-muted-foreground mr-1">Template:</label>
                                    <select wire:model.live="templateId" class="form-select py-1 h-auto text-xs" style="width: auto; min-width: 150px;">
                                        <option value="">None (Custom)</option>
                                        @foreach($templates as $tmpl)
                                            <option value="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" wire:click="openTemplateModal" class="btn btn-icon btn-ghost p-1" title="Save as Template">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>
                            <textarea wire:model.live="campaign_message" rows="10" class="form-input @error('campaign_message') is-invalid @enderror" placeholder="Draft your message content here..."></textarea>
                            @error('campaign_message') <div class="form-error" style="color: var(--danger); font-size: 0.8125rem; margin-top: 0.25rem;">{{ $message }}</div> @enderror
                            
                            @if($type === 'sms')
                                <div class="mt-2 text-end">
                                    <span class="badge {{ strlen($campaign_message) > 160 ? 'badge-danger' : 'badge-secondary' }}">
                                        {{ strlen($campaign_message) }} / 160 Characters
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                    <a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <span wire:loading wire:target="createCampaign" class="spinner-border spinner-border-sm me-2"></span>
                        Initiate Strategy
                    </button>
                </div>
            </div>

            {{-- Audience Sidebar --}}
            <div class="col-lg-4">
                <div class="card shadow-sm" style="position: sticky; top: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">Audience Segmentation</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label text-xs uppercase text-muted-foreground">Pipeline Stage</label>
                            <select wire:model.live="stageId" class="form-select">
                                <option value="">Global Coverage (All Stages)</option>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label text-xs uppercase text-muted-foreground">Market Interest</label>
                            <select wire:model.live="serviceId" class="form-select">
                                <option value="">Global Coverage (All Services)</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label text-xs uppercase text-muted-foreground">Assigned To</label>
                            <select wire:model.live="assignedUser" class="form-select">
                                <option value="">Global Coverage (All Users)</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="p-3 rounded mb-4 text-center" style="background: rgba(var(--primary-rgb), 0.05); border: 1px solid var(--border);">
                            <div class="text-xs font-bold text-primary uppercase mb-1">Target Reach</div>
                            <div style="font-size: 2rem; font-weight: 800; color: var(--text-primary);">{{ number_format($recipientCount) }}</div>
                            <div class="text-xs text-muted-foreground">VERIFIED RECIPIENTS</div>
                        </div>

                        <div class="sample-list">
                            <label class="form-label text-xs uppercase text-muted-foreground mb-2">Segment Samples</label>
                            <div class="d-flex flex-column gap-2">
                                @forelse($sampleRecipients as $sample)
                                    <div class="p-2 border rounded" style="font-size: 0.8125rem; font-weight: 500;">
                                        {{ $sample->client_name }}
                                    </div>
                                @empty
                                    <div class="text-center py-3 text-muted-foreground italic border border-dashed rounded" style="font-size: 0.75rem;">
                                        No targets identified in current scope
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Template Modal --}}
    @if($showTemplateModal)
        <div class="modal-overlay active">
            <div class="card shadow-lg" style="width: 500px; max-width: 95vw; border-radius: 8px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Archive Strategy Template</h3>
                    <button type="button" wire:click="closeTemplateModal" style="background:none; border:none; color:var(--text-secondary); cursor:pointer;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit.prevent="saveTemplate">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" wire:model="newTemplateName" class="form-input" placeholder="e.g., Q4 Follow-up Script">
                            @error('newTemplateName') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Template Content</label>
                            <textarea wire:model="newTemplateContent" class="form-input" rows="6" readonly></textarea>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2 p-3 border-top">
                        <button type="button" wire:click="closeTemplateModal" class="btn btn-secondary">Discard</button>
                        <button type="submit" class="btn btn-primary">Save Template</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
