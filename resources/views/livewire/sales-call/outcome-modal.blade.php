<div class="elite-modal-wrapper" style="font-family: 'Inter', system-ui, sans-serif;">
    <div class="fixed inset-0 z-50 overflow-y-auto {{ $isModalOpen ? 'd-block' : 'd-none' }}" 
         style="background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; padding: 2rem;">
        
        <style>
            /* Elite Architecture Specs */
            .elite-modal-main { width: 100%; max-width: 1000px; background: #ffffff; border-radius: 32px; overflow: hidden; animation: eliteSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 50px 100px -20px rgba(0,0,0,0.25); }
            @keyframes eliteSlideUp { from { opacity: 0; transform: translateY(30px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
            
            .elite-header { background: #ffffff; border-bottom: 1px solid #f1f5f9; padding: 32px 40px; display: flex; justify-content: space-between; align-items: center; }
            .elite-body { max-height: 75vh; overflow-y: auto; padding: 40px; background: #ffffff; scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
            .elite-footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 24px 40px; display: flex; justify-content: flex-end; gap: 16px; }

            .elite-card { background: #ffffff; border-radius: 24px; border: 1px solid #f1f5f9; padding: 24px; margin-bottom: 24px; transition: 0.3s; }
            .elite-card:hover { border-color: #e2e8f0; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); }

            .elite-label { display: block; font-size: 10px; font-weight: 850; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.1em; }
            .elite-input { width: 100%; padding: 12px 16px; font-size: 13px; font-weight: 600; color: #0f172a; background: #f8fafc; border: 1.5px solid #f1f5f9; border-radius: 12px; outline: none; transition: 0.2s; }
            .elite-input:focus { border-color: #0f172a; background: #fff; box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.04); }
            
            /* Outcome Selectors */
            .elite-outcome-grid { display: flex; gap: 12px; margin-bottom: 32px; }
            .elite-outcome-item { flex: 1; padding: 20px; border-radius: 16px; cursor: pointer; text-align: center; border: 2.5px solid #f8fafc; background: #f8fafc; transition: 0.3s; position: relative; }
            .elite-outcome-item.active { background: #ffffff; border-color: #0f172a; box-shadow: 0 15px 25px -5px rgba(0,0,0,0.05); transform: translateY(-2px); }
            .elite-outcome-item.active .outcome-label { color: #0f172a !important; }

            .elite-save-btn { background: #0f172a; color: #fff; border: none; padding: 14px 36px; border-radius: 14px; font-weight: 800; font-size: 14px; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 10px; }
            .elite-save-btn:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 15px 30px -10px rgba(15,23,42,0.3); }
        </style>

        <div class="elite-modal-main">
            <!-- Expert Header -->
            <div class="elite-header">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="background: #0f172a; width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <div>
                        <h5 style="margin: 0; font-weight: 900; font-size: 1.3rem; color: #0f172a; letter-spacing: -0.02em;">Call Outcome Intelligence</h5>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                            <span style="background: #f1f5f9; color: #475569; font-size: 10px; font-weight: 900; padding: 2px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Elite Sync Active</span>
                            <span style="color: #94a3b8; font-size: 11px; font-weight: 600;">Interfacing with <strong style="color: #0f172a;">{{ $leadName }}</strong></span>
                        </div>
                    </div>
                </div>
                <button wire:click="close" style="background: #f8fafc; border: 1.5px solid #f1f5f9; width: 40px; height: 40px; border-radius: 12px; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; transition: 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='white'; this.style.borderColor='#ef4444'" onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b'; this.style.borderColor='#f1f5f9'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="elite-body">
                <div style="display: grid; grid-template-columns: 1fr; gap: 32px;">
                    
                    <!-- Identity Section -->
                    <div class="elite-card">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                            <div style="background: #f1f5f9; width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <span class="elite-label" style="margin: 0;">Market Identity Profile</span>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                            <div><label class="elite-label">Organization</label><input type="text" value="{{ $leadName }}" class="elite-input" style="background: rgba(241,245,249,0.5); color: #94a3b8;" readonly></div>
                            <div><label class="elite-label">Mailing Address</label><input type="text" wire:model="address" class="elite-input" placeholder="Field HQ Location"></div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
                            <div><label class="elite-label">POC Name</label><input type="text" wire:model="contactPerson" class="elite-input" placeholder="Executive Name"></div>
                            <div><label class="elite-label">Role</label><input type="text" wire:model="designation" class="elite-input" placeholder="Designation"></div>
                            <div><label class="elite-label">Mobile</label><input type="text" wire:model="phone" class="elite-input" placeholder="+88xx"></div>
                            <div><label class="elite-label">Corporate Email</label><input type="email" wire:model="email" class="elite-input" placeholder="email@corp.com"></div>
                        </div>
                    </div>

                    <!-- Market Intelligence Row -->
                    <div class="elite-card" style="border: 1.5px solid #0f172a;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="background: #0f172a; width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6M6 20V10M18 20V4"/></svg>
                                </div>
                                <span class="elite-label" style="color: #0f172a; margin: 0;">Market Intelligence matrix</span>
                            </div>
                            <button type="button" wire:click="addServiceRow" class="elite-save-btn" style="padding: 8px 16px; font-size: 10px; border-radius: 8px; box-shadow: none;">
                                + ADD PRODUCT
                            </button>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @foreach($dynamicServices as $index => $svc)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px;">
                                <div style="display: grid; grid-template-columns: 1.2fr 1fr 1.2fr auto; gap: 16px; align-items: end;">
                                    <div>
                                        <label class="elite-label">Service Type</label>
                                        <select wire:model.live="dynamicServices.{{ $index }}.service_type" class="elite-input" style="height: 48px; border-color: #e2e8f0;">
                                            <option value="internet">🌐 Dedicated Internet</option>
                                            <option value="m365">📦 M365 Business</option>
                                            <option value="ip_phone">📞 IP Solution</option>
                                            <option value="sms">💬 SMS Gateway</option>
                                            <option value="cloud">☁️ Cloud PBX/ERP</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="elite-label">Active Competitor</label>
                                        <input type="text" wire:model="dynamicServices.{{ $index }}.competitor" class="elite-input" style="height: 48px; border-color: #e2e8f0;" placeholder="Current ISP">
                                    </div>
                                    <div>
                                        @php
                                            $sType = $dynamicServices[$index]['service_type'] ?? 'internet';
                                            $label = match($sType) {
                                                'internet' => 'Bandwidth (Mbps)',
                                                'm365' => 'License Seat Count',
                                                'ip_phone' => 'No. of Channels',
                                                'sms' => 'Rate per Unit',
                                                'cloud' => 'Storage / Spec',
                                                default => 'Usage Matrix'
                                            };
                                        @endphp
                                        <label class="elite-label">{{ $label }}</label>
                                        <input type="text" wire:model="dynamicServices.{{ $index }}.details" class="elite-input" style="height: 48px; border-color: #e2e8f0;" placeholder="Details...">
                                    </div>
                                    <button type="button" wire:click="removeServiceRow({{ $index }})" style="background: white; border: 1px solid #fee2e2; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #f87171; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='white'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Strategy Layer -->
                    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 32px;">
                        <div class="elite-card" style="margin: 0; background: #fcfdfe;">
                            <span class="elite-label">Consultation Roadmap</span>
                            <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 24px;">
                                @for($i = 1; $i <= 3; $i++)
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 14px; transition: 0.3s;
                                        {{ $i < $callCount ? 'background: #10b981; color: white; box-shadow: 0 4px 10px rgba(16,185,129,0.3);' : ($i === $callCount ? 'background: #0f172a; color: white; box-shadow: 0 4px 10px rgba(15,23,42,0.3);' : 'background: #f1f5f9; color: #cbd5e1;') }}">
                                        @if($i < $callCount) <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> @else {{ $i }} @endif
                                    </div>
                                    <div style="font-size: 13px; font-weight: 800; {{ $i === $callCount ? 'color: #0f172a;' : 'color: #94a3b8;' }}">Call Interaction #{{ $i }}</div>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <span class="elite-label">Strategic Outcome Allocation</span>
                            <div class="elite-outcome-grid" style="margin-top: 12px;">
                                @foreach(['follow_up' => 'Retry Case', 'service_request' => 'Pipeline Gain', 'not_interested' => 'Lost Account'] as $val => $label)
                                <div wire:click="$set('outcome', '{{ $val }}')" class="elite-outcome-item {{ $outcome === $val ? 'active' : '' }}">
                                    <div style="font-weight: 900; text-transform: uppercase; font-size: 11px; color: #94a3b8;" class="outcome-label">
                                        {{ $label }}
                                    </div>
                                    @if($outcome === $val)
                                        <div style="position: absolute; top: -8px; right: -8px; background: #0f172a; color: #fff; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; border: 3px solid #fff;">
                                            <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                                @if($outcome === 'follow_up')
                                <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 16px; animation: eliteFadeIn 0.3s ease;">
                                    <div>
                                        <label class="elite-label">Target Outreach Schedule</label>
                                        <input type="datetime-local" class="elite-input" wire:model="nextCallAt" style="height: 52px; font-weight: 800;">
                                    </div>
                                    <div>
                                        <label class="elite-label">Queue Assignment</label>
                                        <select wire:model="callStatus" class="elite-input" style="height: 52px; font-weight: 800;">
                                            <option value="">Status...</option>
                                            <option value="pending">⏳ Pending Sync</option>
                                            <option value="in_progress">🔄 Interaction Active</option>
                                        </select>
                                    </div>
                                </div>
                                @endif

                                @if($outcome === 'not_interested')
                                <div style="animation: eliteFadeIn 0.3s ease;">
                                    <label class="elite-label">Termination Analysis</label>
                                    <textarea class="elite-input" style="min-height: 80px; resize: none; padding: 16px;" wire:model="closeReason" placeholder="Synthesize the primary refusal drivers..."></textarea>
                                </div>
                                @endif

                                <div>
                                    <label class="elite-label">Executive Interaction Summary</label>
                                    <textarea class="elite-input" style="min-height: 120px; resize: none; padding: 20px;" wire:model="notes" placeholder="Transcribe key consultation highlights and client priorities..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="elite-footer">
                <button wire:click="close" style="background: transparent; border: none; color: #94a3b8; font-size: 14px; font-weight: 700; padding: 0 24px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.color='#0f172a'">
                    Discard Session
                </button>
                <button wire:click="saveOutcome" class="elite-save-btn" style="height: 52px; padding: 0 40px; font-size: 15px;">
                    Finalize Deployment
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
    
    <style> @keyframes eliteFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } } </style>
</div>
