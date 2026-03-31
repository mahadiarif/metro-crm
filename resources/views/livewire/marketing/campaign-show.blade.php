<div>
    <div class="page-header">
        <div class="page-header-row">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}" class="btn btn-ghost" title="Back to Index">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="page-title">Campaign Details</h1>
            </div>
            <div>
                <button wire:click="duplicateCampaign({{ $campaign->id }})" class="btn btn-primary">Clone Strategy</button>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-body">
            <div class="details-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                <div class="detail-item">
                    <div class="detail-label" style="font-weight: 500; color: var(--text-secondary); margin-bottom: 0.25rem;">Campaign Identity</div>
                    <div class="detail-value" style="font-size: 1rem; color: var(--text-primary);">{{ $campaign->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label" style="font-weight: 500; color: var(--text-secondary); margin-bottom: 0.25rem;">Distribution Channel</div>
                    <div class="detail-value" style="font-size: 1rem; color: var(--text-primary); text-transform: uppercase;">{{ $campaign->type }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label" style="font-weight: 500; color: var(--text-secondary); margin-bottom: 0.25rem;">Initialization Time</div>
                    <div class="detail-value" style="font-size: 1rem; color: var(--text-primary);">{{ ($campaign->scheduled_at ?? $campaign->created_at)->format('d M Y, h:i A') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label" style="font-weight: 500; color: var(--text-secondary); margin-bottom: 0.25rem;">Strategy Owner</div>
                    <div class="detail-value" style="font-size: 1rem; color: var(--text-primary);">{{ $campaign->creator?->name ?? 'System' }}</div>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="detail-label" style="font-weight: 500; color: var(--text-secondary); margin-bottom: 0.25rem;">Message Content</div>
                    <div class="detail-value" style="font-size: 1rem; color: var(--text-primary); white-space: pre-wrap; padding: 1rem; background: var(--bg-secondary); border-radius: 8px; border: 1px solid var(--border);">{{ $campaign->message }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transmission Ledger</h3>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Recipient Details</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Time of Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipients as $recipient)
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $recipient->lead?->client_name ?? 'Lead #'.$recipient->lead_id }}</div>
                                <div style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $recipient->recipient_target }}</div>
                            </td>
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'sent' => 'badge-success',
                                        'failed' => 'badge-danger',
                                        'pending' => 'badge-warning'
                                    ][$recipient->status] ?? 'badge-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}" style="text-transform: uppercase; font-size: 0.75rem;">
                                    {{ $recipient->status === 'sent' ? 'Deployed' : ($recipient->status === 'failed' ? 'Halted' : 'Queued') }}
                                </span>
                            </td>
                            <td class="text-end" style="color: var(--text-secondary); font-size: 0.875rem;">
                                @if($recipient->sent_at)
                                    <div>{{ $recipient->sent_at->format('d M Y, H:i') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $recipient->sent_at->diffForHumans() }}</div>
                                @else
                                    <span style="font-style: italic; opacity: 0.6;">Transmission Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted-foreground italic">No transmission data recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recipients->hasPages())
            <div class="pagination" style="padding: 1rem; border-top: 1px solid var(--border);">
                {{ $recipients->links() }}
            </div>
        @endif
    </div>
</div>
