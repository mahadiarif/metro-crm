@extends('tyro-dashboard::layouts.app')

@section('title', 'Log Daily Sales Visit')

@section('breadcrumb')
<a href="{{ route('tyro-dashboard.index') }}">Dashboard</a>
<span class="breadcrumb-separator">/</span>
<a href="{{ route('sales-visits.index') }}">Sales Visit</a>
<span class="breadcrumb-separator">/</span>
<span>Create</span>
@endsection

@section('content')
<div class="container-fluid py-4">
    @livewire('dashboard.visit-form', ['lead' => $lead ?? null])
</div>
@endsection
