<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Marketing Configuration</h1>
                <p class="page-description">Manage enterprise communication gateways and provider credentials.</p>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl p-4 flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="font-bold text-sm">{{ session('success') }}</div>
        </div>
    @endif

    <form wire:submit.prevent="saveSettings">
        <div class="row g-4">
            <!-- Email Configuration -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header border-b" style="padding: 1.25rem 1.5rem;">
                        <h3 class="card-title" style="font-size: 0.875rem; font-weight: 700;">Email Gateway</h3>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">SMTP Host</label>
                                <input type="text" wire:model="smtp_host" placeholder="smtp.mailtrap.io" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">SMTP Port</label>
                                <input type="number" wire:model="smtp_port" placeholder="587" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">Username</label>
                                <input type="text" wire:model="smtp_username" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">Password</label>
                                <input type="password" wire:model="smtp_password" class="form-control">
                            </div>
                            <div class="col-12" style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">From Address</label>
                                        <input type="email" wire:model="smtp_from_address" placeholder="noreply@metronet.com" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">Sender Name</label>
                                        <input type="text" wire:model="smtp_from_name" placeholder="MetroNet Marketing" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Configuration -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header border-b" style="padding: 1.25rem 1.5rem;">
                        <h3 class="card-title" style="font-size: 0.875rem; font-weight: 700;">SMS Gateway</h3>
                    </div>
                    <div class="card-body" style="padding: 1.5rem;">
                        <div style="margin-bottom: 2rem;">
                            <label class="form-label" style="font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase;">Preferred Driver</label>
                            <select wire:model.live="sms_driver" class="form-select">
                                <option value="log">Development Log (Local Testing)</option>
                                <option value="twilio">Twilio Cloud API</option>
                                <option value="custom_sim">Private Gateway (Custom API)</option>
                                <option value="gennet">GenNet Push SMS (Premium)</option>
                            </select>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            @if($sms_driver === 'twilio')
                                <div style="background: rgba(var(--primary-rgb, 59, 130, 246), 0.05); border-radius: 1rem; padding: 1.25rem; border: 1px solid var(--border);">
                                    <h4 style="font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; color: var(--primary); margin-bottom: 1.25rem;">Twilio Configuration</h4>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Account SID</span>
                                            <input type="text" wire:model="twilio_sid" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Auth Token</span>
                                            <input type="password" wire:model="twilio_token" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">From Number</span>
                                            <input type="text" wire:model="twilio_from_number" placeholder="+1234567890" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($sms_driver === 'custom_sim')
                                <div style="background: var(--muted); opacity: 0.1; border-radius: 1rem; padding: 1.25rem;">
                                    <h4 style="font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; color: var(--foreground); margin-bottom: 1.25rem;">Custom API Configuration</h4>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Endpoint URL</span>
                                            <input type="text" wire:model="custom_sms_url" placeholder="http://api.gateway.net/v1/send" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">HTTP Method</span>
                                            <select wire:model="custom_sms_method" class="form-select">
                                                <option value="GET">GET</option>
                                                <option value="POST">POST</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">API Key</span>
                                            <input type="password" wire:model="custom_sms_api_key" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <div class="row g-2">
                                                <div class="col-4">
                                                    <input type="text" wire:model="custom_sms_api_key_param" placeholder="api_key" class="form-control form-control-sm" style="font-family: monospace; font-size: 10px;">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" wire:model="custom_sms_number_param" placeholder="to" class="form-control form-control-sm" style="font-family: monospace; font-size: 10px;">
                                                </div>
                                                <div class="col-4">
                                                    <input type="text" wire:model="custom_sms_message_param" placeholder="message" class="form-control form-control-sm" style="font-family: monospace; font-size: 10px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($sms_driver === 'gennet')
                                <div style="background: rgba(var(--primary-rgb, 59, 130, 246), 0.05); border-radius: 1rem; padding: 1.25rem; border: 1px solid var(--border);">
                                    <h4 style="font-size: 0.6875rem; font-weight: 800; text-transform: uppercase; color: var(--primary); margin-bottom: 1.25rem;">GenNet API Configuration</h4>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">API Token</span>
                                            <input type="text" wire:model="gennet_api_token" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">Masking Name</span>
                                            <input type="text" wire:model="gennet_sid" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <span style="font-size: 0.625rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">API Domain</span>
                                            <input type="text" wire:model="gennet_domain" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($sms_driver === 'log')
                                <div style="padding: 2rem; border: 2px dashed var(--border); border-radius: 1rem; text-align: center; opacity: 0.6;">
                                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--muted-foreground);">Debug Mode (Local Log)</div>
                                    <div style="font-size: 0.625rem; color: var(--muted-foreground); margin-top: 0.25rem;">Messages will be saved to storage/logs/laravel.log</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 3rem; display: flex; justify-content: flex-end; padding-top: 2rem; border-top: 1px solid var(--border); margin-bottom: 3rem;">
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary" style="padding: 1rem 3rem; font-weight: 700; font-size: 1rem; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(var(--primary-rgb, 59, 130, 246), 0.4);">
                 <span wire:loading.remove wire:target="saveSettings">Commit Configuration</span>
                 <span wire:loading wire:target="saveSettings">Saving...</span>
            </button>
        </div>
    </form>
</div>
