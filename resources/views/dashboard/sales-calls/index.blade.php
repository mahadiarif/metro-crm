@extends('tyro-dashboard::layouts.app')
@use('App\Models\Lead')

@section('title', 'Daily Sales Call')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Sales Call</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Daily Sales Call</h1>
            <p class="page-description">Manage and optimize your tele-sales productivity</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('tyro-dashboard.sales-calls.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                Log New Call
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M17 8H7"/><path d="M17 12H7"/><path d="M17 16H7"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Calls</div>
            <div class="stat-value">{{ number_format($totalCalls) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Today's Calls</div>
            <div class="stat-value">{{ $todayCalls->count() }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Overdue</div>
            <div class="stat-value">{{ $overdueCalls->count() }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Converted</div>
            <div class="stat-value">{{ number_format($serviceInterestsCount) }}</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.sales-calls.index') }}" method="GET">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="filters-bar" style="display: flex; gap: 1rem; align-items: center; justify-content: space-between;">
                <div class="search-box" style="display: flex; gap: 0.5rem; align-items: center; flex: 1;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; color: var(--muted-foreground);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" style="width: 100%; max-width: 400px;" placeholder="Search by company or outcome..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary">Search</button>
                    @if(request('search'))
                        <a href="{{ route('tyro-dashboard.sales-calls.index', ['tab' => $tab]) }}" class="btn btn-ghost">Clear</a>
                    @endif
                </div>
                <div class="tabs" style="display: flex; gap: 0.5rem; background: var(--muted); padding: 0.25rem; border-radius: 8px;">
                    <a href="?tab=today&search={{ request('search') }}" class="btn btn-sm {{ $tab === 'today' ? 'btn-primary' : 'btn-ghost' }}">Daily Feed</a>
                    <a href="?tab=overdue&search={{ request('search') }}" class="btn btn-sm {{ $tab === 'overdue' ? 'btn-primary' : 'btn-ghost' }}">Overdue</a>
                    <a href="?tab=history&search={{ request('search') }}" class="btn btn-sm {{ $tab === 'history' ? 'btn-primary' : 'btn-ghost' }}">History</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Lead / Company</th>
                    <th>Outcome</th>
                    <th>Follow Up</th>
                    <th>Executive</th>
                    <th>When</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $rows = $tab === 'today' ? $todayCalls : ($tab === 'overdue' ? $overdueCalls : $callHistory); ?>
                <?php if ($rows->count()) { ?>
                <?php foreach ($rows as $row) { ?>
                <?php
                    $isLeadQueue = $row instanceof Lead;
                    $lead = $isLeadQueue ? $row : $row->lead;
                    $latestCall = $isLeadQueue ? $row->latestCall : $row;
                    $targetUrl = $isLeadQueue
                        ? route('tyro-dashboard.sales-calls.create', ['lead_id' => $row->id])
                        : route('tyro-dashboard.sales-calls.edit', $row->id);
                ?>
                <tr style="cursor: pointer;" onclick="window.location='{{ $targetUrl }}'">
                    <td>
                        <div style="font-weight: 600;">{{ $lead->company_name ?? 'N/A' }}</div>
                        <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $lead->client_name ?? '' }}</div>
                    </td>
                    <td>
                        <span class="badge {{ ($latestCall?->outcome ?? null) === 'service_request' ? 'badge-success' : 'badge-secondary' }}">
                            {{ $latestCall?->outcome ? Str::headline($latestCall->outcome) : 'Pending Call' }}
                        </span>
                    </td>
                    <td>
                        <?php $followUpAt = $isLeadQueue ? $lead->next_followup_at : $row->next_call_at; ?>
                        @if($followUpAt)
                            <div style="font-size: 0.8125rem;">{{ $followUpAt->format('M d, Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $followUpAt->format('h:i A') }}</div>
                        @else
                            <span style="color: var(--muted-foreground);">-</span>
                        @endif
                    </td>
                    <td>{{ $isLeadQueue ? ($lead->assignedUser->name ?? 'Unassigned') : ($row->user->name ?? 'System') }}</td>
                    <td>{{ $isLeadQueue ? ($latestCall?->created_at?->diffForHumans() ?? 'Not called yet') : $row->created_at->diffForHumans() }}</td>
                    <td style="text-align: right;" onclick="event.stopPropagation()">
                        <div class="table-actions" style="justify-content: flex-end;">
                            <a href="{{ $targetUrl }}" class="btn btn-icon btn-ghost" title="{{ $isLeadQueue ? 'Log Call' : 'View' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @unless($isLeadQueue)
                            <a href="{{ route('tyro-dashboard.sales-calls.edit', $row->id) }}" class="btn btn-icon btn-ghost" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('tyro-dashboard.sales-calls.destroy', $row->id) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('Delete this sales call?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-ghost text-danger" title="Delete">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3" />
                                    </svg>
                                </button>
                            </form>
                            @endunless
                        </div>
                    </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--muted-foreground);">
                        <div style="margin-bottom: 1rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 48px; opacity: 0.2; margin: 0 auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        No calls found for this section.
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    @if($callHistory->hasPages())
    <div class="card-footer">
        {{ $callHistory->links('tyro-dashboard::partials.pagination') }}
    </div>
    @endif
</div>
@endsection
" ,Description:
