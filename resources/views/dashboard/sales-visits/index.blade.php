@extends('tyro-dashboard::layouts.app')
@use('App\Models\Lead')

@section('title', 'Daily Sales Visit')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Sales Visit</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Daily Sales Visit</h1>
            <p class="page-description">Track and manage your physical client engagements</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('tyro-dashboard.sales-visits.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Log New Visit
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Visits</div>
            <div class="stat-value">{{ number_format($totalVisits) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Today's Visits</div>
            <div class="stat-value">{{ $todayVisits->count() }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Satisfied</div>
            <div class="stat-value">{{ number_format($satisfiedVisits) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Follow Ups</div>
            <div class="stat-value">{{ number_format($followUpsCount) }}</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.sales-visits.index') }}" method="GET">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="filters-bar" style="display: flex; gap: 1rem; align-items: center; justify-content: space-between;">
                <div class="search-box" style="display: flex; gap: 0.5rem; align-items: center; flex: 1;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; color: var(--muted-foreground);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" class="form-input" style="width: 100%; max-width: 400px;" placeholder="Search by company or feedback..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-secondary">Search</button>
                    @if(request('search'))
                        <a href="{{ route('tyro-dashboard.sales-visits.index', ['tab' => $tab]) }}" class="btn btn-ghost">Clear</a>
                    @endif
                </div>
                <div class="tabs" style="display: flex; gap: 0.5rem; background: var(--muted); padding: 0.25rem; border-radius: 8px;">
                    <a href="?tab=today&search={{ request('search') }}" class="btn btn-sm {{ $tab === 'today' ? 'btn-primary' : 'btn-ghost' }}">Daily Visits</a>
                    <a href="?tab=overdue&search={{ request('search') }}" class="btn btn-sm {{ $tab === 'overdue' ? 'btn-primary' : 'btn-ghost' }}">Pending</a>
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
                    <th>Status</th>
                    <th>Visit #</th>
                    <th>Feedback</th>
                    <th>Executive</th>
                    <th>When</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php($visits = $tab === 'today' ? $todayVisits : ($tab === 'overdue' ? $overdueVisits : $visitHistory))
                @forelse($visits as $visit)
                <tr style="cursor: pointer;" onclick="window.location='{{ route('tyro-dashboard.sales-visits.show', $visit->id) }}'">
                    <td>
                        <div style="font-weight: 600;">{{ $visit->dailySalesVisit->lead->company_name ?? 'N/A' }}</div>
                        <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $visit->dailySalesVisit->lead->client_name ?? '' }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $visit->status === 'service_request' ? 'badge-success' : 'badge-secondary' }}">
                            {{ Str::headline($visit->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-outline">
                            {{ $visit->visit_number }}{{ match($visit->visit_number) { 1=>'st', 2=>'nd', 3=>'rd', default=>'th' } }}
                        </span>
                    </td>
                    <td>
                        <div style="font-size: 0.8125rem; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $visit->notes ?: 'No notes provided' }}
                        </div>
                    </td>
                    <td>{{ $visit->marketingExe->name ?? 'System' }}</td>
                    <td>{{ $visit->visit_date->format('M d, Y') }}</td>
                    <td style="text-align: right;" onclick="event.stopPropagation()">
                        <div class="table-actions" style="justify-content: flex-end;">
                            <a href="{{ route('tyro-dashboard.sales-visits.show', $visit->id) }}" class="btn btn-icon btn-ghost" title="View">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('tyro-dashboard.sales-visits.edit', $visit->id) }}" class="btn btn-icon btn-ghost" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('tyro-dashboard.sales-visits.destroy', $visit->id) }}" method="POST" style="display: inline-flex;" onsubmit="return confirm('Delete this sales visit?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-ghost text-danger" title="Delete">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 3rem; color: var(--muted-foreground);">
                        <div style="margin-bottom: 1rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 48px; opacity: 0.2; margin: 0 auto;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        No visits found for this section.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($visitHistory->hasPages())
    <div class="card-footer">
        {{ $visitHistory->links('tyro-dashboard::partials.pagination') }}
    </div>
    @endif
</div>
@endsection
" ,Description:
