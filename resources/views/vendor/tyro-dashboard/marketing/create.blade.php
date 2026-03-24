@extends('tyro-dashboard::layouts.admin')

@section('title', 'Create Campaign')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}">/ Marketing</a>
<span>/ Create</span>
@endsection

@section('content')
<div class="page-header">
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Create Campaign</h1>
            <p class="page-description">Design and target your marketing message to specific audience segments.</p>
        </div>
        <div class="page-header-actions">
            <a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}" class="btn btn-secondary flex items-center gap-2">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Campaigns
            </a>
        </div>
    </div>
</div>

<livewire:marketing.campaign-create />
@endsection
