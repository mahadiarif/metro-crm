<div class="card shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recipient Status Tracking</h5>
        @if($campaign->recipients()->where('status', 'failed')->exists())
            <form action="{{ route('marketing.campaigns.retry', $campaign) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning">
                    <i class="bi bi-arrow-clockwise"></i> Retry Failed
                </button>
            </form>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Recipient</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th class="text-end pe-3">Action/Error</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipients as $recipient)
                        <tr>
                            <td class="ps-3">
                                <strong>{{ $recipient->lead->client_name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $recipient->lead->company_name ?? '' }}</small>
                            </td>
                            <td>{{ $recipient->email }}</td>
                            <td>
                                @if($recipient->status === 'sent')
                                    <span class="badge bg-success-soft text-success">
                                        <i class="bi bi-check-circle"></i> Sent
                                    </span>
                                @elseif($recipient->status === 'failed')
                                    <span class="badge bg-danger-soft text-danger">
                                        <i class="bi bi-exclamation-triangle"></i> Failed
                                    </span>
                                @else
                                    <span class="badge bg-secondary-soft text-secondary">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>{{ $recipient->sent_at ? $recipient->sent_at->format('d M, h:i A') : '-' }}</td>
                            <td class="text-end pe-3">
                                @if($recipient->status === 'failed')
                                    <button class="btn btn-sm btn-link text-danger p-0" 
                                            title="{{ $recipient->error_message }}"
                                            onclick="alert('Error: {{ addslashes($recipient->error_message) }}')">
                                        View Error
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No recipients added to this campaign yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($recipients->hasPages())
        <div class="card-footer bg-white">
            {{ $recipients->links() }}
        </div>
    @endif
</div>

<style>
    .bg-success-soft { background-color: #d1fae5; }
    .bg-danger-soft { background-color: #fee2e2; }
    .bg-secondary-soft { background-color: #f3f4f6; }
</style>
