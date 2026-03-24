@extends('tyro-dashboard::layouts.admin')

@section('title', 'Team Performance Report')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.reports.team-performance') }}">Reports</a>
<span>/ Team Performance</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Team Performance</h1>
            <p class="page-description">Analyze the performance metrics of your assigned team members.</p>
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
<div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 160px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: var(--muted-foreground); font-weight: 500;">Team Members</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem;">{{ number_format($aggregates->total_team_members) }}</div>
    </div>
    <div class="card" style="flex: 1; min-width: 160px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: var(--muted-foreground); font-weight: 500;">Total Leads</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem;">{{ number_format($aggregates->total_leads) }}</div>
    </div>
    <div class="card" style="flex: 1; min-width: 160px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: var(--muted-foreground); font-weight: 500;">Total Sales</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem;">{{ number_format($aggregates->total_sales) }}</div>
    </div>
    <div class="card" style="flex: 1; min-width: 160px; padding: 1.25rem 1.5rem;">
        <div style="font-size: 0.8125rem; color: #10b981; font-weight: 500;">MTD Team Revenue</div>
        <div style="font-size: 1.75rem; font-weight: 700; margin-top: 0.25rem; color: #10b981;">৳ {{ number_format($aggregates->monthly_revenue, 2) }}</div>
    </div>
</div>

{{-- Filters & Search --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form action="{{ route('tyro-dashboard.reports.team-performance') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <div class="search-box" style="display: flex; gap: 10px; align-items: center; flex: 1; max-width: 400px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; color: var(--muted-foreground);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" class="form-input" placeholder="Search executive name..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('tyro-dashboard.reports.team-performance') }}" class="btn btn-ghost" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Clear</a>
            @endif
        </form>
    </div>
</div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Executive</th>
                        <th>Role</th>
                        <th style="text-align: center;">Total Leads</th>
                        <th style="text-align: center;">Visits</th>
                        <th style="text-align: center;">Follow-ups</th>
                        <th style="text-align: center;">Total Sales</th>
                        <th style="text-align: right;">MTD Revenue</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teamMembers as $member)
                    <tr>
                        <td style="font-weight: 500;">{{ $member->name }}</td>
                        <td><span class="badge badge-secondary">{{ Str::title(str_replace('_', ' ', $member->role ?? 'N/A')) }}</span></td>
                        <td style="text-align: center; color: var(--muted-foreground);">{{ $member->stats['total_leads'] }}</td>
                        <td style="text-align: center; color: var(--muted-foreground);">{{ $member->stats['total_visits'] }}</td>
                        <td style="text-align: center; color: var(--muted-foreground);">{{ $member->stats['total_followups'] }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ $member->stats['total_sales'] }}</td>
                        <td style="text-align: right; font-weight: 700; color: #10b981; white-space: nowrap;">৳ {{ number_format($member->stats['monthly_revenue'], 2) }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('tyro-dashboard.reports.user-performance', $member->id) }}" class="btn btn-primary" style="font-size: 0.8125rem; padding: 0.3rem 0.75rem; height: auto;">View Profile</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 4rem; color: var(--muted-foreground);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 40px; height: 40px; margin: 0 auto 1rem; display: block; opacity: 0.4;"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                            No team members found under your supervision.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
