@extends('tyro-dashboard::layouts.app')
@use('App\Models\Lead')

@section('title', 'Daily Sales Call')

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Daily Sales Call</h1>
            <p class="page-description">Manage and optimize your tele-sales productivity</p>
        </div>
        <div>
            <a href="{{ route('tyro-dashboard.sales-calls.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                Log New Call
            </a>
        </div>
    </div>
</div>


<!-- Native Stats Grid (6 Elite Cards) -->
<div class="stats-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="elite-stat-card">
        <div class="esc-icon bg-indigo-50 text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M17 8H7"/><path d="M17 12H7"/><path d="M17 16H7"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Total Calls</span>
            <div class="esc-value text-slate-900">{{ number_format($totalCalls) }}</div>
            <span class="esc-sub text-slate-400">Lifetime Logs</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-blue-50 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Today</span>
            <div class="esc-value text-slate-900">{{ $todayCalls->count() }}</div>
            <span class="esc-sub text-blue-500 font-bold">Inbound Queue</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-rose-50 text-rose-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Overdue</span>
            <div class="esc-value text-slate-900">{{ $overdueCalls->count() }}</div>
            <span class="esc-sub text-rose-500 font-bold">Take Action</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-emerald-50 text-emerald-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Converted</span>
            <div class="esc-value text-slate-900">{{ number_format($serviceInterestsCount) }}</div>
            <span class="esc-sub text-emerald-500 font-bold">Interests</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-pink-50 text-pink-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="m14 18-1.4-1.45L16.15 13H4v-2h12.15L12.6 7.45 14 6l6 6z"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Follow Ups</span>
            <div class="esc-value text-slate-900">{{ number_format($followUpsCount) }}</div>
            <span class="esc-sub text-pink-500 font-bold">Planned</span>
        </div>
    </div>

    <div class="elite-stat-card">
        <div class="esc-icon bg-orange-50 text-orange-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="esc-info">
            <span class="esc-label">Frequency</span>
            <div class="esc-value text-slate-900">{{ $reachRate }}%</div>
            <span class="esc-sub text-orange-500 font-bold">Reach Rate</span>
        </div>
    </div>
</div>

<!-- Tabs & Control -->
<div class="mb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div class="segmented-control p-1 bg-slate-100 rounded-lg d-inline-flex border border-slate-200">
            <a href="?tab=today&filter={{ $filter }}" class="seg-item {{ $tab === 'today' ? 'active shadow-sm' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                Daily Feed
            </a>
            <a href="?tab=overdue&filter={{ $filter }}" class="seg-item {{ $tab === 'overdue' ? 'active shadow-sm' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Overdue
            </a>
            <a href="?tab=history&filter={{ $filter }}" class="seg-item {{ $tab === 'history' ? 'active shadow-sm' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                Call History
            </a>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="filter-group d-flex align-items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg shadow-sm">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filter Intent</span>
                <select class="form-select border-0 bg-transparent p-0 text-xs font-bold text-slate-700 focus:ring-0" style="width: auto; cursor: pointer;" onchange="window.location.href='?tab={{ $tab }}&filter=' + this.value">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Active</option>
                    <option value="follow_up" {{ $filter === 'follow_up' ? 'selected' : '' }}>Follow Ups</option>
                    <option value="service_request" {{ $filter === 'service_request' ? 'selected' : '' }}>Successful</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Queue Content -->
