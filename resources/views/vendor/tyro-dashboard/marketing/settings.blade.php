@extends('tyro-dashboard::layouts.admin')

@section('title', 'Marketing Configuration')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}">/ Marketing</a>
<span>/ Configuration</span>
@endsection

@section('content')
    <livewire:marketing.marketing-settings />
@endsection
