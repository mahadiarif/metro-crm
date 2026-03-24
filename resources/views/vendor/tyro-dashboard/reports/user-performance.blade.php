@extends('tyro-dashboard::layouts.admin')

@section('title', 'Salesperson Performance - ' . $user->name)

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="separator">/</span>
<span>{{ $user->name }} Performance</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <div style="width: 80px; height: 80px; border-radius: 9999px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="page-title">{{ $user->name }}</h1>
                <p class="page-description">{{ $user->email }} • {{ $user->roles->first()->name ?? 'Sales Executive' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Row 1: Individual KPIs -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon stat-icon-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Conversion Rate</div>
            <div class="stat-value">{{ $stats['conversion_rate'] }}%</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-10V6m0 12v-2m6-6a6 6 0 11-12 0 6 6 0 0112 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value">৳ {{ number_format($stats['monthly_revenue'], 2) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Visits</div>
            <div class="stat-value">{{ number_format($stats['total_visits']) }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon stat-icon-warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Deals</div>
            <div class="stat-value">{{ number_format($stats['total_sales']) }}</div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 2rem;">
    <!-- Sales Trend -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Sales Trend</h3>
        </div>
        <div class="card-body">
            <div style="height: 200px; display: flex; align-items: flex-end; gap: 1rem; padding-top: 1rem;">
                @foreach($salesTrend as $trend)
                    @php($maxAmount = collect($salesTrend)->max('amount') ?: 1)
                    @php($height = ($trend['amount'] / $maxAmount) * 100)
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                        <div style="width: 100%; background: var(--primary); border-radius: 4px 4px 0 0; height: {{ max(5, $height) }}%; opacity: {{ 0.4 + ($height/200) }};"></div>
                        <span style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $trend['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Assigned Leads -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Latest Assigned Leads</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($recentLeads->count())
                            @foreach($recentLeads as $lead)
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">{{ $lead->company_name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--muted-foreground);">{{ $lead->service->name ?? 'Service N/A' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ ucfirst($lead->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="2" style="text-align: center; padding: 2rem; color: var(--muted-foreground);">No leads assigned.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
