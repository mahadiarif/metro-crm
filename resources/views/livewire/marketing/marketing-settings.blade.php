<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Enterprise Gateways</h1>
                <p class="page-description">Configure high-availability communication pillars for email and SMS distribution.</p>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert bg-success-soft border border-success-soft text-success rounded-xl p-4 flex items-center gap-3 shadow-sm mb-4">
            <div class="w-8 h-8 rounded-full bg-success flex items-center justify-center shrink-0 text-white">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="font-bold text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <form wire:submit.prevent="saveSettings">
        <div class="row g-4">
            <!-- Email Gateway -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">SMTP Architecture</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label text-xs uppercase font-bold text-muted-foreground">Gateway Host</label>
                                <input type="text" wire:model="smtp_host" placeholder="e.g., smtp.mandrillapp.com" class="form-input">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-xs uppercase font-bold text-muted-foreground">Port</label>
                                <input type="number" wire:model="smtp_port" placeholder="587" class="form-input">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-xs uppercase font-bold text-muted-foreground">Authentication User</label>
                                <input type="text" wire:model="smtp_username" class="form-input">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-xs uppercase font-bold text-muted-foreground">Secure Token</label>
                                <input type="password" wire:model="smtp_password" class="form-input">
                            </div>
                            
                            <div class="col-12 mt-4 pt-4 border-top">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-xs uppercase font-bold text-muted-foreground">Origin Identity</label>
                                        <input type="email" wire:model="smtp_from_address" placeholder="no-reply@enterprise.com" class="form-input">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-xs uppercase font-bold text-muted-foreground">Sender Signature</label>
                                        <input type="text" wire:model="smtp_from_name" placeholder="Executive Marketing" class="form-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Gateway -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">Cellular Protocol</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label class="form-label text-xs uppercase font-bold text-muted-foreground">Primary Delivery Engine</label>
                            <select wire:model.live="sms_driver" class="form-input">
                                <option value="log">Simulation Engine (Local Log)</option>
                                <option value="twilio">Twilio Cloud Infrastructure</option>
                                <option value="custom_sim">Edge Sim Gateway (Custom)</option>
                                <option value="gennet">GenNet Priority SMS</option>
                            </select>
                        </div>

                        <div class="driver-config-zone">
                            @if($sms_driver === 'twilio')
                                <div class="bg-primary-soft p-4 rounded-xl border border-primary-soft">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="p-1 rounded bg-primary text-white"><svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M21.95 5.05a3.91 3.91 0 00-5.53 0L12 9.47 7.58 5.05a3.91 3.91 0 00-5.53 5.53l4.42 4.42L2.05 19.42a3.91 3.91 0 105.53 5.53l4.42-4.42 4.42 4.42a3.91 3.91 0 005.53-5.53l-4.42-4.42 4.42-4.42a3.91 3.91 0 000-5.53z"/></svg></div>
                                        <span class="text-xs font-black uppercase text-primary tracking-wider">Twilio Integration</span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label text-xs font-bold text-primary">Account Identifier (SID)</label>
                                            <input type="text" wire:model="twilio_sid" class="form-input bg-card">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-xs font-bold text-primary">Secret Key</label>
                                            <input type="password" wire:model="twilio_token" class="form-input bg-card">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-xs font-bold text-primary">Twilio Global Number</label>
                                            <input type="text" wire:model="twilio_from_number" placeholder="+1..." class="form-input bg-card">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($sms_driver === 'gennet')
                                <div class="bg-primary-soft p-4 rounded-xl border border-primary-soft">
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="p-1 rounded bg-primary text-white"><svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg></div>
                                        <span class="text-xs font-black uppercase text-primary tracking-wider">GenNet High Priority</span>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label text-xs font-bold text-primary">Carrier Token</label>
                                            <input type="text" wire:model="gennet_api_token" class="form-input bg-card">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-xs font-bold text-primary">Authenticated Mask</label>
                                            <input type="text" wire:model="gennet_sid" class="form-input bg-card">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-xs font-bold text-primary">Base domain</label>
                                            <input type="text" wire:model="gennet_domain" class="form-input bg-card">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($sms_driver === 'log')
                                <div class="empty-state border-dashed border-2 py-5">
                                    <div class="empty-state-icon bg-muted rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <h4 class="empty-state-title">Development Sandbox</h4>
                                    <p class="empty-state-description">Distribution is simulated via <code>laravel.log</code>. No actual packets will be transmitted.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-end pb-5 pt-4 border-top">
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary py-3 px-5 shadow-lg" style="border-radius: 1rem;">
                <div class="d-flex align-items-center gap-2">
                    <span wire:loading wire:target="saveSettings" class="spinner-border spinner-border-sm"></span>
                    <span style="font-weight: 800; font-size: 1.1rem;">Synchronize Configuration</span>
                </div>
            </button>
        </div>
    </form>
</div>
