@extends('tyro-dashboard::layouts.app')

@section('title', 'Lead Directory')

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <h1 class="page-title text-slate-900 fw-bold">Lead Management</h1>
                <p class="page-description text-slate-500">Centralized directory for all qualified prospects and opportunities</p>
            </div>
            <div>
                <a href="{{ route('leads.create') }}" class="btn btn-indigo-600 border-0 text-white d-flex align-items-center gap-2 px-4 py-2.5 rounded-xl shadow-sm fw-bold transition-all hover:scale-105 active:scale-95" style="background: #4f46e5;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    New Prospect
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Search & Filter -->
<div class="card border-0 shadow-sm rounded-2xl mb-6 bg-slate-50/50">
    <div class="card-body p-4">
        <form action="{{ route('leads.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label text-[10px] fw-bold text-slate-400 uppercase tracking-wider mb-2">Search Records</label>
                    <div class="input-group input-group-modern shadow-sm">
                        <span class="input-group-text bg-white border-end-0 rounded-start-xl ps-3">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        </span>
                        <input type="text" name="keyword" class="form-control border-start-0 rounded-end-xl h-11 shadow-none" placeholder="Company, client name, or phone..." value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-[10px] fw-bold text-slate-400 uppercase tracking-wider mb-2">Service</label>
                    <select name="service_id" class="form-select rounded-xl h-11 border-slate-200 shadow-sm text-sm fw-bold">
                        <option value="">All Services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-[10px] fw-bold text-slate-400 uppercase tracking-wider mb-2">Current Stage</label>
                    <select name="pipeline_stage_id" class="form-select rounded-xl h-11 border-slate-200 shadow-sm text-sm fw-bold">
                        <option value="">All Stages</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" {{ request('pipeline_stage_id') == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-[10px] fw-bold text-slate-400 uppercase tracking-wider mb-2">Owner</label>
                    <select name="assigned_user_id" class="form-select rounded-xl h-11 border-slate-200 shadow-sm text-sm fw-bold">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-slate-900 text-white rounded-xl h-11 px-4 fw-bold flex-grow-1" style="background: #0f172a;">Apply</button>
                    <a href="{{ route('leads.index') }}" class="btn btn-white border-slate-200 rounded-xl h-11 px-3 d-flex align-items-center justify-content-center" title="Reset Filters">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0zm10-3l-4 4m0-4l4 4"/></svg>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Card View Grid -->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
    @forelse($leads as $lead)
    <div class="col">
        <div class="lead-pro-card h-100 bg-white rounded-2xl border border-slate-200 shadow-sm transition-all duration-300 hover:shadow-xl hover:border-indigo-100">
            <!-- Card Ribbon -->
            <div class="p-4 border-bottom position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex flex-wrap gap-1">
                        @forelse($lead->serviceStatuses as $ss)
                            @php
                                $colorClass = match($ss->status) {
                                    'service_request' => 'bg-emerald-500 shadow-sm shadow-emerald-100',
                                    'not_interested' => 'bg-slate-300',
                                    default => 'bg-amber-400',
                                };
                                $initial = strtoupper(substr($ss->service->name, 0, 1));
                            @endphp
                            <div class="rounded-sm text-[7px] px-1 text-white fw-bold {{ $colorClass }} pointer" 
                                 title="{{ $ss->service->name }}: {{ $ss->status }}"
                                 style="cursor: help;">
                                {{ $initial }}
                            </div>
                        @empty
                            <div class="xx-small fw-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded">
                                No Services
                            </div>
                        @endforelse
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-icon btn-ghost-slate btn-sm rounded-lg" data-bs-toggle="dropdown">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-xl rounded-xl p-2">
                            <li><a class="dropdown-item rounded-lg py-2 fw-bold text-sm" href="{{ route('leads.edit', $lead->id) }}">
                                <svg width="14" height="14" class="me-2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit Profile
                            </a></li>
                            <li><button class="dropdown-item rounded-lg py-2 fw-bold text-sm" data-bs-toggle="modal" data-bs-target="#assignModal{{ $lead->id }}">
                                <svg width="14" height="14" class="me-2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Reassign Owner
                            </button></li>
                        </ul>
                    </div>
                </div>
                <h3 class="h6 fw-bold text-slate-900 mb-1">{{ $lead->company_name }}</h3>
                <p class="xx-small text-slate-400 fw-bold uppercase tracking-tighter mb-0">Client: {{ $lead->client_name }}</p>
            </div>

            <!-- Body -->
            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-xl bg-slate-50 p-2 border border-slate-100">
                        <svg width="20" height="20" class="text-slate-400" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs fw-bold text-slate-900">{{ $lead->phone }}</div>
                        <div class="xx-small text-slate-400 fw-medium">{{ $lead->email ?: 'Email not provided' }}</div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-3 d-flex justify-content-between align-items-center mb-0">
                    <div>
                        <div class="xx-small text-slate-400 uppercase fw-bold mb-1">Assigned To</div>
                        <div class="text-xs fw-bold text-slate-700 d-flex align-items-center">
                            <div class="rounded-circle bg-indigo-100 text-indigo-600 d-flex align-items-center justify-content-center fw-bold me-2" style="width:20px;height:20px;font-size:9px;">
                                {{ substr($lead->assignedUser?->name ?? 'U', 0, 1) }}
                            </div>
                            {{ $lead->assignedUser->name ?? 'Unassigned' }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="xx-small text-slate-400 uppercase fw-bold mb-1">Status</div>
                        <span class="badge rounded-pill px-3 py-1 bg-white border border-indigo-200 text-indigo-700 fw-bold" style="font-size:9px;">
                            {{ optional($lead->stage)->name ?? 'Qualified' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="p-3 bg-slate-50 mt-auto border-top rounded-bottom-2xl">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="xx-small text-slate-400 fw-bold">Joined: {{ $lead->created_at->format('M d, Y') }}</span>
                    <a href="{{ route('leads.edit', $lead->id) }}" class="text-indigo-600 text-[10px] fw-bold text-decoration-none hover:underline">View Timeline →</a>
                </div>
            </div>
        </div>

        <!-- Assign Modal (Pro Version) -->
        <div class="modal fade" id="assignModal{{ $lead->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-2xl rounded-2xl overflow-hidden">
                    <form action="{{ route('leads.assign', $lead->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-slate-50 border-bottom px-4 py-3">
                            <h5 class="modal-title fw-bold text-slate-900 fs-6">Assign Relationship Manager</h5>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-4 text-center">
                                <div class="fw-bold text-slate-900 mb-1 h5">{{ $lead->company_name }}</div>
                                <p class="text-slate-500 small mb-0">Select the executive who will handle this account.</p>
                            </div>
                            
                            <label class="form-label text-xs fw-bold text-slate-700 uppercase tracking-wider mb-2">Team Member</label>
                            <select name="assigned_to" class="form-select rounded-xl h-12 border-slate-200 shadow-none fw-bold" required>
                                <option value="">Select executive...</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ $lead->assigned_user == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer bg-slate-50 border-top p-4 d-flex justify-content-between">
                            <button type="button" class="btn border-slate-200 text-slate-600 bg-white hover:bg-slate-50 px-4 py-2 fw-bold" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-indigo-600 text-white px-5 py-2 fw-bold" style="background: #4f46e5;">Assign Prospect</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 py-20 text-center">
        <div class="empty-state-lux">
            <div class="lux-icon" style="font-size: 3rem;">📂</div>
            <h3 class="fw-bold text-slate-900">No leads found</h3>
            <p class="text-slate-500">Simplify your search or try adding a new prospect to the directory.</p>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $leads->links() }}
</div>

<style>
    .xx-small { font-size: 0.65rem; }
    .page-title { letter-spacing: -0.04em; }
    .input-group-modern .input-group-text { color: var(--slate-400); }
    .rounded-start-xl { border-top-left-radius: 0.75rem !important; border-bottom-left-radius: 0.75rem !important; }
    .rounded-end-xl { border-top-right-radius: 0.75rem !important; border-bottom-right-radius: 0.75rem !important; }
    .rounded-xl { border-radius: 0.75rem !important; }
    .rounded-bottom-2xl { border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem; }
    .btn-ghost-slate:hover { background: #f8fafc; color: #0f172a; }
</style>
@endsection
