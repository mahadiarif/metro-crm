@extends('tyro-dashboard::layouts.user')

@section('title', 'Dashboard')

@section('breadcrumb')
<span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Welcome back, {{ $user->name ?? 'User' }}!</h1>
            <p class="page-description" style="font-size: 1rem;">Here's your sales activity overview for today.</p>
        </div>
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
            <h3 class="card-title">Monthly Revenue Trend</h3>
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
            <h3 class="card-title">Lead Status</h3>
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
            <h3 class="card-title">Product Sales</h3>
            <span class="badge badge-secondary">Monthly</span>
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
                            <td style="font-weight: 600;">{{ $item['product'] }}</td>
                            <td><span class="badge badge-secondary" style="font-size: 0.75rem;">{{ $item['package'] }}</span></td>
                            <td style="text-align: right; font-weight: 700; color: var(--primary);">৳ {{ number_format($item['value'], 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: var(--muted-foreground); padding: 2rem;">No sales recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Performance --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Performance Ranking</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Executive</th>
                            <th style="text-align: right;">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['executive_performance'] as $perf)
                        <tr>
                            <td>{{ $perf->user->name ?? 'Unknown' }}</td>
                            <td style="text-align: right; font-weight: 600; color: #10b981;">৳ {{ number_format($perf->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" style="text-align: center; color: var(--muted-foreground);">No data available.</td>
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
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lead</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['today_visits_list'] as $visit)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $visit->lead->client_name ?? 'N/A' }}</div>
                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $visit->user->name ?? '' }}</div>
                            </td>
                            <td>{{ $visit->visit_date->format('h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No visits logged today.</td>
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
            <h3 class="card-title">My Followups</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Lead</th>
                            <th>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($crmCharts['upcoming_followups_list'] as $followup)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $followup->lead->client_name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div>{{ $followup->scheduled_at->format('M d') }}</div>
                                <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $followup->scheduled_at->format('h:i A') }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No upcoming tasks.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Row 5: Recent Activity -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h3 class="card-title">Recent Activity</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Actor</th>
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
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="width: 28px; height: 28px; border-radius: 9999px; background: var(--secondary); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                        {{ strtoupper(substr($row['actor'], 0, 1)) }}
                                    </div>
                                    <span style="font-size: 0.875rem;">{{ $row['actor'] }}</span>
                                </div>
                            </td>
                            <td style="text-align:right; color: var(--muted-foreground);">{{ $row['when'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 1.5rem; color: var(--muted-foreground);">No activities yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
