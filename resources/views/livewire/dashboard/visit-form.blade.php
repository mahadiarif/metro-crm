<div class="max-w-5xl mx-auto py-8">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Log Client Visit</h2>
            <p class="text-blue-100 mt-1">Direct Outreach & Service Outcome Matrix</p>
        </div>

        <!-- Standalone Mode: Lead Selection -->
        @if(!$lead)
        <div class="px-8 pt-8 pb-4 bg-blue-50/50 border-b border-blue-100">
            <label class="block text-sm font-bold text-blue-700 uppercase tracking-wider mb-2">1. Select Company / Lead to Log Visit</label>
            <div class="relative">
                <select wire:model.live="selectedLeadId" class="block w-full rounded-xl border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-lg h-14 px-4 border bg-white font-bold text-gray-800 transition-all @error('selectedLeadId') border-red-500 @enderror">
                    <option value="">-- Click to select a company --</option>
                    @foreach($allLeads as $l)
                        <option value="{{ $l->id }}">{{ $l->company_name }} ({{ $l->client_name }})</option>
                    @endforeach
                </select>
                @error('selectedLeadId') <span class="text-red-500 text-xs font-bold mt-1 block">Please select a company to continue.</span> @enderror
                <div class="mt-2 flex items-center text-xs text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Selecting a company will automatically load their current service usage and contact history.</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Section 0: Lead Overview (Read Only) - Only show if lead is selected -->
        @if($lead)
        <div class="px-8 pt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                <span class="text-[10px] font-bold uppercase text-muted-foreground block mb-1">Company Address</span>
                <p class="text-sm font-medium text-gray-800">{{ $lead->address ?: 'No address provided' }}</p>
            </div>
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                <span class="text-[10px] font-bold uppercase text-muted-foreground block mb-1">Lead Source</span>
                <p class="text-sm font-medium text-gray-800">{{ $lead->source ?: 'Direct / General' }}</p>
            </div>
            <div class="p-4 rounded-xl {{ $previous_visit ? 'bg-amber-50 border-amber-100' : 'bg-gray-50 border-gray-100' }}">
                <span class="text-[10px] font-bold uppercase {{ $previous_visit ? 'text-amber-600' : 'text-muted-foreground' }} block mb-1">Current Stage</span>
                <p class="text-sm font-bold text-amber-700">{{ $visit_stage }}</p>
                @if($previous_visit)
                    <p class="text-[10px] text-amber-600">Last: {{ $previous_visit->visit_date->diffForHumans() }}</p>
                @endif
            </div>
        </div>

        <form wire:submit.prevent="save" class="p-8 space-y-10">
            
            <!-- Section 1: Client Information -->
            <section>
                <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">1. Client Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Contact Person</label>
                        <input type="text" wire:model="contact_person" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border" placeholder="Name of contact">
                        @error('contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Designation</label>
                        <input type="text" wire:model="designation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border" placeholder="e.g. IT Manager">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Contact Num / Cell No</label>
                        <input type="text" wire:model="phone" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Email ID</label>
                        <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Lead Source</label>
                        <input type="text" wire:model="source" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border" placeholder="e.g. Website, Referral">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Address</label>
                        <textarea wire:model="address" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border"></textarea>
                    </div>
                </div>
            </section>

            <!-- Section 2 & 3: Provider & Usage -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <section>
                    <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">2. Competitor / Provider</h3>
                    </div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Existing Service Providers</label>
                    <input type="text" wire:model="existing_provider" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border" placeholder="e.g. Link3, AmberIT">
                </section>

                <section>
                    <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">3. Service Usages</h3>
                    </div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Current Service Usages</label>
                    <textarea wire:model="current_usage" rows="1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border" placeholder="Bandwidth, Price, etc."></textarea>
                </section>
            </div>

            <!-- Section 4: Service Outcome Matrix -->
            <section>
                <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">4. Service Outcome Matrix</h3>
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Service Type</th>
                                <th class="px-2 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">1st Visit</th>
                                <th class="px-2 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">2nd Visit</th>
                                <th class="px-2 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">3rd Visit</th>
                                <th class="px-2 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">4th Visit</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-indigo-600 uppercase tracking-wider bg-indigo-50">Current Outcome ({{ $visit_stage }})</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($available_services as $service)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-700">
                                    {{ $service }}
                                </td>
                                @foreach(['1st Visit', '2nd Visit', '3rd Visit', '4th Visit'] as $stage)
                                <td class="px-2 py-3 whitespace-nowrap text-center text-xs">
                                    @if(isset($service_history[$service][$stage]))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $service_history[$service][$stage] === 'Yes' ? 'bg-green-100 text-green-700' : ($service_history[$service][$stage] === 'No' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $service_history[$service][$stage] }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">N/A</span>
                                    @endif
                                </td>
                                @endforeach
                                <td class="px-4 py-2 whitespace-nowrap bg-indigo-50/30">
                                    <select wire:model="service_outcomes.{{ $service }}" class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-xs h-9 px-2 border bg-white">
                                        <option value="">N/A</option>
                                        <option value="follow_up">Follow Up Request</option>
                                        <option value="service_request">Service Request / Yes</option>
                                        <option value="not_interested">Not Interested / No</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Section 5: Interest Status & Visit Stage -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Overall Lead Status</h3>
                    </div>
                    <select wire:model="interest_summary_status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border bg-white @error('interest_summary_status') border-red-500 @enderror">
                        <option value="follow_up">Follow Up Request (Continuous)</option>
                        <option value="proposal_request">Proposal Request / Pipeline</option>
                        <option value="closed">Not Interested / Closed</option>
                    </select>
                    @error('interest_summary_status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Current Visit Stage</h3>
                    </div>
                    <select wire:model="visit_stage" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border bg-white @error('visit_stage') border-red-500 @enderror">
                        <option value="1st Visit">1st Visit</option>
                        <option value="2nd Visit">2nd Visit</option>
                        <option value="3rd Visit">3rd Visit</option>
                        <option value="4th Visit">4th Visit</option>
                    </select>
                    @error('visit_stage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                @if($interest_summary_status === 'follow_up')
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Next Follow-up Date</h3>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                        <input type="date" wire:model="next_followup_date" class="block w-full rounded-lg border-indigo-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-11 pl-10 border bg-white @error('next_followup_date') border-red-500 @enderror">
                    </div>
                    @error('next_followup_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    <p class="text-[10px] text-gray-500 mt-2 italic">* Automatically schedules lead back into Daily Sales Call queue.</p>
                </div>
                @endif
            </section>

            <!-- Section 6: Visit Notes -->
            <section>
                <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-rose-50 rounded-lg">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">6. Notes / Remarks</h3>
                </div>
                <textarea wire:model="meeting_notes" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-4 border" placeholder="Direct feedback from client regarding these services..."></textarea>
            </section>

            <!-- Footer / Submit -->
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="inline-flex items-center px-10 py-4 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out shadow-xl">
                    <span>Save Daily Visit Log</span>
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                </button>
            </div>
        </form>
        @else
        <div class="p-20 text-center">
            <div class="mb-4 text-6xl">🔍</div>
            <h3 class="text-xl font-bold text-gray-800">No Lead Selected</h3>
            <p class="text-gray-500 mt-2">Please select a company from the dropdown above to start logging service outcomes.</p>
        </div>
        @endif
    </div>
</div>
