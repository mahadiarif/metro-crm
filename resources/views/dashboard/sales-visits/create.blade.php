@extends('tyro-dashboard::layouts.app')

@section('title', 'Log New Sales Visit')

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <nav class="breadcrumb-container" aria-label="breadcrumb">
                <ol class="breadcrumb" style="display: flex; list-style: none; padding: 0; margin: 0 0 8px; font-size: 0.75rem; gap: 8px; color: var(--muted-foreground);">
                    <li class="breadcrumb-item"><a href="{{ route('tyro-dashboard.index') }}" style="color: inherit; text-decoration: none;">Dashboard</a></li>
                    <li class="breadcrumb-item" style="display: flex; align-items: center; gap: 8px; opacity: 0.7;">/ <a href="{{ route('tyro-dashboard.sales-visits.index') }}" style="color: inherit; text-decoration: none;">Sales Visits</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--foreground);">/ Log New Visit</li>
                </ol>
            </nav>
            <h1 class="page-title" style="font-size: 1.5rem; font-weight: 800; color: var(--foreground); margin: 0;">Log New Sales Visit</h1>
            <p class="text-xs text-slate-500 font-medium">Record detailed field intelligence and customer mood</p>
        </div>
        <div>
            <a href="{{ route('tyro-dashboard.sales-visits.index') }}" class="btn btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="m15 18-6-6 6-6"/></svg>
                Back to List
            </a>
        </div>
    </div>
</div>

<div class="content-container" style="margin-top: 24px;">
    @livewire('sales-visit.visit-modal', [
        'leadId' => request('lead_id'), 
        'isModalOpen' => true, 
        'isStandalone' => true
    ])
</div>

<style>
    .page-header { margin-bottom: 0; border-bottom: 1px solid var(--border); padding-bottom: 24px; }
    .btn-outline { background: #fff; border: 1px solid var(--border); color: var(--foreground); display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 6px; font-weight: 600; transition: 0.2s; }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }
</style>
@endsection
