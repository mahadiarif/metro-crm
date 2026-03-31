<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Marketing Campaigns</h1>
            </div>
            <a href="{{ route('tyro-dashboard.marketing.campaigns.create') }}" class="btn btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Initiate Campaign
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card" style="margin-bottom: 1rem;">
        <div class="card-body">
            <div class="filters-bar" style="display: flex; gap: 10px; align-items: center; justify-content: space-between;">
                <div class="search-box" style="display: flex; gap: 10px; align-items: center; flex-grow: 1; max-width: 400px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live="search" class="form-input" placeholder="Search campaigns...">
                    @if($search)
                        <button wire:click="$set('search', '')" class="btn btn-ghost">Clear</button>
                    @endif
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <select wire:model.live="type" class="form-select" style="min-width: 140px;">
                        <option value="">All Channels</option>
                        <option value="email">Email</option>
                        <option value="sms">SMS</option>
                    </select>

                    <button type="button" class="btn btn-secondary" style="display: flex; align-items: center; gap: 5px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        Filter Columns
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Campaign Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Recipients</th>
                        <th>Created At</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('tyro-dashboard.marketing.campaigns.show', $campaign) }}'">
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $campaign->name }}</div>
                                <div style="font-size: 0.8125rem; color: var(--text-secondary);">{{ Str::limit($campaign->message, 50) }}</div>
                            </td>
                            <td>
                                <span class="badge badge-secondary" style="text-transform: uppercase;">{{ $campaign->type }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'completed' => 'badge-success',
                                        'processing' => 'badge-primary',
                                        'failed' => 'badge-danger',
                                        'pending' => 'badge-warning',
                                        'draft' => 'badge-secondary'
                                    ][$campaign->status] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($campaign->recipients_count) }}</td>
                            <td style="color: var(--text-secondary); font-size: 0.875rem;">
                                {{ ($campaign->scheduled_at ?? $campaign->created_at)->format('d M Y, h:i A') }}
                            </td>
                            <td style="text-align: right;" onclick="event.stopPropagation()">
                                <div class="table-actions" style="justify-content: flex-end;">
                                    <a href="{{ route('tyro-dashboard.marketing.campaigns.show', $campaign) }}" class="btn btn-icon btn-ghost" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <button wire:click="duplicateCampaign({{ $campaign->id }})" class="btn btn-icon btn-ghost" title="Clone">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form onsubmit="return confirm('Are you sure?')" wire:submit.prevent="deleteCampaign({{ $campaign->id }})" style="display: inline;">
                                        <button type="submit" class="btn btn-icon btn-ghost text-danger" title="Delete">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted-foreground italic">No campaigns found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($campaigns->hasPages())
            <div class="pagination" style="padding: 1rem; border-top: 1px solid var(--border);">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
</div>
