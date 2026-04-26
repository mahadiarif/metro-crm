@extends('tyro-dashboard::layouts.app')

@section('title', 'Log Sales Call')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.sales-calls.index') }}">Sales Call</a>
<span class="breadcrumb-separator">/</span>
<span>Log New</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Log Sales Call</h1>
            <p class="page-description">Record outcome and engagement details for tele-sales activity</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('tyro-dashboard.sales-calls.index') }}" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to History
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        @livewire('sales-call.outcome-modal', [
            'leadId' => request('lead_id'), 
            'isModalOpen' => true
        ])
    </div>
</div>

@endsection
