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

        .visit-alert {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 8px;
            padding: 0.875rem 1rem;
            margin-bottom: 0.5rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
            font-size: 0.875rem;
            font-weight: 700;
        }

        .visit-alert-success {
            border-color: color-mix(in srgb, var(--success) 45%, var(--border));
            background: color-mix(in srgb, var(--success) 14%, var(--card));
            color: var(--success-foreground);
        }

        .visit-alert-error {
            border-color: color-mix(in srgb, var(--destructive) 35%, var(--border));
            background: color-mix(in srgb, var(--destructive) 8%, var(--card));
            color: var(--destructive);
        }

        .visit-alert-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }

        .visit-alert-success .visit-alert-icon {
            background: var(--success);
            color: var(--success-foreground);
        }

        .visit-alert-error .visit-alert-icon {
            background: var(--destructive);
            color: var(--destructive-foreground);
        }

        .visit-alert-title {
            margin: 0;
            color: var(--foreground);
            font-size: 0.875rem;
            font-weight: 800;
        }

        .visit-alert-text {
            margin: 0.125rem 0 0;
            color: var(--muted-foreground);
            font-size: 0.75rem;
            font-weight: 600;
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
        @if (session()->has('success'))
            <div class="visit-alert visit-alert-success">
                <span class="visit-alert-icon">
                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5" />
                    </svg>
                </span>
                <div>
                    <p class="visit-alert-title">{{ session('success') }}</p>
                    <p class="visit-alert-text">The visit details and follow-up information have been saved.</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="visit-alert visit-alert-error">
                <span class="visit-alert-icon">
                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0z" />
                    </svg>
                </span>
                <div>
                    <p class="visit-alert-title">Save failed</p>
                    <p class="visit-alert-text">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="visit-alert visit-alert-error">
                <span class="visit-alert-icon">
                    <svg class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0z" />
                    </svg>
                </span>
                <div>
                    <p class="visit-alert-title">Please check the form</p>
                    <p class="visit-alert-text">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

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
                                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input @error('leadId') border-destructive @enderror @error('companyName') border-destructive @enderror" placeholder="Type company name or search existing lead">
                                    @if(!empty($leads))
                                        <div class="visit-search-results">
                                            @foreach($leads as $l)
                                                <div wire:click="selectLead({{ $l['id'] }})" class="visit-lead-result">
                                                    {{ $l['company_name'] }}{{ !empty($l['client_name']) ? ' - ' . $l['client_name'] : '' }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                @if($leadId)
                                    <p class="text-[10px] text-success font-bold mt-1">Existing lead selected.</p>
                                @elseif(!empty($leads))
                                    <p class="text-[10px] text-muted-foreground font-bold mt-1">Select a matching lead from the list, or keep typing for a new lead.</p>
                                @elseif($search)
                                    <p class="text-[10px] text-muted-foreground font-bold mt-1">New lead will be created when you save.</p>
                                @endif
                                @error('leadId') <p class="text-[10px] text-destructive font-bold mt-1">{{ $message }}</p> @enderror
                                @error('companyName') <p class="text-[10px] text-destructive font-bold mt-1">{{ $message }}</p> @enderror
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
                        <div>
                            <label class="visit-label">Latitude</label>
                            <input type="text" id="visit-latitude" wire:model.live="latitude" class="form-input" placeholder="e.g. 23.8103">
                        </div>
                        <div>
                            <label class="visit-label">Longitude</label>
                            <input type="text" id="visit-longitude" wire:model.live="longitude" class="form-input" placeholder="e.g. 90.4125">
                        </div>

                        <div class="visit-field-full" style="margin-top: 1rem;">
                            <label class="visit-label">Select on Map</label>
                            <div id="visit-map" style="height: 200px; border-radius: 8px; border: 1px solid var(--border); z-index: 1;"></div>
                            <p class="text-[10px] text-muted-foreground mt-1">Click on the map to set coordinates manually.</p>
                        </div>
                    </div>
                </div>
            </div>

            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                document.addEventListener('livewire:initialized', () => {
                    let map, marker;
                    const defaultLat = 23.8103;
                    const defaultLng = 90.4125;

                    function initMap() {
                        const latInput = document.getElementById('visit-latitude');
                        const lngInput = document.getElementById('visit-longitude');
                        
                        const initialLat = parseFloat(latInput.value) || defaultLat;
                        const initialLng = parseFloat(lngInput.value) || defaultLng;

                        if (map) return; // Already initialized

                        map = L.map('visit-map').setView([initialLat, initialLng], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(map);

                        marker = L.marker([initialLat, initialLng], {draggable: true}).addTo(map);

                        map.on('click', function(e) {
                            const lat = e.latlng.lat.toFixed(6);
                            const lng = e.latlng.lng.toFixed(6);
                            updatePosition(lat, lng);
                        });

                        marker.on('dragend', function(e) {
                            const lat = marker.getLatLng().lat.toFixed(6);
                            const lng = marker.getLatLng().lng.toFixed(6);
                            updatePosition(lat, lng);
                        });

                        function updatePosition(lat, lng) {
                            marker.setLatLng([lat, lng]);
                            @this.set('latitude', lat);
                            @this.set('longitude', lng);
                        }

                        // Listen for Livewire updates to sync map
                        Livewire.on('setCoordinates', (data) => {
                            const lat = data[0].lat || data.lat;
                            const lng = data[0].lng || data.lng;
                            if (map && marker) {
                                marker.setLatLng([lat, lng]);
                                map.panTo([lat, lng]);
                            }
                        });
                    }

                    // Re-init map when modal opens or component updates
                    initMap();
                    
                    // Handle case where map container might be hidden initially
                    const observer = new MutationObserver((mutations) => {
                        if (document.getElementById('visit-map')) {
                            initMap();
                            map.invalidateSize();
                        }
                    });
                    observer.observe(document.body, { childList: true, subtree: true });
                });
            </script>

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
                                        <option value="">Select Service</option>
                                        @foreach($services as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="visit-label">Current Vendor</label>
                                    <input type="text" wire:model="dynamicServices.{{ $index }}.competitor" class="form-input" placeholder="Vendor name">
                                </div>
                                <div>
                                    @php
                                        $sId = $dynamicServices[$index]['service_type'] ?? null;
                                        $sName = $sId ? ($services->firstWhere('id', $sId)->name ?? '') : '';
                                        $label = 'Usage Details';
                                        if (str_contains(strtolower($sName), 'internet')) $label = 'Bandwidth (Mbps)';
                                        elseif (str_contains(strtolower($sName), '365')) $label = 'No. of Licenses';
                                        elseif (str_contains(strtolower($sName), 'phone')) $label = 'No. of Channels';
                                        elseif (str_contains(strtolower($sName), 'data')) $label = 'Link Capacity';
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </span>
                        Service Requirements (Interested In)
                    </h3>
                    <button type="button" wire:click="addInterestedServiceRow" class="btn btn-sm btn-secondary">
                        Add Requirement
                    </button>
                </div>
                <div class="visit-card-body">
                    @foreach($interestedServices as $index => $svc)
                        <div class="visit-service-card">
                            <div class="visit-service-grid">
                                <div>
                                    <label class="visit-label">Our Service</label>
                                    <select wire:model.live="interestedServices.{{ $index }}.service_id" class="form-input">
                                        <option value="">Select Service</option>
                                        @foreach($services as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="visit-label">Interest Level</label>
                                    <select wire:model="interestedServices.{{ $index }}.status" class="form-input">
                                        <option value="interested">Interested</option>
                                        <option value="demo">Demo / Trial</option>
                                        <option value="qualified">Qualified Lead</option>
                                        <option value="closed">Closed / Won</option>
                                    </select>
                                </div>
                                <div>
                                    @php
                                        $sId = $interestedServices[$index]['service_id'] ?? null;
                                        $sName = $sId ? ($services->firstWhere('id', $sId)->name ?? '') : '';
                                        $label = 'Requirements';
                                        if (str_contains(strtolower($sName), 'internet')) $label = 'Bandwidth (Mbps)';
                                        elseif (str_contains(strtolower($sName), '365')) $label = 'No. of Licenses';
                                        elseif (str_contains(strtolower($sName), 'phone')) $label = 'No. of Channels';
                                    @endphp
                                    <label class="visit-label">{{ $label }}</label>
                                    <input type="text" wire:model="interestedServices.{{ $index }}.details" class="form-input" placeholder="Specific requirements">
                                </div>
                                <button type="button" wire:click="removeInterestedServiceRow({{ $index }})" class="btn btn-icon btn-ghost text-danger" title="Remove">
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-3l-4 4z" />
                            </svg>
                        </span>
                        Visit Count
                    </h3>
                </div>
                <div class="visit-card-body">
                    <div class="visit-segmented">
                        @foreach([1, 2, 3, 4] as $num)
                            <div wire:click="$set('visitNumber', {{ $num }})" 
                                 class="visit-segment {{ $visitNumber == $num ? 'active-cold' : '' }}">
                                {{ $num }}{{ match($num) { 1=>'st', 2=>'nd', 3=>'rd', default=>'th' } }} Visit
                            </div>
                        @endforeach
                    </div>
                    @error('visitNumber') <p class="text-[10px] text-destructive font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="visit-card">
                <div class="visit-card-header">
                    <h3 class="visit-card-title">
                        <span class="visit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </span>
                        Visit Status & Follow Up
                    </h3>
                </div>
                <div class="visit-card-body">
                    <div class="visit-field-grid">
                        <div class="visit-field-full">
                            <label class="visit-label">Current Status</label>
                            <select wire:model.live="status" class="form-input">
                                <option value="follow_up">Follow Up</option>
                                <option value="service_request">Service Request</option>
                                <option value="interested">Interested</option>
                                <option value="not_interested">Not Interested</option>
                                <option value="qualified">Qualified</option>
                            </select>
                        </div>
                        
                        @if(in_array($status, ['follow_up', 'service_request', 'interested', 'qualified']))
                            <div class="visit-field-full">
                                <label class="visit-label">Follow Up Status</label>
                                <select wire:model="visitStatus" class="form-input">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <div>
                                <label class="visit-label">Next Action Mode</label>
                                <select wire:model="followupType" class="form-input">
                                    <option value="call">Call</option>
                                    <option value="visit">Visit</option>
                                    <option value="email">Email</option>
                                    <option value="proposal">Proposal Delivery</option>
                                </select>
                            </div>
                            <div>
                                <label class="visit-label">Target Date</label>
                                <input type="date" wire:model="nextFollowupDate" class="form-input">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="visit-actions">
                <button type="button" wire:click="saveVisit" wire:loading.attr="disabled" class="btn btn-primary" style="width: 100%; justify-content: center; min-height: 44px;">
                    <span wire:loading.remove>Save Daily Visit Log</span>
                    <span wire:loading>Saving...</span>
                    <svg wire:loading.remove class="simple-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
