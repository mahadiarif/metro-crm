<div>
    <form wire:submit.prevent="createCampaign">
        <div class="row">
            <!-- Campaign Details -->
            <div class="col-lg-8">
                <div class="card" style="margin-bottom: 1.5rem;">
                    <div class="card-header border-b" style="background: rgba(var(--muted-rgb, 107, 114, 128), 0.05); padding: 1.25rem 1.5rem;">
                        <h2 class="card-title" style="font-size: 1rem; font-weight: 700;">Campaign Configuration</h2>
                        <p style="font-size: 0.75rem; color: var(--muted-foreground); margin-top: 0.25rem; margin-bottom: 0;">Define your campaign message and channel.</p>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600;">Campaign Name</label>
                            <input type="text" wire:model="name" placeholder="e.g., Summer Special Offer 2024" class="form-input" />
                            @error('name') <p class="form-error" style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label" style="font-weight: 600;">Campaign Type</label>
                            <div style="display: flex; gap: 1rem;">
                                <label style="flex: 1; cursor: pointer;">
                                    <input type="radio" wire:model.live="type" value="email" style="display: none;">
                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid {{ $type === 'email' ? 'var(--primary)' : 'var(--border)' }}; background: {{ $type === 'email' ? 'rgba(var(--primary-rgb, 59, 130, 246), 0.05)' : 'var(--card)' }}; transition: all 0.2s;">
                                        <svg style="width: 24px; height: 24px; color: {{ $type === 'email' ? 'var(--primary)' : 'var(--muted-foreground)' }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span style="font-weight: 600; font-size: 0.875rem; color: {{ $type === 'email' ? 'var(--primary)' : 'var(--foreground)' }};">Email Channel</span>
                                    </div>
                                </label>
                                <label style="flex: 1; cursor: pointer;">
                                    <input type="radio" wire:model.live="type" value="sms" style="display: none;">
                                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem; padding: 1.5rem; border-radius: 0.75rem; border: 2px solid {{ $type === 'sms' ? 'var(--primary)' : 'var(--border)' }}; background: {{ $type === 'sms' ? 'rgba(var(--primary-rgb, 59, 130, 246), 0.05)' : 'var(--card)' }}; transition: all 0.2s;">
                                        <svg style="width: 24px; height: 24px; color: {{ $type === 'sms' ? 'var(--primary)' : 'var(--muted-foreground)' }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        <span style="font-weight: 600; font-size: 0.875rem; color: {{ $type === 'sms' ? 'var(--primary)' : 'var(--foreground)' }};">SMS Channel</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="form-group" style="position: relative;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <label class="form-label" style="font-weight: 600; margin-bottom: 0;">Message Content</label>
                                <div style="display: flex; gap: 0.75rem; align-items: center;">
                                    <div class="form-group mb-0" style="min-width: 180px;">
                                        <select wire:model.live="templateId" class="form-select" style="font-size: 0.7rem; padding: 0.25rem 0.5rem; height: auto;">
                                            <option value="">-- Use Template --</option>
                                            @foreach($templates as $tmpl)
                                                <option value="{{ $tmpl->id }}">{{ $tmpl->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if($type === 'sms')
                                        <span style="font-size: 10px; padding: 0.125rem 0.5rem; border-radius: 9999px; background: {{ strlen($campaign_message) > 160 ? '#ffedd5' : '#dbeafe' }}; color: {{ strlen($campaign_message) > 160 ? '#c2410c' : '#1d4ed8' }}; font-weight: 700; text-transform: uppercase;">
                                            {{ strlen($campaign_message) }}/160 chars
                                        </span>
                                    @endif
                                    <span style="font-size: 10px; color: var(--muted-foreground); font-weight: 500; text-transform: uppercase;">Use <strong>{name}</strong> for tag</span>
                                </div>
                            </div>
                            <textarea wire:model.live="campaign_message" rows="6" placeholder="Hi {name},..." class="form-input" style="resize: none;"></textarea>
                            @error('campaign_message') <p class="form-error" style="color: var(--destructive); font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="card" style="border-color: rgba(var(--primary-rgb, 59, 130, 246), 0.2); background: rgba(var(--primary-rgb, 59, 130, 246), 0.02);">
                    <div class="card-body" style="padding: 1.5rem;">
                        <h3 style="font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Campaign Preview
                        </h3>
                        <div style="background: var(--card); border: 2px dashed var(--border); border-radius: 0.75rem; padding: 1.5rem; position: relative; overflow: hidden;">
                            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--primary); opacity: 0.3;"></div>
                            <div style="font-size: 10px; color: var(--muted-foreground); font-family: monospace; text-transform: uppercase; margin-bottom: 0.75rem; opacity: 0.7;">Live Preview Example:</div>
                            <div style="font-size: 0.875rem; line-height: 1.6; color: var(--foreground); white-space: pre-wrap;">
                                {!! empty($campaign_message) ? '<span style="color: var(--muted-foreground); font-style: italic; opacity: 0.5;">Draft your message to see a preview here...</span>' : str_replace('{name}', '<span style="color: var(--primary); font-weight: 700; text-decoration: underline; text-underline-offset: 4px;">John Doe</span>', e($campaign_message)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Targeting -->
            <div class="col-lg-4">
                    <div class="card-header border-b" style="background: rgba(var(--muted-rgb, 107, 114, 128), 0.05); padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <h2 class="card-title" style="font-size: 1rem; font-weight: 700; margin-bottom: 0;">Target Audience</h2>
                        <div wire:loading wire:target="stageId, serviceId, assignedUser, fromDate, toDate">
                            <svg class="animate-spin" style="width: 1rem; height: 1rem; color: var(--primary);" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 700;">Lead Stage</label>
                            <select wire:model.live.debounce.500ms="stageId" class="form-select">
                                <option value="">All Stages</option>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 700;">Service Interest</label>
                            <select wire:model.live.debounce.500ms="serviceId" class="form-select">
                                <option value="">All Services</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 700;">Assigned Agent</label>
                            <select wire:model.live.debounce.500ms="assignedUser" class="form-select">
                                <option value="">All Agents</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2" style="margin-bottom: 1.5rem;">
                            <div class="col-6">
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 700;">From</label>
                                <input type="date" wire:model.live.debounce.500ms="fromDate" class="form-input" style="font-size: 0.75rem;">
                            </div>
                            <div class="col-6">
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 700;">To</label>
                                <input type="date" wire:model.live.debounce.500ms="toDate" class="form-input" style="font-size: 0.75rem;">
                            </div>
                        </div>

                        <div style="padding-top: 1.25rem; border-top: 1px solid var(--border);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1rem;">
                                <div>
                                    <span style="font-size: 10px; color: var(--muted-foreground); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05rem; display: block; margin-bottom: 0.25rem;">Potential Reach</span>
                                    <div style="display: flex; align-items: baseline; gap: 0.25rem;">
                                        <span style="font-size: 2rem; font-weight: 800; color: var(--primary);">{{ number_format($recipientCount) }}</span>
                                        <span style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">leads</span>
                                    </div>
                                </div>
                                <div style="padding: 0.5rem; background: rgba(var(--primary-rgb, 59, 130, 246), 0.1); border-radius: 0.5rem;">
                                    <svg style="width: 20px; height: 20px; color: var(--primary);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>

                            @if(count($sampleRecipients) > 0)
                                <div style="margin-top: 0.5rem;">
                                    <span style="font-size: 10px; color: var(--muted-foreground); font-weight: 600; text-transform: uppercase; font-style: italic; display: block; margin-bottom: 0.5rem;">Sample Targets:</span>
                                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                        @foreach($sampleRecipients as $sample)
                                            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem; background: rgba(var(--muted-rgb, 107, 114, 128), 0.05); border: 1px solid var(--border); border-radius: 0.5rem; font-size: 0.75rem;">
                                                <div style="width: 1.5rem; height: 1.5rem; border-radius: 9999px; background: rgba(var(--primary-rgb, 59, 130, 246), 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.625rem;">{{ substr($sample->client_name, 0, 1) }}</div>
                                                <div style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 500;">{{ $sample->client_name }}</div>
                                                <div style="font-size: 0.625rem; color: var(--muted-foreground); opacity: 0.7;">{{ strtoupper($type) }}</div>
                                            </div>
                                        @endforeach
                                        @if($recipientCount > 5)
                                            <div style="font-size: 0.625rem; text-align: center; color: var(--muted-foreground); font-style: italic; margin-top: 0.25rem;">& and {{ number_format($recipientCount - 5) }} others...</div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div style="padding: 1.25rem; border-radius: 0.75rem; background: #fff7ed; border: 1px solid #ffedd5; color: #c2410c; text-align: center;">
                                    <p style="font-size: 0.75rem; font-weight: 700; margin-bottom: 0.25rem;">No recipients found.</p>
                                    <p style="font-size: 0.625rem; opacity: 0.8; margin-bottom: 0;">Adjust filters to find target leads.</p>
                                </div>
                            @endif

                            @error('recipients') <p class="form-error" style="color: var(--destructive); font-size: 0.75rem; margin-top: 1rem;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary" style="width: 100%; padding: 1rem; border-radius: 0.75rem; font-weight: 700; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.25rem;">
                         <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div wire:loading wire:target="createCampaign">
                                <svg class="animate-spin" style="width: 1rem; height: 1rem; color: white;" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                            <svg wire:loading.remove wire:target="createCampaign" style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span wire:loading.remove wire:target="createCampaign">Launch Campaign</span>
                            <span wire:loading wire:target="createCampaign">Launching...</span>
                        </div>
                        <span style="font-size: 9px; opacity: 0.7; text-transform: uppercase; letter-spacing: 0.1em;">Ready for delivery</span>
                    </button>

                    <p style="font-size: 0.625rem; color: var(--muted-foreground); text-align: center; line-height: 1.5; padding: 0 1rem;">
                        By launching, you agree to send these messages to <strong>{{ number_format($recipientCount) }}</strong> identified recipients via background processes.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
