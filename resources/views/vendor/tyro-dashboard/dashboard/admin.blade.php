@extends('tyro-dashboard::layouts.admin')

@section('title', 'Admin Dashboard')

@section('breadcrumb')
<span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Sales CRM Overview</h1>
            <p class="page-description" style="font-size: 1rem;">Daily performance and pipeline tracking.</p>
        </div>
    </div>
</div>

<!-- Quick Actions Bar -->
<div class="quick-actions-bar" style="display: flex; align-items: center; justify-content: space-between; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div>
        <h2 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin: 0;">Activity Command Center</h2>
        <p style="font-size: 0.875rem; color: #64748b; margin: 0.25rem 0 0 0;">Priority tasks and quick entries for today.</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <button onclick="window.location.href='{{ route('tyro-dashboard.pipelines.kanban') }}'" style="background: #eef2ff; color: #4f46e5; border: none; padding: 0.625rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: background 0.2s;">
            Open Kanban
        </button>
        <button style="background: #4f46e5; color: white; border: none; padding: 0.625rem 1rem; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            + New Lead
        </button>
    </div>
</div>

<!-- Row 1: KPIs -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Leads</div>
            <div class="stat-value">{{ number_format($crmStats['total_leads'] ?? 0) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Today's Visits</div>
            <div class="stat-value">{{ number_format($crmStats['today_visits'] ?? 0) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Today's Followups</div>
            <div class="stat-value">{{ number_format($crmStats['today_followups'] ?? 0) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2m6-6a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Monthly Sales</div>
            <div class="stat-value">৳ {{ number_format($crmStats['monthly_sales'] ?? 0, 2) }}</div>
        </div>
    </div>
</div>

<!-- Row 2: Charts -->
<div class="grid-2" style="margin-bottom: 2rem;">
    {{-- Monthly Revenue Chart --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Monthly Revenue Chart</h3>
            <span class="badge badge-secondary">BDT (৳)</span>
        </div>
        <div class="card-body">
            <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">৳ {{ number_format($crmCharts['revenue_total_bdt'] ?? 0, 2) }}</div>
            <div style="border: 1px solid var(--border); border-radius: 10px; padding: 1rem; background: var(--muted);">
                <svg viewBox="0 0 600 180" width="100%" height="150" preserveAspectRatio="none" style="display:block; color: var(--primary);">
                    <g opacity="0.35" stroke="currentColor" style="color: var(--muted-foreground);">
                        <path d="M0 150 H600" />
                        <path d="M0 90 H600" />
                        <path d="M0 30 H600" />
                    </g>
                    <path d="{{ $crmCharts['revenue_area_path'] }}" fill="currentColor" opacity="0.12"></path>
                    <path d="{{ $crmCharts['revenue_line_path'] }}" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <div style="display:flex; justify-content: space-between; margin-top: 0.5rem; font-size: 0.8125rem; color: var(--muted-foreground);">
                    <span>{{ $crmCharts['revenue_range_left'] }}</span>
                    <span>{{ $crmCharts['revenue_range_right'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Lead Status Chart --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lead Status Distribution</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1.5rem; align-items: center; min-height: 200px;">
                <div style="display:flex; align-items:center; justify-content:center;">
                    <svg viewBox="0 0 42 42" width="140" height="140" style="display:block; transform: rotate(-90deg);">
                        <circle cx="21" cy="21" r="15.915" fill="transparent" stroke="var(--border)" stroke-width="5"></circle>
                        @php($offset = 100)
                        @foreach($crmCharts['status_donut'] as $slice)
                            @if($slice['pct'] > 0)
                            <circle
                                cx="21" cy="21" r="15.915"
                                fill="transparent"
                                stroke="{{ $slice['color'] }}"
                                stroke-width="5"
                                stroke-dasharray="{{ $slice['pct'] }} {{ 100 - $slice['pct'] }}"
                                stroke-dashoffset="{{ $offset }}"
                                stroke-linecap="round"
                            ></circle>
                            @php($offset -= $slice['pct'])
                            @endif
                        @endforeach
                    </svg>
                </div>
                <div style="display:flex; flex-direction:column; gap: 0.75rem;">
                    @foreach($crmCharts['status_donut'] as $slice)
                        <div style="display:flex; align-items:center; justify-content: space-between; gap: 1rem;">
                            <div style="display:flex; align-items:center; gap: 0.5rem;">
                                <span style="width: 10px; height: 10px; border-radius: 9999px; background: {{ $slice['color'] }};"></span>
                                <span style="font-size: 0.9375rem; color: var(--foreground);">{{ $slice['label'] }}</span>
                            </div>
                            <div style="font-size: 0.9375rem; color: var(--muted-foreground); font-weight: 600;">{{ $slice['count'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Product & Executive Analytics -->
<div class="grid-2" style="margin-bottom: 2rem;">
    {{-- Product Wise Sales --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Product Wise Sales</h3>
            <span class="badge badge-secondary">Top 5 This Month</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Package</th>
                            <th style="text-align: right;">Total Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['product_sales'] as $item)
                        <tr>
                            <td style="font-weight: 600;">{{ $item->product }}</td>
                            <td><span class="badge badge-secondary" style="font-size: 0.75rem;">{{ $item->package }}</span></td>
                            <td style="text-align: right; font-weight: 700; color: var(--primary);">৳ {{ number_format($item->total_sales, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--muted-foreground); padding: 2rem;">No sales records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Executive Performance --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Executive Performance</h3>
            <span class="badge badge-secondary">Top Performers</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Executive</th>
                            <th style="text-align: center;">Deals</th>
                            <th style="text-align: right;">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['executive_performance'] as $perf)
                        <tr>
                            <td>
                                <a href="{{ route('tyro-dashboard.reports.user-performance', $perf->user->id) }}" style="font-weight: 600; color: var(--primary); text-decoration: none;">
                                    {{ $perf->user->name ?? 'Unknown' }}
                                </a>
                            </td>
                            <td style="text-align: center;">{{ $perf->total_deals }}</td>
                            <td style="text-align: right; font-weight: 600; color: #10b981;">৳ {{ number_format($perf->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--muted-foreground);">No performance data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Lists -->
<div class="grid-2">
    {{-- Today's Visits List --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Today's Visits</h3>
            <span class="badge badge-info">{{ $crmCharts['today_visits_list']->count() }} Interaction(s)</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lead / Client</th>
                            <th>Executive</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['today_visits_list'] as $visit)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $visit->lead->client_name ?? 'N/A' }}</div>
                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $visit->lead->company_name ?? '' }}</div>
                            </td>
                            <td>{{ $visit->user->name ?? 'Unknown' }}</td>
                            <td>{{ $visit->visit_date->format('h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No visits logged today yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Upcoming Followups List --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Upcoming Followups</h3>
            <span class="badge badge-warning">Pending tasks</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lead / Client</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['upcoming_followups_list'] as $followup)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $followup->lead->client_name ?? 'N/A' }}</div>
                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $followup->lead->company_name ?? '' }}</div>
                            </td>
                            <td>
                                <div>{{ $followup->scheduled_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $followup->scheduled_at->format('h:i A') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $followup->scheduled_at->isPast() ? 'badge-danger' : 'badge-secondary' }}">
                                    {{ $followup->scheduled_at->isPast() ? 'Overdue' : 'Scheduled' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No upcoming followups scheduled.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Row 5: Latest Leads & Sales -->
<div class="grid-2" style="margin-top: 2rem;">
    {{-- Latest Leads --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Latest Leads</h3>
            <span class="badge badge-primary">Newest 5</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lead Name</th>
                            <th>Company</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['latest_leads'] as $lead)
                        <tr>
                            <td style="font-weight: 600;">{{ $lead->client_name }}</td>
                            <td>{{ $lead->company_name }}</td>
                            <td>
                                <span class="badge badge-secondary">{{ ucfirst($lead->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No leads found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Latest Sales --}}
    <div class="card" style="height: 100%;">
        <div class="card-header">
            <h3 class="card-title">Latest Sales</h3>
            <span class="badge badge-success">Newest 5</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Amount</th>
                            <th style="text-align: right;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['latest_sales'] as $sale)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $sale->lead->client_name ?? 'N/A' }}</div>
                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">
                                    {{ $sale->service->name ?? '' }}
                                    @if($sale->servicePackage)
                                        ({{ $sale->servicePackage->name }})
                                    @elseif($sale->lead->servicePackage)
                                        ({{ $sale->lead->servicePackage->name }})
                                    @endif
                                </div>
                            </td>
                            <td style="color: #10b981; font-weight: 600;">৳ {{ number_format($sale->amount, 2) }}</td>
                            <td style="text-align: right; color: var(--muted-foreground);">{{ $sale->closed_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No sales found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Row 6: Recent Activity -->
<div style="width: 100%; margin-top: 2rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Activity</h3>
            <span class="badge badge-secondary">Full Width View</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Actor</th>
                            <th>Status</th>
                            <th style="text-align:right;">When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['recent_activities'] as $row)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $row['title'] }}</div>
                                    <div style="font-size: 0.8125rem; color: var(--muted-foreground);">{{ $row['subtitle'] }}</div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.625rem;">
                                        <div style="width: 32px; height: 32px; border-radius: 9999px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem;">
                                            {{ strtoupper(substr($row['actor'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-size: 0.9375rem; font-weight: 500;">{{ $row['actor'] }}</div>
                                            <div style="font-size: 0.8125rem; color: var(--muted-foreground);">{{ $row['actor_meta'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $row['status_badge_class'] }}">{{ $row['status'] }}</span>
                                </td>
                                <td style="text-align:right; color: var(--muted-foreground);">{{ $row['when'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No recent activities.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
