@extends('tyro-dashboard::layouts.admin')

@section('title', 'Campaign Details')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}">/ Marketing</a>
<span>/ Details</span>
@endsection

@section('content')
    <livewire:marketing.campaign-show :campaign="$campaign" />
@endsection
