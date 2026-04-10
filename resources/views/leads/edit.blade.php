@extends('tyro-dashboard::layouts.app')

@section('title', 'Edit Lead: ' . $lead->company_name)

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('leads.index') }}">Leads</a>
<span class="breadcrumb-separator">/</span>
<span>Edit</span>
@endsection

@section('content')
@if(request()->has('action') && request()->action === 'log_visit')
    <div class="alert alert-info shadow-sm mb-6 border-0 rounded-xl d-flex align-items-center" style="background: var(--primary); color: white;">
        <div class="p-2 rounded-lg bg-white/20 me-3">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        </div>
        <div>
            <h5 class="fw-bold mb-0">Daily Sales Visit Log Mode</h5>
            <p class="text-xs mb-0 opacity-80">You are recording details for a new field visit. Please scroll down to the visit form.</p>
        </div>
    </div>
@endif

<div class="page-header">
    <h1 class="page-title">Edit Lead: {{ $lead->company_name }}</h1>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Company Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $lead->company_name) }}" required>
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Client Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Client Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name', $lead->client_name) }}" required>
                    @error('client_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contact Person -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $lead->contact_person) }}">
                    @error('contact_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $lead->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $lead->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Service -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Service <span class="text-danger">*</span></label>
                    <select name="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                        <option value="">Select Service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $lead->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pipeline Stage -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pipeline Stage</label>
                    <select name="stage_id" class="form-select @error('stage_id') is-invalid @enderror">
                        <option value="">Select Stage...</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" {{ old('stage_id', $lead->stage_id) == $stage->id ? 'selected' : '' }}>
                                {{ $stage->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('stage_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="interested" {{ old('status', $lead->status) == 'interested' ? 'selected' : '' }}>Interested</option>
                        <option value="closed" {{ old('status', $lead->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address -->
                <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $lead->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('leads.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Lead</button>
            </div>
        </form>
    </div>
</div>

<div class="mt-8">
    <div class="page-header mb-4">
        <h2 class="page-title" style="font-size: 1.25rem;">Visit & Meeting Details</h2>
        <p class="text-xs text-muted-foreground">Log field visits and track relationship progression</p>
    </div>
    @livewire('dashboard.visit-form', ['lead' => $lead])
</div>
@endsection
