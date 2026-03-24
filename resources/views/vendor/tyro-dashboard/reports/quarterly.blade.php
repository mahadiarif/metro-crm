@extends('tyro-dashboard::layouts.admin')

@section('title', 'Quarterly Sales Report')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.reports.leads') }}">Reports</a>
<span>/ Quarterly</span>
@endsection

@section('content')
    <livewire:dashboard.quarterly-sales-report />
@endsection
