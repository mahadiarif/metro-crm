@extends('tyro-dashboard::layouts.admin')

@section('title', 'Lead Report')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.reports.leads') }}">Reports</a>
<span>/ Leads</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Lead Report</h1>
            <p class="page-description">Monitor your full sales pipeline and lead acquisition metrics.</p>
        </div>
        <div class="page-header-actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                CSV
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                PDF
            </a>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
@php
    $statusCounts = $leads->groupBy(fn($l) => $l->status)->map->count();
@endphp
<div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 120px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: var(--muted-foreground); font-weight: 500;">Total</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem;">{{ $leads->total() }}</div>
    </div>
    <div class="card" style="flex: 1; min-width: 120px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: #10b981; font-weight: 500;">Closed</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem; color: #10b981;">{{ $statusCounts['closed'] ?? 0 }}</div>
    </div>
    <div class="card" style="flex: 1; min-width: 120px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: #f59e0b; font-weight: 500;">Interested</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem; color: #f59e0b;">{{ $statusCounts['interested'] ?? 0 }}</div>
    </div>
    <div class="card" style="flex: 1; min-width: 120px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: #ef4444; font-weight: 500;">Lost</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem; color: #ef4444;">{{ $statusCounts['lost'] ?? 0 }}</div>
    </div>
</div>

{{-- Filters --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px; color: var(--muted-foreground);"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
            <h3 class="card-title">Filters</h3>
        </div>
    </div>
    <div class="card-body">
        {{-- Quick Date Presets --}}
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
            <span style="font-size: 0.8125rem; font-weight: 500; color: var(--muted-foreground); align-self: center; margin-right: 0.25rem;">Quick:</span>
            @foreach(['today' => 'Today', 'this_week' => 'This Week', 'this_month' => 'This Month'] as $key => $label)
                <a href="{{ request()->fullUrlWithQuery(['quick' => $key, 'start_date' => '', 'end_date' => '']) }}"
                   class="btn {{ request('quick') === $key ? 'btn-primary' : 'btn-ghost' }}"
                   style="font-size: 0.8125rem; padding: 0.3rem 0.75rem; height: auto;">
                   {{ $label }}
                </a>
            @endforeach
            @if(request()->anyFilled(['quick','start_date','end_date','user_id','status','service_id','zone']))
                <a href="{{ route('tyro-dashboard.reports.leads') }}" class="btn btn-ghost" style="font-size: 0.8125rem; padding: 0.3rem 0.75rem; height: auto; color: var(--muted-foreground);">✕ Clear all</a>
            @endif
        </div>

        <form action="{{ route('tyro-dashboard.reports.leads') }}" method="GET">
            <div class="form-row" style="flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 140px;">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-input" value="{{ request('start_date') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 140px;">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-input" value="{{ request('end_date') }}">
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 160px;">
                    <label class="form-label">Assigned Executive</label>
                    <select name="user_id" class="form-select">
                        <option value="">All Executives</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 160px;">
                    <label class="form-label">Product</label>
                    <select name="service_id" class="form-select">
                        <option value="">All Products</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 140px;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="new"        {{ request('status') == 'new'        ? 'selected' : '' }}>New</option>
                        <option value="contacted"  {{ request('status') == 'contacted'  ? 'selected' : '' }}>Contacted</option>
                        <option value="interested" {{ request('status') == 'interested' ? 'selected' : '' }}>Interested</option>
                        <option value="closed"     {{ request('status') == 'closed'     ? 'selected' : '' }}>Closed</option>
                        <option value="lost"       {{ request('status') == 'lost'       ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 140px;">
                    <label class="form-label">Zone</label>
                    <select name="zone" class="form-select">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="visibility: hidden;">Go</label>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
    </div>
</div>

{{-- Search Card (Tyro Style) --}}
<div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
        <div class="filters-bar" style="display: flex; gap: 10px; align-items: center; justify-content: space-between;">
            <div class="search-box" style="display: flex; gap: 10px; align-items: center;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; color: var(--muted-foreground);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" class="form-input" placeholder="Search..." value="{{ request('search') }}">
                @if(request('search'))
                    <a href="{{ request()->fullUrlWithQuery(['search' => '']) }}" class="btn btn-ghost" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Clear Search</a>
                @endif
            </div>
        </div>
    </div>
</div>
</form>



{{-- Data Table --}}
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Created</th>
                        <th>Company</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $i => $lead)
                        @php
                            $badgeClass = match($lead->status) {
                                'closed' => 'badge-success',
                                'interested' => 'badge-warning',
                                'lost' => 'badge-danger',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <tr>
                            <td style="color: var(--muted-foreground); font-size: 0.8125rem;">{{ ($leads->currentPage() - 1) * $leads->perPage() + $i + 1 }}</td>
                            <td style="white-space: nowrap;">{{ $lead->created_at->format('d M Y') }}</td>
                            <td style="font-weight: 600;">{{ $lead->company_name }}</td>
                            <td>{{ $lead->client_name }}</td>
                            <td style="font-size: 0.875rem;">{{ $lead->phone }}</td>
                            <td><span class="badge badge-secondary">{{ $lead->service->name ?? 'N/A' }}</span></td>
                            <td><span class="badge {{ $badgeClass }}">{{ ucfirst($lead->status) }}</span></td>
                            <td>{{ $lead->assignedUser->name ?? 'Unassigned' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 4rem; color: var(--muted-foreground);">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 40px; height: 40px; margin: 0 auto 1rem; display: block; opacity: 0.4;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                No leads found for the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border);">
            {{ $leads->links('tyro-dashboard::partials.pagination') }}
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let timeout = null;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.closest('form').submit();
                }, 500);
            });

            if (searchInput.value) {
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }
        }
    });
</script>
@endpush
@endsection
