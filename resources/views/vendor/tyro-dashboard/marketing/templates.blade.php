@extends('tyro-dashboard::layouts.admin')

@section('title', 'Marketing Templates')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}">/ Marketing</a>
<span>/ Templates</span>
@endsection

@section('content')
    <div class="container-fluid py-4">
        @livewire('marketing.template-manager')
    </div>
@endsection
