<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Marketing Campaigns</h1>
                <p class="page-description">Manage your SMS and Email marketing campaigns.</p>
            </div>
            <div class="page-header-actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button wire:click="export('csv')" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    CSV
                </button>
                <button wire:click="export('excel')" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Excel
                </button>
                <button wire:click="export('pdf')" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    PDF
                </button>
                <a href="{{ route('tyro-dashboard.marketing.campaigns.create') }}" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Campaign
                </a>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div class="filters-bar" style="display: flex; gap: 10px; align-items: center;">
                <div class="search-box" style="display: flex; gap: 10px; align-items: center; flex: 1; max-width: 400px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; color: var(--muted-foreground);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-input" placeholder="Search campaign name...">
                </div>
                @if($search)
                    <button wire:click="$set('search', '')" class="btn btn-ghost" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Clear Search</button>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
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
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $campaign->name }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $campaign->type === 'email' ? 'badge-primary' : 'badge-secondary' }}" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                        @if($campaign->type === 'email')
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        @else
                                            <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        @endif
                                        {{ strtoupper($campaign->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($campaign->status === 'sent') badge-success
                                        @elseif($campaign->status === 'scheduled') badge-info
                                        @else badge-secondary @endif">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem; color: var(--muted-foreground);">{{ $campaign->recipients_count }} Leads</div>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">{{ $campaign->created_at->format('M d, Y') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $campaign->created_at->format('h:i A') }}</div>
                                </td>
                                <td style="text-align: right;">
                                    <a href="{{ route('tyro-dashboard.marketing.campaigns.show', $campaign) }}" class="btn btn-ghost btn-sm" style="color: var(--primary);">
                                        View Details
                                        <svg style="width: 14px; height: 14px; margin-left: 0.25rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 4rem; text-align: center; color: var(--muted-foreground);">
                                    <div style="margin-bottom: 1rem;">
                                        <svg style="width: 48px; height: 48px; opacity: 0.4; display: inline-block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    </div>
                                    <div style="font-weight: 600;">No campaigns found</div>
                                    <p style="margin-top: 0.25rem;">Start by creating your first marketing campaign.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($campaigns->hasPages())
                <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border);">
                    {{ $campaigns->links('tyro-dashboard::partials.pagination') }}
                </div>
            @endif
        </div>
    </div>
</div>
