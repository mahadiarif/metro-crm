@extends('tyro-dashboard::layouts.app')
@use('App\Models\Lead')

@section('title', 'Daily Sales Visit')

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title text-slate-900 fw-bold mb-1">Daily Sales Visit List</h1>
            <p class="text-xs text-slate-500 font-medium">Track your daily visits and client progress</p>
        </div>
        <div>
            <a href="{{ route('tyro-dashboard.sales-visits.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Add New Visit Report
            </a>
        </div>
    </div>
</div>

<!-- Native Stats Grid (6 Elite Cards) -->
<div class="stats-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="elite-stat-card">
        <div class="esc-icon bg-indigo-50 text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Completed</span>
            <div class="esc-value text-slate-900">{{ number_format($totalVisits) }}</div>
            <span class="esc-sub text-slate-400">Total Logged</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-blue-50 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Scheduled</span>
            <div class="esc-value text-slate-900">{{ $scheduledVisits->count() }}</div>
            <span class="esc-sub text-blue-500 font-bold">Field Queue</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-rose-50 text-rose-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6h-6z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Hot Leads</span>
            <div class="esc-value text-slate-900">{{ number_format($hotLeadsCount) }}</div>
            <span class="esc-sub text-rose-500 font-bold">Priority</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-emerald-50 text-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Interests</span>
            <div class="esc-value text-slate-900">{{ number_format($serviceInterestsCount) }}</div>
            <span class="esc-sub text-emerald-500 font-bold">Gains</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-pink-50 text-pink-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9zm0 16c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zm1-11h-2v3H8v2h3v3h2v-3h3v-2h-3V8z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Insight</span>
            <div class="esc-value text-slate-900">{{ number_format($marketIntelCount) }}</div>
            <span class="esc-sub text-pink-500 font-bold">Market Intel</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-orange-50 text-orange-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8h-2z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Efficiency</span>
            <div class="esc-value text-slate-900">{{ $successRate }}%</div>
            <span class="esc-sub text-orange-500 font-bold">Success</span>
        </div>
    </div>
</div>

