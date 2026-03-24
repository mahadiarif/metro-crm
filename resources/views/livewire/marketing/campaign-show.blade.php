<div>
    <div class="page-header">
        <div class="page-header-row">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}" class="btn btn-ghost" style="padding: 0.5rem; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; background: var(--muted); opacity: 0.1;">
                    <svg style="width: 20px; height: 20px; color: var(--foreground);" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="page-title">{{ $campaign->name }}</h1>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-top: 0.25rem; font-size: 0.75rem; color: var(--muted-foreground); font-weight: 500;">
                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $campaign->creator->name }}
                        </span>
                        <span style="display: flex; align-items: center; gap: 0.25rem;">
                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $campaign->created_at->format('M d, Y') }}
                        </span>
                        <span style="padding: 0.125rem 0.625rem; border-radius: 0.375rem; background: var(--muted); color: var(--foreground); font-size: 0.625rem; font-weight: 700; text-transform: uppercase;">
                            {{ $campaign->type }}
                        </span>
                        <span style="padding: 0.125rem 0.625rem; border-radius: 0.375rem; background: {{ $campaign->status === 'sent' ? 'rgba(16, 185, 129, 0.1)' : 'var(--muted)' }}; color: {{ $campaign->status === 'sent' ? '#10b981' : 'var(--muted-foreground)' }}; font-size: 0.625rem; font-weight: 700; text-transform: uppercase;">
                            {{ $campaign->status }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="page-header-actions">
                <button onclick="window.print()" class="btn btn-secondary flex items-center gap-2">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="row g-4" style="margin-bottom: 2rem;">
        <div class="col-md-3">
            <div class="card" style="height: 100%; border-left: 4px solid var(--primary); padding: 1.25rem;">
                <div style="font-size: 0.75rem; color: var(--muted-foreground); font-weight: 700; text-transform: uppercase;">Total Recipients</div>
                <div style="font-size: 2rem; font-weight: 800; margin: 0.5rem 0;">{{ number_format($stats['total']) }}</div>
                <div style="font-size: 0.75rem; color: var(--muted-foreground);">Total leads in audience</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="height: 100%; border-left: 4px solid #10b981; padding: 1.25rem;">
                <div style="font-size: 0.75rem; color: var(--muted-foreground); font-weight: 700; text-transform: uppercase;">Delivered</div>
                <div style="font-size: 2rem; font-weight: 800; margin: 0.5rem 0; color: #10b981;">{{ number_format($stats['sent']) }}</div>
                <div style="font-size: 0.75rem; color: #10b981; font-weight: 600;">Success</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="height: 100%; border-left: 4px solid #f59e0b; padding: 1.25rem;">
                <div style="font-size: 0.75rem; color: var(--muted-foreground); font-weight: 700; text-transform: uppercase;">Pending</div>
                <div style="font-size: 2rem; font-weight: 800; margin: 0.5rem 0; color: #f59e0b;">{{ number_format($stats['pending']) }}</div>
                <div style="font-size: 0.75rem; color: #f59e0b; font-weight: 600;">In Queue</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="height: 100%; border-left: 4px solid #ef4444; padding: 1.25rem;">
                <div style="font-size: 0.75rem; color: var(--muted-foreground); font-weight: 700; text-transform: uppercase;">Failed</div>
                <div style="font-size: 2rem; font-weight: 800; margin: 0.5rem 0; color: #ef4444;">{{ number_format($stats['failed']) }}</div>
                <div style="font-size: 0.75rem; color: #ef4444; font-weight: 600;">Delivery Issues</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Message Preview -->
        <div class="col-lg-4">
            <div class="card" style="margin-bottom: 2rem;">
                <div class="card-header border-b" style="padding: 1.25rem 1.5rem;">
                    <h2 class="card-title" style="font-size: 0.875rem; font-weight: 700;">Broadcast Message</h2>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div style="background: var(--muted); opacity: 0.1; border-radius: 1rem; padding: 1.5rem; position: relative;">
                         <svg style="position: absolute; top: 1rem; right: 1rem; width: 1.5rem; height: 1.5rem; color: var(--muted-foreground); opacity: 0.2;" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        <div style="font-size: 0.875rem; line-height: 1.6; color: var(--foreground); white-space: pre-wrap; position: relative; z-index: 1;">
                            {!! str_replace('{name}', '<span style="color: var(--primary); font-weight: 700;">John Doe</span>', e($campaign->message)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audience & Delivery -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-b" style="padding: 1.25rem 1.5rem;">
                    <h2 class="card-title" style="font-size: 0.875rem; font-weight: 700;">Delivery Log</h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: right;">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($recipients->count() > 0)
                                    @foreach($recipients as $recipient)
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                    <div style="width: 1.5rem; height: 1.5rem; border-radius: 9999px; background: rgba(var(--primary-rgb, 59, 130, 246), 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.625rem;">{{ substr($recipient->lead?->client_name ?? 'N', 0, 1) }}</div>
                                                    <div>
                                                        <div style="font-weight: 600; font-size: 0.8125rem;">{{ $recipient->lead?->client_name ?? 'N/A' }}</div>
                                                        <div style="font-size: 0.6875rem; color: var(--muted-foreground);">{{ $campaign->type === 'email' ? $recipient->email : $recipient->phone }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align: center;">
                                                @php
                                                    $statusColor = $recipient->status === 'sent' ? '#10b981' : ($recipient->status === 'failed' ? '#ef4444' : '#f59e0b');
                                                    $statusBg = $recipient->status === 'sent' ? 'rgba(16, 185, 129, 0.1)' : ($recipient->status === 'failed' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(245, 158, 11, 0.1)');
                                                @endphp
                                                <span style="padding: 0.125rem 0.625rem; border-radius: 9999px; background: {{ $statusBg }}; color: {{ $statusColor }}; font-size: 0.625rem; font-weight: 700; text-transform: uppercase;">
                                                    {{ $recipient->status }}
                                                </span>
                                            </td>
                                            <td style="text-align: right; font-size: 0.75rem; color: var(--muted-foreground);">
                                                {{ $recipient->sent_at ? $recipient->sent_at->diffForHumans() : 'Queued' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 4rem; color: var(--muted-foreground);">No logs found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
