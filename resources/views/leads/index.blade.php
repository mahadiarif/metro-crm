@extends('tyro-dashboard::layouts.app')

@section('title', 'Lead Management')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<span>Leads</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Lead Management</h1>
        </div>
        <a href="{{ route('leads.create') }}" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Lead
        </a>
    </div>
</div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif

<!-- Advanced Filters -->
<div class="card mb-4 mt-3">
    <div class="card-body">
        <form action="{{ route('leads.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Keyword</label>
                    <input type="text" name="keyword" class="form-control" placeholder="Company, Client or Contact..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Pipeline Stage</label>
                    <select name="pipeline_stage_id" class="form-select">
                        <option value="">All Stages</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" {{ request('pipeline_stage_id') == $stage->id ? 'selected' : '' }}>
                                {{ $stage->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Service</label>
                    <select name="service_id" class="form-select">
                        <option value="">All Services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Assigned User</label>
                    <select name="assigned_user_id" class="form-select">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('leads.index') }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Leads Table -->
<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Client Name</th>
                    <th>Contact Person</th>
                    <th>Service</th>
                    <th>Stage</th>
                    <th>Assigned To</th>
                    <th>Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td><strong>{{ $lead->company_name }}</strong></td>
                    <td>{{ $lead->client_name }}</td>
                    <td>{{ $lead->contact_person }}</td>
                    <td>{{ optional($lead->service)->name }}</td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ optional($lead->stage)->name }}
                        </span>
                    </td>
                    <td>{{ optional($lead->assignedUser)->name ?? 'Unassigned' }}</td>
                    <td>{{ $lead->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <!-- Assign Button/Modal Trigger -->
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $lead->id }}">
                                Assign
                            </button>
                            <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-sm btn-icon btn-ghost">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px;">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>

                        <!-- Assign Modal -->
                        <div class="modal fade" id="assignModal{{ $lead->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('leads.assign', $lead->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Assign Lead: {{ $lead->company_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <label class="form-label">Select Team Member</label>
                                            <select name="assigned_to" class="form-select" required>
                                                <option value="">Choose User...</option>
                                                @foreach($users as $u)
                                                    <option value="{{ $u->id }}" {{ $lead->assigned_user == $u->id ? 'selected' : '' }}>
                                                        {{ $u->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Assign Now</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">No leads found matching your criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
