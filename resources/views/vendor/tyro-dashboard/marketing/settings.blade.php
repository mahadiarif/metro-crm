@extends('tyro-dashboard::layouts.admin')

@section('title', 'Marketing Configuration')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('tyro-dashboard.marketing.campaigns.index') }}">Marketing</a>
<span class="breadcrumb-separator">/</span>
<span>Configuration</span>
@endsection

@section('content')
    <livewire:marketing.marketing-settings />
@endsection
