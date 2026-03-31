@extends('tyro-dashboard::layouts.app')

@section('content')
<!-- Improvement 1: Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small fw-semibold text-uppercase mb-1">আজকের Calls</div>
                <div class="h3 mb-0">{{ $todayCalls->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="background: #fff5f5;">
            <div class="card-body">
                <div class="text-danger small fw-semibold text-uppercase mb-1">Overdue</div>
                <div class="h3 mb-0 text-danger">{{ $overdueCalls->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="background: #f0fff4;">
            <div class="card-body">
                <div class="text-success small fw-semibold text-uppercase mb-1">কথা হয়েছে</div>
                <div class="h3 mb-0 text-success">{{ $answeredCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="background: #fffdf0;">
            <div class="card-body">
                <div class="text-warning small fw-semibold text-uppercase mb-1">Answer Rate</div>
                <div class="h3 mb-0 text-warning">{{ $answerRate }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-0">
        <ul class="nav nav-tabs card-header-tabs" id="callTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'today' ? 'active fw-bold' : '' }}" href="?tab=today">Today's Calls</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'overdue' ? 'active fw-bold' : '' }}" href="?tab=overdue">
                    Overdue Calls
                    @if($overdueCalls->count() > 0)
                        <span class="badge bg-danger ms-1">{{ $overdueCalls->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'history' ? 'active fw-bold' : '' }}" href="?tab=history">Full Log History</a>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="tab-content" id="callTabsContent">
            <!-- Today's Tab -->
            @if($tab === 'today')
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <!-- Improvement 6: Table Header Styling -->
                            <th class="ps-4 py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Lead / Company</th>
                            <th class="py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Phone Number</th>
                            <th class="py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Last Outcome</th>
                            <!-- Improvement 2: Next Call Column -->
                            <th class="py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Next Call</th>
                            <th class="pe-4 text-end py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayCalls as $lead)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $lead->company_name }}</div>
                                <div class="text-muted small">{{ $lead->client_name }}</div>
                            </td>
                            <td>
                                <!-- Improvement 4: Phone Formatting -->
                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none h5 fw-bold text-primary">
                                    {{ strlen($lead->phone) >= 11 ? substr($lead->phone, 0, 5).'-'.substr($lead->phone, 5) : $lead->phone }}
                                </a>
                            </td>
                            <td>
                                @if($lead->latestCall)
                                    <!-- Improvement 3: Color-coded Badges -->
                                    @php
                                        $outcomeClass = [
                                            'reached' => 'bg-success',
                                            'answered' => 'bg-success',
                                            'busy' => 'bg-warning text-dark',
                                            'no_answer' => 'bg-danger',
                                            'callback_requested' => 'bg-info text-dark',
                                        ][$lead->latestCall->outcome] ?? 'bg-secondary';
                                        
                                        $outcomeLabel = [
                                            'reached' => 'কথা হয়েছে',
                                            'answered' => 'কথা হয়েছে',
                                            'busy' => 'ব্যস্ত ছিলেন',
                                            'no_answer' => 'ধরেননি',
                                            'callback_requested' => 'Callback চাই',
                                        ][$lead->latestCall->outcome] ?? strtoupper(str_replace('_', ' ', $lead->latestCall->outcome));
                                    @endphp
                                    <span class="badge rounded-pill {{ $outcomeClass }}">{{ $outcomeLabel }}</span>
                                    <div class="text-muted xx-small">On {{ $lead->latestCall->called_at->format('M d, H:i') }}</div>
                                @else
                                    <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary">Never called</span>
                                @endif
                            </td>
                            <td>
                                <!-- Improvement 2: Next Call Column Logic -->
                                @if($lead->latestCall && $lead->latestCall->next_call_at)
                                    <div class="small fw-semibold text-muted">{{ $lead->latestCall->next_call_at->format('D h:i A') }}</div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <button onclick="Livewire.dispatch('openOutcomeModal', { leadId: {{ $lead->id }} })" class="btn btn-sm btn-primary px-3 rounded-pill">
                                    Mark Called
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <!-- Improvement 7: Empty State Icon -->
                            <td colspan="5" class="text-center py-5 text-muted">
                                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 d-block mx-auto opacity-25">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                No calls scheduled for today. Great job!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Overdue Tab -->
            @if($tab === 'overdue')
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Lead / Company</th>
                            <th class="py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Phone Number</th>
                            <th class="py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Days Overdue</th>
                            <th class="pe-4 text-end py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdueCalls as $lead)
                        <!-- Improvement 5: Overdue Row Highlight -->
                        <tr style="background: #fff5f5;">
                            <td class="ps-4">
                                <div class="fw-bold">{{ $lead->company_name }}</div>
                                <div class="text-muted small">{{ $lead->client_name }}</div>
                            </td>
                            <td>
                                <!-- Improvement 4 & 5: Red Phone Color in Overdue -->
                                <a href="tel:{{ $lead->phone }}" class="text-decoration-none h5 fw-bold text-danger">
                                    {{ strlen($lead->phone) >= 11 ? substr($lead->phone, 0, 5).'-'.substr($lead->phone, 5) : $lead->phone }}
                                </a>
                            </td>
                            <td>
                                @php $overdueDays = now()->diffInDays($lead->latestCall->next_call_at); @endphp
                                <span class="badge bg-danger">{{ $overdueDays }} Days Past Due</span>
                            </td>
                            <td class="pe-4 text-end">
                                <button onclick="Livewire.dispatch('openOutcomeModal', { leadId: {{ $lead->id }} })" class="btn btn-sm btn-danger px-3 rounded-pill">
                                    Call Now
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2 d-block mx-auto opacity-25">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                No overdue calls. You're all caught up!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            <!-- History Tab -->
            @if($tab === 'history')
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Date & User</th>
                            <th class="py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Lead</th>
                            <th class="py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Outcome</th>
                            <th class="pe-4 py-3 text-uppercase small text-muted fw-semibold" style="letter-spacing:.05em">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($historyCalls as $call)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold small text-muted">{{ $call->called_at->format('M d, Y H:i') }}</div>
                                <div class="text-muted xx-small">Ex: {{ $call->user->name }}</div>
                            </td>
                            <td>
                                <div class="small fw-bold">{{ $call->lead->company_name }}</div>
                            </td>
                            <td>
                                @php
                                    $outcomeClass = [
                                        'reached' => 'bg-success',
                                        'answered' => 'bg-success',
                                        'busy' => 'bg-warning text-dark',
                                        'no_answer' => 'bg-danger',
                                        'callback_requested' => 'bg-info text-dark',
                                    ][$call->outcome] ?? 'bg-secondary';
                                    
                                    $outcomeLabel = [
                                        'reached' => 'কথা হয়েছে',
                                        'answered' => 'কথা হয়েছে',
                                        'busy' => 'ব্যস্ত ছিলেন',
                                        'no_answer' => 'ধরেননি',
                                        'callback_requested' => 'Callback চাই',
                                    ][$call->outcome] ?? strtoupper(str_replace('_', ' ', $call->outcome));
                                @endphp
                                <span class="badge rounded-pill {{ $outcomeClass }}">{{ $outcomeLabel }}</span>
                            </td>
                            <td class="pe-4">
                                <p class="text-muted mb-0 small text-truncate" style="max-width: 300px;">{{ $call->notes }}</p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-4 py-3">
                    {{ $historyCalls->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@livewire('sales-call.outcome-modal')

<style>
.xx-small { font-size: 0.65rem; }
.nav-tabs .nav-link { border: 0; color: #6c757d; }
.nav-tabs .nav-link.active { border-bottom: 2px solid #0d6efd !important; color: #0d6efd; }
</style>
@endsection
