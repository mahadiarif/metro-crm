<div class="elite-modal-wrapper" style="font-family: 'Inter', system-ui, sans-serif;">
    <style>
        /* Elite Modal Architecture */
        .elite-modal-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 32px; padding: 10px; }
        
        .elite-card { 
            background: #ffffff; border-radius: 24px; border: 1px solid #f1f5f9; 
            padding: 28px; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
            margin-bottom: 24px;
        }
        .elite-card:hover { border-color: #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
        
        .elite-label { display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.1em; }
        .elite-input { 
            width: 100%; padding: 12px 16px; font-size: 13px; font-weight: 600; color: #0f172a; 
            background: #f8fafc; border: 1.5px solid #f1f5f9; border-radius: 12px; outline: none; transition: 0.2s; 
        }
        .elite-input:focus { border-color: #0f172a; background: #fff; box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.04); }
        .elite-input::placeholder { color: #cbd5e1; }
        
        /* Elite Components */
        .elite-section-title { 
            display: flex; align-items: center; gap: 12px; margin-bottom: 24px; 
            font-size: 13px; font-weight: 900; color: #0f172a; letter-spacing: -0.01em;
        }
        .elite-icon-box { 
            width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9; 
            display: flex; align-items: center; justify-content: center; color: #64748b;
        }
        
        /* Elite Temperature Control */
        .elite-temp-box { display: flex; gap: 10px; margin-bottom: 28px; }
        .elite-temp-item { 
            flex: 1; padding: 20px 10px; border-radius: 16px; cursor: pointer; text-align: center; 
            transition: 0.3s; border: 2px solid #f8fafc; background: #f8fafc;
        }
        .elite-temp-item:hover { transform: translateY(-3px); background: #f1f5f9; }
        .elite-temp-item.active-hot { background: #fff1f2; border-color: #f43f5e; box-shadow: 0 10px 15px -3px rgba(244, 63, 94, 0.1); }
        .elite-temp-item.active-warm { background: #fff7ed; border-color: #f59e0b; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.1); }
        .elite-temp-item.active-cold { background: #eff6ff; border-color: #3b82f6; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1); }

        /* Dynamic Service Rows */
        .elite-service-card { background: #fcfdfe; border: 1px solid #f1f5f9; border-radius: 16px; padding: 20px; margin-bottom: 12px; position: relative; }
        .elite-service-card:hover { border-color: #e2e8f0; }
        
        .elite-save-btn { 
            background: #0f172a; color: #fff; border: none; padding: 16px 32px; border-radius: 14px; 
            font-weight: 800; font-size: 14px; cursor: pointer; transition: 0.3s; 
            display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.3);
        }
        .elite-save-btn:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 15px 30px -10px rgba(15, 23, 42, 0.4); }

        .trash-btn { 
            background: #fff; width: 34px; height: 34px; border-radius: 8px; border: 1px solid #fee2e2; color: #f87171;
            display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;
        }
        .trash-btn:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

        @media (max-width: 1024px) { .elite-modal-grid { grid-template-columns: 1fr; } }
    </style>

    <div class="elite-modal-grid">
        <!-- Left Column: Core Intelligence -->
        <div class="elite-column">
            <!-- Identity Section -->
            <div class="elite-card">
                <div class="elite-section-title">
                    <div class="elite-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    Enterprise Profile & POC
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="grid-column: span 2;">
                        <label class="elite-label">Corporate Entity</label>
                        @if($isStandalone)
                            <div style="position: relative;">
                                <input type="text" wire:model.live.debounce.300ms="search" class="elite-input" placeholder="Initiate search for company entity...">
                                @if(!empty($leads))
                                    <div style="position: absolute; width: 100%; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); z-index: 100; margin-top: 8px; overflow: hidden;">
                                        @foreach($leads as $l)
                                            <div wire:click="selectLead({{ $l['id'] }})" style="padding: 14px 18px; border-bottom: 1px solid #f8fafc; cursor: pointer; font-size: 13px; font-weight: 700; color: #1e293b; transition: 0.2s;" onmouseover="this.style.background='#fcfdfe'" onmouseout="this.style.background='white'">{{ $l['company_name'] }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div style="padding: 12px 16px; background: #fcfdfe; border-radius: 12px; font-weight: 800; color: #0f172a; border: 1.5px solid #f1f5f9; font-size: 14px;">{{ $companyName }}</div>
                        @endif
                    </div>
                    <div><label class="elite-label">Contact Executive</label><input type="text" wire:model="contactPerson" class="elite-input" placeholder="John Doe"></div>
                    <div><label class="elite-label">Official Designation</label><input type="text" wire:model="designation" class="elite-input" placeholder="e.g. IT Manager"></div>
                    <div><label class="elite-label">Mobile Network</label><input type="text" wire:model="phone" class="elite-input" placeholder="+880 1xxx-xxxxxx"></div>
                    <div><label class="elite-label">Corporate Email</label><input type="email" wire:model="email" class="elite-input" placeholder="executive@corporate.com"></div>
                    <div style="grid-column: span 2;"><label class="elite-label">Deployment Address</label><input type="text" wire:model="location" class="elite-input" placeholder="Physical location of client premises..."></div>
                </div>
            </div>

            <!-- Service Intelligence Section -->
            <div class="elite-card">
                <div class="elite-section-title" style="justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="elite-icon-box" style="background: #0f172a; color: #fff;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6M6 20V10M18 20V4"/></svg></div>
                        Service Usage Intelligence
                    </div>
                    <button type="button" wire:click="addServiceRow" class="elite-save-btn" style="padding: 8px 16px; font-size: 10px; border-radius: 8px; box-shadow: none;">
                        + ADD SERVICE
                    </button>
                </div>

                <div class="services-container">
                    @foreach($dynamicServices as $index => $svc)
                    <div class="elite-service-card">
                        <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr auto; gap: 16px; align-items: end;">
                            <div>
                                <label class="elite-label">Category</label>
                                <select wire:model.live="dynamicServices.{{ $index }}.service_type" class="elite-input" style="height: 44px; font-size: 12px;">
                                    <option value="internet">🌐 Dedicated Internet</option>
                                    <option value="m365">📦 Microsoft 365 Sub</option>
                                    <option value="ip_phone">📞 IP PBX Solution</option>
                                    <option value="sms">💬 SMS Marketing</option>
                                    <option value="cloud">☁️ Cloud Infrastructure</option>
                                </select>
                            </div>
                            <div>
                                <label class="elite-label">Current Vendor</label>
                                <input type="text" wire:model="dynamicServices.{{ $index }}.competitor" class="elite-input" style="height: 44px;" placeholder="e.g. AmberIT">
                            </div>
                            <div>
                                @php
                                    $sType = $dynamicServices[$index]['service_type'] ?? 'internet';
                                    $label = match($sType) {
                                        'internet' => 'Bandwidth (Mbps)',
                                        'm365' => 'License Count',
                                        'ip_phone' => 'No. of Channels',
                                        'sms' => 'Cost / Message',
                                        'cloud' => 'Storage / Package',
                                        default => 'Usage Details'
                                    };
                                @endphp
                                <label class="elite-label">{{ $label }}</label>
                                <input type="text" wire:model="dynamicServices.{{ $index }}.details" class="elite-input" style="height: 44px;" placeholder="Current usage...">
                            </div>
                            <button type="button" wire:click="removeServiceRow({{ $index }})" class="trash-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="elite-card">
                 <label class="elite-label">Executive Field Notes</label>
                 <textarea wire:model="notes" rows="3" class="elite-input" style="padding: 16px; resize: none;" placeholder="Synthesize critical feedback and meeting highlights here..."></textarea>
            </div>
        </div>

        <!-- Right Column: Strategic Parameters -->
        <div class="elite-column">
            <!-- Sentiment Gauge -->
            <div class="elite-card">
                <div class="elite-section-title">
                    <div class="elite-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></div>
                    Sentiment Analysis
                </div>
                <div class="elite-temp-box">
                    <div wire:click="$set('leadTemperature', 'hot')" class="elite-temp-item {{ $leadTemperature === 'hot' ? 'active-hot' : '' }}">
                        <div style="font-size: 24px; margin-bottom: 8px;">🔥</div>
                        <div class="elite-label" style="color: {{ $leadTemperature === 'hot' ? '#be123c' : '#94a3b8' }}; margin: 0;">HOT</div>
                    </div>
                    <div wire:click="$set('leadTemperature', 'warm')" class="elite-temp-item {{ $leadTemperature === 'warm' ? 'active-warm' : '' }}">
                        <div style="font-size: 24px; margin-bottom: 8px;">🌤️</div>
                        <div class="elite-label" style="color: {{ $leadTemperature === 'warm' ? '#b45309' : '#94a3b8' }}; margin: 0;">WARM</div>
                    </div>
                    <div wire:click="$set('leadTemperature', 'cold')" class="elite-temp-item {{ $leadTemperature === 'cold' ? 'active-cold' : '' }}">
                        <div style="font-size: 24px; margin-bottom: 8px;">❄️</div>
                        <div class="elite-label" style="color: {{ $leadTemperature === 'cold' ? '#1d4ed8' : '#94a3b8' }}; margin: 0;">COLD</div>
                    </div>
                </div>

                <label class="elite-label">Identified Pain Points</label>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach(['price' => 'Price Barrier', 'support' => 'SLA Misalignment', 'downtime' => 'Consistency Issues', 'coverage' => 'Black-spot Area'] as $k => $v)
                        <div wire:click="$toggle('painPoints.{{ $k }}')" style="cursor: pointer; padding: 8px 16px; border-radius: 12px; font-size: 11px; font-weight: 800; border: 2px solid {{ in_array($k, (array)$painPoints) ? '#0f172a' : '#f1f5f9' }}; {{ in_array($k, (array)$painPoints) ? 'background:#0f172a; color:#fff;' : 'background:#fff; color:#64748b;' }} transition: 0.2s;">
                            {{ $v }}
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Auth & Roadmap -->
            <div class="elite-card">
                 <div class="elite-section-title">
                    <div class="elite-icon-box"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                    Visit Verification
                </div>
                <div style="padding: 24px; background: #fcfdfe; border: 2px dashed #e2e8f0; border-radius: 20px; text-align: center;">
                    @if($visitImage)
                        <div style="font-weight: 900; font-size: 11px; color: #10b981; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            PHOTOGRAPH SYNCED
                        </div>
                    @else
                        <label style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <input type="file" wire:model="visitImage" style="display: none;">
                            <div style="width: 44px; height: 44px; background: #fff; border-radius: 50%; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; font-size: 20px;">📸</div>
                            <span style="font-size: 10px; font-weight: 900; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">Acquire Office Photo</span>
                        </label>
                    @endif
                </div>
                <div style="margin-top: 14px; background: {{ $latitude ? '#f0fdf4' : '#fff7ed' }}; padding: 10px; border-radius: 10px; text-align: center;">
                    <span style="font-size: 10px; font-weight: 800; color: {{ $latitude ? '#15803d' : '#c2410c' }}; text-transform: uppercase;">
                        GPS: {{ $latitude ? 'Geotag identification complete' : 'Requesting field coordinates...' }}
                    </span>
                </div>
            </div>

            <!-- Future roadmap -->
            <div class="elite-card" style="background: #0f172a; border: none; box-shadow: 0 20px 25px -5px rgba(15,23,42,0.2);">
                <div class="elite-section-title" style="color: #fff;">
                    <div class="elite-icon-box" style="background: rgba(255,255,255,0.1); color: #fff;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19 7-7-7-7"/><path d="M19 12H5"/></svg></div>
                    Roadmap Planning
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div style="grid-column: span 2;">
                        <label class="elite-label" style="color: rgba(255,255,255,0.5);">Strategic Status</label>
                        <select wire:model="visitStatus" class="elite-input" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff; height: 48px;">
                            <option value="pending">Awaiting Sync</option>
                            <option value="in_progress">Developing Interaction</option>
                            <option value="completed">Interaction Finalized</option>
                        </select>
                    </div>
                    <div>
                        <label class="elite-label" style="color: rgba(255,255,255,0.5);">Next Mode</label>
                        <select wire:model="followupType" class="elite-input" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff; height: 48px;">
                            <option value="call">Call Review</option>
                            <option value="visit">Field Deployment</option>
                            <option value="email">Formal proposal</option>
                        </select>
                    </div>
                    <div>
                        <label class="elite-label" style="color: rgba(255,255,255,0.5);">Target Date</label>
                        <input type="date" wire:model="nextFollowupDate" class="elite-input" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff; height: 48px;">
                    </div>
                </div>
            </div>

            <button wire:click="saveVisit" class="elite-save-btn" style="width: 100%; height: 56px; font-size: 15px;">
                Finalize Visit Report
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m12 5 7 7-7 7"/><path d="M19 12H5"/></svg>
            </button>
        </div>
    </div>
</div>