<!-- Tabs & Control -->
<div class="mb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div class="segmented-control p-1 bg-slate-100 rounded-lg d-inline-flex border border-slate-200">
            <a href="?tab=today&filter={{ $filter }}" class="seg-item {{ $tab === 'today' ? 'active shadow-sm' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="9" y1="4" x2="9" y2="22"/></svg>
                Daily Feed
            </a>
            <a href="?tab=history&filter={{ $filter }}" class="seg-item {{ $tab === 'history' ? 'active shadow-sm' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                Visit History
            </a>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="filter-group d-flex align-items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg shadow-sm">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filter Outcome</span>
                <select class="form-select border-0 bg-transparent p-0 text-xs font-bold text-slate-700 focus:ring-0" style="width: auto; cursor: pointer;" onchange="window.location.href='?tab={{ $tab }}&filter=' + this.value">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Engagement</option>
                    <option value="follow_up" {{ $filter === 'follow_up' ? 'selected' : '' }}>Pending Follow-up</option>
                    <option value="service_request" {{ $filter === 'service_request' ? 'selected' : '' }}>Success Converged</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="content-container">
    @if($tab === 'today')
        <div class="row g-4">
            @forelse($scheduledVisits as $lead)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="elite-lead-card h-100">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h3 class="lead-title mb-1">{{ $lead->company_name }}</h3>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="status-dot-pulse"></div>
                                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Awaiting Deployment</span>
                                    </div>
                                </div>
                                <div class="badge-elite">
                                    {{ Str::upper(optional($lead->service)->name ?: 'GENERAL') }}
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-6">
                                <div class="d-flex align-items-center gap-3 text-slate-600">
                                    <div class="icon-circle-sm bg-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <span class="text-xs font-bold">{{ $lead->client_name ?: 'Unknown POC' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-3 text-slate-500">
                                    <div class="icon-circle-sm bg-slate-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </div>
                                    <span class="text-[11px] font-medium">{{ Str::limit($lead->address, 45) ?: 'No precise geolocation' }}</span>
                                </div>
                            </div>

                            <a href="{{ route('tyro-dashboard.sales-visits.create', ['lead_id' => $lead->id]) }}" class="elite-action-btn-primary w-full">
                                <span>Write Visit Report</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-20 text-center">
                    <div class="bg-slate-50 d-inline-flex p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-300"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                    </div>
                    <h4 class="text-slate-900 font-black tracking-tight">Field Schedule Excused</h4>
                    <p class="text-slate-400 text-sm">No synchronized field interactions required for this cycle.</p>
                </div>
            @endforelse
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
            <div class="table-container">
                <table class="table mb-0" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="py-4 px-5 text-[10px] font-black uppercase text-slate-500 tracking-widest">Visit Info</th>
                            <th class="py-4 px-0 text-[10px] font-black uppercase text-slate-500 tracking-widest">Lead intelligence</th>
                            <th class="py-4 px-0 text-[10px] font-black uppercase text-slate-500 tracking-widest">Market Context</th>
                            <th class="py-4 px-0 text-[10px] font-black uppercase text-slate-500 tracking-widest">Status / Outcome</th>
                            <th class="py-4 px-5 text-[10px] font-black uppercase text-slate-500 tracking-widest text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($historyVisits as $visit)
                            <tr class="elite-row transition-all duration-200">
                                <td class="py-4 px-5 align-top">
                                    <div class="d-flex flex-column gap-1">
                                        <div class="badge-solid-xs bg-slate-900">{{ $visit->visit_stage ?: 'Entry log' }}</div>
                                        <div class="text-[11px] font-bold text-slate-900 mt-1">{{ $visit->visit_date->format('d M, Y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">{{ $visit->visit_date->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="py-4 px-0 align-top">
                                    <div class="text-sm font-black text-slate-900 tracking-tight">{{ $visit->dailySalesVisit->lead->company_name ?? 'N/A' }}</div>
                                    <div class="d-flex align-items-center gap-1.5 mt-1.5">
                                        <span class="text-xs font-bold text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded">{{ $visit->contact_person ?: $visit->dailySalesVisit->lead->client_name }}</span>
                                        @if($visit->designation)
                                            <span class="text-[10px] text-slate-400 font-medium">• {{ $visit->designation }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-1.5 text-[11px] text-slate-500 font-medium truncate" style="max-width: 240px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-slate-300"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ $visit->address ?: $visit->dailySalesVisit->lead->address }}
                                    </div>
                                </td>
                                <td class="py-4 px-0 align-top">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="intelligence-item">
                                            <div class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Competitor</div>
                                            <div class="text-xs font-bold text-slate-700 italic flex align-items-center gap-1">
                                                <div style="width: 4px; height: 4px; border-radius: 50%; background: #e2e8f0;"></div>
                                                {{ $visit->existing_provider ?: 'None identified' }}
                                            </div>
                                        </div>
                                        <div class="intelligence-item">
                                            <div class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Current Usage</div>
                                            <div class="text-xs font-bold text-slate-700 truncate" style="max-width: 150px;">
                                                {{ $visit->current_usage ?: 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-0 align-top">
                                    @php
                                        $statusClass = match($visit->status) {
                                            'service_request' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'not_interested' => 'bg-rose-100 text-rose-700 border-rose-200',
                                            default => 'bg-blue-50 text-blue-700 border-blue-100',
                                        };
                                        $statusIcon = match($visit->status) {
                                            'service_request' => '<svg width="10" height="10" fill="currentColor" class="me-1"><circle cx="5" cy="5" r="4"/></svg>',
                                            default => '<svg width="10" height="10" fill="currentColor" opacity="0.5" class="me-1"><circle cx="5" cy="5" r="4"/></svg>'
                                        };
                                    @endphp
                                    <div class="d-inline-flex align-items-center px-2 py-1 rounded-full border {{ $statusClass }} text-[10px] font-black uppercase tracking-wider mb-2">
                                        {!! $statusIcon !!}
                                        {{ str_replace('_', ' ', $visit->status) }}
                                <tr class="elite-row">
                                    <td class="align-middle py-4 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle-sm bg-slate-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-slate-900 fw-bold text-sm">{{ $visit->company_name }}</div>
                                                <div class="text-slate-400 text-xs">{{ $visit->client_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm fw-semibold text-slate-500">{{ $visit->visit_date?->format('d M, Y') }}</td>
                                    <td class="align-middle">
                                        @php
                                            $badgeClass = match($visit->visit_status) {
                                                'completed' => 'badg-success',
                                                'in_progress' => 'badg-primary',
                                                'pending' => 'badg-warning',
                                                default => 'badg-primary'
                                            };
                                        @endphp
                                        <span class="elite-badge {{ $badgeClass }}">
                                            {{ Str::headline($visit->visit_status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $tempClass = match($visit->lead_temperature) {
                                                'hot' => 'badg-danger',
                                                'warm' => 'badg-warning',
                                                'cold' => 'badg-primary',
                                                default => 'badg-primary'
                                            };
                                        @endphp
                                        <span class="elite-badge {{ $tempClass }} rounded-pill" style="padding: 2px 10px;">
                                            {{ strtoupper($visit->lead_temperature ?: 'WARM') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end px-4">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button wire:click="$dispatch('openVisitModal', { visitId: {{ $visit->id }} })" class="elite-edit-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <div class="bg-slate-50 p-4 rounded-full mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-300"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
                                        </div>
                                        <p class="text-slate-400 font-bold text-sm mb-0">No field interactions identified.</p>
                                        <span class="text-xs text-slate-300">Try adjusting your filters to see more results.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($historyVisits->hasPages())
                <div class="card-footer border-t bg-slate-50/50 py-4 px-5">
                    {{ $historyVisits->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<style>
    /* Elite Metric System */
    .elite-stat-card {
        background: #ffffff; padding: 1.5rem; border-radius: 16px; border: 1px solid #f1f5f9;
        display: flex; flex-direction: column; gap: 1rem; transition: 0.3s;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    .elite-stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); border-color: #e2e8f0; }
    .esc-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .esc-value { font-size: 1.5rem; font-weight: 900; line-height: 1; margin: 4px 0; letter-spacing: -0.5px; }
    .esc-label { font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #64748b; }
    .esc-sub { font-size: 10px; font-weight: 600; }

    /* Elite Lead Management */
    .elite-lead-card { background: #ffffff; border-radius: 20px; border: 1px solid #f1f5f9; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
    .elite-lead-card:hover { border-color: #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); transform: translateY(-2px); }
    .lead-title { font-size: 1.25rem; font-weight: 900; color: #0f172a; tracking: -0.5px; }
    .badge-elite { padding: 4px 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 10px; font-weight: 950; color: #475569; letter-spacing: 0.5px; }
    
    .icon-circle-sm { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .status-dot-pulse { width: 6px; height: 6px; border-radius: 50%; background: #3b82f6; position: relative; }
    .status-dot-pulse::after { content: ''; position: absolute; width: 100%; height: 100%; top: 0; left: 0; background: #3b82f6; border-radius: 50%; animation: pulse 2s infinite; }
    
    @keyframes pulse { 0% { transform: scale(1); opacity: 0.8; } 70% { transform: scale(3); opacity: 0; } 100% { transform: scale(1); opacity: 0; } }

    .elite-action-btn-primary {
        background: #0f172a; color: #ffffff; padding: 0.75rem 1.25rem; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 700;
        text-decoration: none; transition: 0.2s; border: none;
    }
    .elite-action-btn-primary:hover { background: #1e293b; transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2); color: #fff; }

    /* Layout & Utilities */
    .segmented-control { gap: 4px; }
    .seg-item { padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 700; color: #64748b; border-radius: 6px; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; }
    .seg-item.active { background: #ffffff; color: #0f172a; }
    .seg-item:not(.active):hover { color: #0f172a; background: rgba(0,0,0,0.02); }

    .elite-row:hover { background: #fcfdfe; }
    .badge-solid-xs { display: inline-flex; padding: 2px 6px; border-radius: 4px; color: #fff; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
    
    .elite-edit-btn {
        width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; color: #64748b;
        display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;
    }
    .elite-edit-btn:hover { background: #0f172a; color: #fff; border-color: #0f172a; transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
</style>
@endsection