<div class="content-container">
    @if($tab === 'today' || $tab === 'overdue')
        <div class="row g-4">
            @php $currentQueue = ($tab === 'today') ? $todayCalls : $overdueCalls; @endphp
            @forelse($currentQueue as $lead)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="elite-lead-card h-100">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h3 class="lead-title mb-1">{{ $lead->company_name }}</h3>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="status-dot-pulse {{ $tab === 'overdue' ? 'bg-rose-500' : 'bg-emerald-500' }}"></div>
                                        <span class="text-[10px] font-black uppercase {{ $tab === 'overdue' ? 'text-rose-500' : 'text-slate-400' }} tracking-wider">
                                            {{ $tab === 'overdue' ? 'Critical Overdue' : 'Awaiting Outreach' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="badge-elite {{ $tab === 'overdue' ? 'border-rose-200 text-rose-600 bg-rose-50' : '' }}">
                                    {{ $tab === 'overdue' ? 'OVERDUE' : 'SCHEDULED' }}
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-6">
                                <div class="d-flex align-items-center gap-3 text-slate-600">
                                    <div class="icon-circle-sm bg-slate-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <span class="text-xs font-bold">{{ $lead->client_name ?: 'Contact POC' }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-3 text-slate-500 font-bold mb-3">
                                    <div class="icon-circle-sm bg-slate-100 text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                                    </div>
                                    <span class="text-xs">{{ $lead->phone }}</span>
                                </div>
                            </div>

                            <div class="roadmap-steps mb-5 p-3 bg-slate-50/50 rounded-xl border border-slate-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Consultation Depth</span>
                                    <span class="text-[10px] font-black text-slate-900">{{ $lead->salesCalls->count() }} / 3</span>
                                </div>
                                <div class="progress-steps-compact">
                                    @php $cCount = $lead->salesCalls->count(); @endphp
                                    @for($i = 1; $i <= 3; $i++)
                                        <div class="step-line {{ $cCount >= $i ? 'active' : '' }}"></div>
                                    @endfor
                                </div>
                            </div>

                            <a href="{{ route('tyro-dashboard.sales-calls.create', ['lead_id' => $lead->id]) }}" class="elite-action-btn-primary w-full">
                                <span>Log New Interaction</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.81 12.81 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-20 text-center">
                    <div class="bg-slate-50 d-inline-flex p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-300"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                    </div>
                    <h4 class="text-slate-900 font-black tracking-tight">Outreach Session Empty</h4>
                    <p class="text-slate-400 text-sm">No scheduled engagements identified for this cycle.</p>
                </div>
            @endforelse
        </div>
            @empty
                <div class="col-12 py-5 text-center text-muted-foreground">
                    <p>No tele-sales session logs found.</p>
                </div>
            @endforelse
        </div>
    @endif

    @if($tab === 'history')
        <div class="card border-0 shadow-sm rounded-xl overflow-hidden bg-white">
            <div class="table-container">
                <table class="table mb-0" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="py-4 px-5 text-[10px] font-black uppercase text-slate-500 tracking-widest">Client profile</th>
                            <th class="py-4 px-0 text-[10px] font-black uppercase text-slate-500 tracking-widest">Contacts & Source</th>
                            <th class="py-4 px-0 text-[10px] font-black uppercase text-slate-500 tracking-widest">Market context</th>
                            <th class="py-4 px-0 text-[10px] font-black uppercase text-slate-500 tracking-widest">Call Outcome</th>
                            <th class="py-4 px-5 text-[10px] font-black uppercase text-slate-500 tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($historyCalls as $call)
                            <tr class="elite-row transition-all duration-200">
                                <td class="py-4 px-5 align-top">
                                    <div class="text-sm font-black text-slate-900 tracking-tight">{{ $call->lead->company_name ?? 'N/A' }}</div>
                                    <div class="d-flex align-items-center gap-1.5 mt-1.5">
                                        <span class="text-xs font-bold text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded">{{ $call->contact_person ?: $call->lead->client_name }}</span>
                                        @if($call->designation)
                                            <span class="text-[10px] text-slate-400 font-medium">• {{ $call->designation }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 d-flex align-items-center gap-1.5 text-[11px] text-slate-500 font-medium italic">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-slate-300"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        {{ Str::limit($call->address ?: ($call->lead->address ?: 'No address logged'), 30) }}
                                    </div>
                                </td>
                                <td class="py-4 px-0 align-top">
                                    <div class="text-[11px] font-bold text-slate-900">{{ $call->phone ?: $call->lead->phone }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium mt-1">{{ $call->email ?: ($call->lead->email ?: 'no-email@recorded') }}</div>
                                    @if($call->source)
                                        <div class="mt-2">
                                            <span class="text-[9px] font-black uppercase px-1.5 py-0.5 bg-slate-900 text-white rounded">
                                                {{ $call->source }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-0 align-top">
                                    <div class="d-flex flex-column gap-3">
                                        <div>
                                            <div class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Competitor</div>
                                            <div class="text-xs font-bold text-slate-700 italic">{{ $call->existing_provider ?: 'None identified' }}</div>
                                        </div>
                                        <div>
                                            <div class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-1">Usage</div>
                                            <div class="text-xs font-bold text-slate-700 truncate" style="max-width: 150px;">{{ $call->current_usage ?: 'Not specified' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-0 align-top">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @php
                                            $outcomeLabel = match($call->outcome) {
                                                'service_request' => 'Successful',
                                                'not_interested' => 'Dropped',
                                                default => 'Follow-Up',
                                            };
                                            $statusStyle = match($call->outcome) {
                                                'service_request' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'not_interested' => 'bg-rose-100 text-rose-700 border-rose-200',
                                                default => 'bg-blue-50 text-blue-700 border-blue-100',
                                            };
                                        @endphp
                                        <div class="d-inline-flex align-items-center px-2 py-1 rounded-full border {{ $statusStyle }} text-[10px] font-black uppercase tracking-wider">
                                            {{ $outcomeLabel }}
                                        </div>
                                    </div>
                                    <div class="text-[11px] leading-relaxed text-slate-500 bg-slate-50/50 p-2.5 rounded-lg border border-slate-100 italic" style="max-width: 250px;">
                                        "{{ Str::limit($call->notes, 100) ?: 'Call consultation finalized.' }}"
                                <tr>
                                    <td class="align-middle py-4 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="icon-circle-sm bg-slate-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.81 12.81 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-slate-900 fw-bold text-sm">{{ $call->company_name }}</div>
                                                <div class="text-slate-400 text-xs">{{ $call->client_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm fw-semibold text-slate-500">{{ $call->call_date?->format('d M, Y') }}</td>
                                    <td class="align-middle text-center">
                                        @php
                                            $outcomeClass = match($call->outcome) {
                                                'service_request' => 'badg-success',
                                                'follow_up' => 'badg-primary',
                                                'not_interested' => 'badg-danger',
                                                default => 'badg-primary'
                                            };
                                        @endphp
                                        <span class="elite-badge {{ $outcomeClass }}">
                                            {{ Str::headline($call->outcome) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @php
                                            $statusClass = match($call->status) {
                                                'completed' => 'badg-success',
                                                'in_progress' => 'badg-primary',
                                                'pending' => 'badg-warning',
                                                default => 'badg-primary'
                                            };
                                        @endphp
                                        <span class="elite-badge {{ $statusClass }}">
                                            {{ Str::headline($call->status ?: 'Pending') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-end px-4">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button wire:click="$dispatch('openOutcomeModal', { callId: {{ $call->id }} })" class="elite-edit-btn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                        @empty
                            <tr><td colspan="5" class="py-20 text-center text-slate-400 italic">No historical outreach activity identified.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($historyCalls->hasPages())
                <div class="card-footer border-t bg-slate-50/50 py-4 px-5">
                    {{ $historyCalls->links() }}
                </div>
            @endif
        </div>
    @endif
</div>

<style>
    .font-bold { font-weight: 700; }
    .shadcn-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .shadcn-card:hover { transform: translateY(-3px); box-shadow: var(--card-shadow-hover); }

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
    .status-dot-pulse { width: 6px; height: 6px; border-radius: 50%; position: relative; }
    .status-dot-pulse::after { content: ''; position: absolute; width: 100%; height: 100%; top: 0; left: 0; background: inherit; border-radius: 50%; animation: pulse 2s infinite; }
    @keyframes pulse { 0% { transform: scale(1); opacity: 0.8; } 70% { transform: scale(3); opacity: 0; } 100% { transform: scale(1); opacity: 0; } }

    .elite-action-btn-primary {
        background: #0f172a; color: #ffffff; padding: 0.75rem 1.25rem; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; font-weight: 700;
        text-decoration: none; transition: 0.2s; border: none;
    }
    .elite-action-btn-primary:hover { background: #1e293b; transform: translateY(-1px); box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2); color: #fff; }

    .progress-steps-compact { display: flex; gap: 4px; height: 4px; }
    .step-line { flex: 1; background-color: #e2e8f0; border-radius: 10px; }
    .step-line.active { background-color: #0f172a; }

    /* Elite UI Components */
    .segmented-control { gap: 4px; }
    .seg-item { padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 700; color: #64748b; border-radius: 6px; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; }
    .seg-item.active { background: #ffffff; color: #0f172a; }
    .seg-item:not(.active):hover { color: #0f172a; background: rgba(0,0,0,0.02); }

    .elite-row:hover { background: #fcfdfe; }
    
    .elite-edit-btn {
        width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; color: #64748b;
        display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s;
    }
    .elite-edit-btn:hover { background: #0f172a; color: #fff; border-color: #0f172a; transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }

    /* Tailwind Compat Classes */
    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .bg-indigo-50 { background-color: #f5f3ff; }
    .text-indigo-600 { color: #4f46e5; }
    .bg-emerald-100 { background-color: #d1fae5; }
    .text-emerald-700 { color: #047857; }
    .bg-rose-100 { background-color: #ffe4e6; }
    .text-rose-700 { color: #be123c; }
    .bg-blue-50 { background-color: #eff6ff; }
    .text-blue-700 { color: #1d4ed8; }
</style>
@endsection
