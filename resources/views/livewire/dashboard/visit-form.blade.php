<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
            <h2 class="text-2xl font-bold text-white">Log Client Visit</h2>
            <p class="text-blue-100 mt-1">Lead: {{ $lead->company_name }}</p>
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
                        <label class="block text-sm font-medium text-gray-700">Contact Person</label>
                        <input type="text" wire:model="contact_person" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-10 px-3 border" placeholder="Name of contact">
                        @error('contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Designation</label>
                        <input type="text" wire:model="designation" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-10 px-3 border" placeholder="e.g. IT Manager">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" wire:model="phone" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-10 px-3 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-10 px-3 border">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
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
                        <h3 class="text-lg font-semibold text-gray-800">2. Existing Provider</h3>
                    </div>
                    <input type="text" wire:model="existing_provider" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-10 px-3 border" placeholder="e.g. Link3, AmberIT">
                </section>

                <section>
                    <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">3. Current Usage</h3>
                    </div>
                    <textarea wire:model="current_usage" rows="1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border" placeholder="Bandwidth, Price, etc."></textarea>
                </section>
            </div>

            <!-- Section 4: Service Interest -->
            <section>
                <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">4. Service Interest</h3>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-6 rounded-xl border border-gray-100 text-sm">
                    @foreach(['Internet', 'Data', 'IPTSP', 'SMS', 'Voice', 'LAN', 'Colocation', 'Cloud', 'Others'] as $service)
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" wire:model="service_interests" value="{{ $service }}" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition duration-150 ease-in-out">
                            <span class="text-gray-700 group-hover:text-blue-600 transition duration-150 ease-in-out">{{ $service }}</span>
                        </label>
                    @endforeach
                </div>
            </section>

            <!-- Section 5: Interest Status & Visit Stage -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Interest Status</h3>
                    </div>
                    <select wire:model="interest_summary_status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border bg-white">
                        <option value="follow_up">Follow Up Request</option>
                        <option value="proposal_request">Proposal Request</option>
                        <option value="closed">Not Interested / Closed</option>
                    </select>
                </div>
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Visit Stage</h3>
                    </div>
                    <select wire:model="visit_stage" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-11 px-3 border bg-white">
                        <option value="1st Visit">1st Visit</option>
                        <option value="2nd Visit">2nd Visit</option>
                        <option value="3rd Visit">3rd Visit</option>
                        <option value="4th Visit">4th Visit</option>
                    </select>
                </div>
            </section>

            <!-- Section 6: Visit Notes -->
            <section>
                <div class="flex items-center space-x-2 mb-6 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-rose-50 rounded-lg">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">6. Visit Notes</h3>
                </div>
                <textarea wire:model="meeting_notes" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-4 border" placeholder="Summary of discussion..."></textarea>
            </section>

            <!-- Footer / Submit -->
            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="inline-flex items-center px-8 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-lg">
                    <span>Save Visit Details</span>
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                </button>
            </div>
        </form>
    </div>
</div>
