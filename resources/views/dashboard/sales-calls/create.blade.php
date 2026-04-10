@extends('tyro-dashboard::layouts.app')

@section('title', 'Log Sales Call Activity')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.sales-calls.index') }}">Sales Calls</a>
<span class="breadcrumb-separator">/</span>
<span>Record Session</span>
@endsection

@section('content')
<div class="px-2">
    <div class="page-header d-flex justify-content-between align-items-center mb-6 text-left">
        <div>
            <h1 class="page-title text-slate-900 fw-bold mb-1">Tele-Sales Engagement Log</h1>
            <p class="text-xs text-slate-500 font-medium">Capture inbound/outbound call outcomes and service interest matrix.</p>
        </div>
        <a href="{{ route('tyro-dashboard.sales-calls.index') }}" class="btn btn-outline-slate btn-sm rounded-xl fw-bold text-xs">
            Back to History
        </a>
    </div>

    <!-- Expert Call Logging Form (Full-Page Mode) -->
    <div class="card border-0 shadow-sm rounded-2xl overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
        <div class="card-body p-0">
            @livewire('sales-call.outcome-modal', [
                'leadId' => request('lead_id'), 
                'isModalOpen' => true
            ])
        </div>
    </div>
</div>

<style>
    /* Flatten the modal behavior for full-page resource experience */
    .fixed.inset-0.z-50 {
        position: static !important;
        background: transparent !important;
        backdrop-filter: none !important;
    }
    .min-h-screen {
        min-height: auto !important;
        padding: 0 !important;
    }
    .max-w-4xl {
        max-width: 100% !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    .animate-in.fade-in.zoom-in {
        animation: none !important;
    }
</style>
@endsection
