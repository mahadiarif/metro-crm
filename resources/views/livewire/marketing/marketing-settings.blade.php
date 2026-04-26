<div class="gateway-settings">
    <style>
        .gateway-settings { font-family: inherit; }
        .gateway-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .gateway-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            color: var(--card-foreground);
            overflow: visible;
        }
        .gateway-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .gateway-card-title {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--foreground);
            margin: 0;
        }
        .gateway-card-body { padding: 1.25rem; }
        .gateway-icon {
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
        .gateway-icon svg,
        .simple-icon { width: 18px; height: 18px; }
        .gateway-field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .gateway-field-full { grid-column: 1 / -1; }
        .gateway-label {
            display: block;
            margin-bottom: 0.45rem;
            color: var(--muted-foreground);
            font-size: 0.75rem;
            font-weight: 600;
        }
        .gateway-panel {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--muted);
            padding: 1rem;
            margin-top: 1rem;
        }
        .gateway-panel-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--foreground);
            font-size: 0.8125rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        .gateway-empty {
            border: 1px dashed var(--border);
            border-radius: 8px;
            background: var(--muted);
            color: var(--muted-foreground);
            padding: 2rem 1rem;
            text-align: center;
        }
        .gateway-empty-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--card);
            color: var(--muted-foreground);
            margin-bottom: 0.75rem;
        }
        .gateway-actions {
            display: flex;
            justify-content: flex-end;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid var(--border);
        }
        @media (max-width: 1100px) {
            .gateway-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 760px) {
            .gateway-field-grid { grid-template-columns: 1fr; }
            .gateway-field-full { grid-column: auto; }
            .gateway-actions .btn { width: 100%; justify-content: center; }
        }
    </style>

    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Enterprise Gateways</h1>
                <p class="page-description">Configure communication gateways for email and SMS distribution.</p>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success" style="margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="saveSettings">
        <div class="gateway-grid">
            <div class="gateway-card">
                <div class="gateway-card-header">
                    <h3 class="gateway-card-title">
                        <span class="gateway-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8 6 8-6" />
                            </svg>
                        </span>
                        SMTP Gateway
                    </h3>
                </div>
                <div class="gateway-card-body">
                    <div class="gateway-field-grid">
                        <div class="gateway-field-full">
                            <label class="gateway-label">Gateway Host</label>
                            <input type="text" wire:model="smtp_host" placeholder="smtp.mandrillapp.com" class="form-input">
                        </div>
                        <div>
                            <label class="gateway-label">Port</label>
                            <input type="number" wire:model="smtp_port" placeholder="587" class="form-input">
                        </div>
                        <div>
                            <label class="gateway-label">Encryption</label>
                            <select wire:model="smtp_encryption" class="form-input">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="gateway-label">Authentication User</label>
                            <input type="text" wire:model="smtp_username" class="form-input">
                        </div>
                        <div>
                            <label class="gateway-label">Secure Token</label>
                            <input type="password" wire:model="smtp_password" class="form-input">
                        </div>
                        <div>
                            <label class="gateway-label">From Address</label>
                            <input type="email" wire:model="smtp_from_address" placeholder="no-reply@company.com" class="form-input">
                        </div>
                        <div>
                            <label class="gateway-label">Sender Name</label>
                            <input type="text" wire:model="smtp_from_name" placeholder="Marketing Team" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="gateway-card">
                <div class="gateway-card-header">
                    <h3 class="gateway-card-title">
                        <span class="gateway-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5h14v10H8l-3 3V5z" />
                            </svg>
                        </span>
                        SMS Gateway
                    </h3>
                </div>
                <div class="gateway-card-body">
                    <div>
                        <label class="gateway-label">Primary Delivery Engine</label>
                        <select wire:model.live="sms_driver" class="form-input">
                            <option value="log">Simulation Engine (Local Log)</option>
                            <option value="twilio">Twilio Cloud Infrastructure</option>
                            <option value="custom_sim">Custom SMS Gateway</option>
                            <option value="gennet">GenNet Priority SMS</option>
                        </select>
                    </div>

                    @if($sms_driver === 'twilio')
                        <div class="gateway-panel">
                            <div class="gateway-panel-title">
                                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zM8 12h8M12 8v8" />
                                </svg>
                                Twilio Integration
                            </div>
                            <div class="gateway-field-grid">
                                <div class="gateway-field-full">
                                    <label class="gateway-label">Account SID</label>
                                    <input type="text" wire:model="twilio_sid" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Auth Token</label>
                                    <input type="password" wire:model="twilio_token" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">From Number</label>
                                    <input type="text" wire:model="twilio_from_number" placeholder="+1..." class="form-input">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($sms_driver === 'gennet')
                        <div class="gateway-panel">
                            <div class="gateway-panel-title">
                                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M5 7h14M5 17h14" />
                                </svg>
                                GenNet High Priority
                            </div>
                            <div class="gateway-field-grid">
                                <div class="gateway-field-full">
                                    <label class="gateway-label">Carrier Token</label>
                                    <input type="text" wire:model="gennet_api_token" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Sender ID / Mask</label>
                                    <input type="text" wire:model="gennet_sid" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Base Domain</label>
                                    <input type="text" wire:model="gennet_domain" class="form-input">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($sms_driver === 'custom_sim')
                        <div class="gateway-panel">
                            <div class="gateway-panel-title">
                                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                                </svg>
                                Custom Gateway
                            </div>
                            <div class="gateway-field-grid">
                                <div class="gateway-field-full">
                                    <label class="gateway-label">Endpoint URL</label>
                                    <input type="text" wire:model="custom_sms_url" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Method</label>
                                    <select wire:model="custom_sms_method" class="form-input">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="gateway-label">API Key</label>
                                    <input type="password" wire:model="custom_sms_api_key" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">API Key Param</label>
                                    <input type="text" wire:model="custom_sms_api_key_param" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Number Param</label>
                                    <input type="text" wire:model="custom_sms_number_param" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Message Param</label>
                                    <input type="text" wire:model="custom_sms_message_param" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Sender ID</label>
                                    <input type="text" wire:model="custom_sms_sender_id" class="form-input">
                                </div>
                                <div>
                                    <label class="gateway-label">Sender ID Param</label>
                                    <input type="text" wire:model="custom_sms_sender_id_param" class="form-input">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($sms_driver === 'log')
                        <div class="gateway-empty" style="margin-top: 1rem;">
                            <div class="gateway-empty-icon">
                                <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6l6 6v10a2 2 0 0 1-2 2z" />
                                </svg>
                            </div>
                            <div style="font-weight: 800; color: var(--foreground);">Development Sandbox</div>
                            <div style="font-size: 0.8125rem; margin-top: 0.25rem;">Distribution is simulated through <code>laravel.log</code>.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="gateway-actions">
            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary" style="min-height: 44px;">
                <span wire:loading wire:target="saveSettings" class="spinner-border spinner-border-sm" style="margin-right: 0.5rem;"></span>
                Save Configuration
            </button>
        </div>
    </form>
</div>
