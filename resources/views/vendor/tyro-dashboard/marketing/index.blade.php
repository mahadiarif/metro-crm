@extends('tyro-dashboard::layouts.admin')

@section('title', 'Marketing Campaigns')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span>/ Marketing</span>
@endsection

@section('content')
    <livewire:marketing.campaign-index />
@endsection
