<div>
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <h1 class="page-title">Quarterly Sales Performance</h1>
                <p class="page-description">Detailed breakdown of sales by quarter and team hierarchy.</p>
            </div>
            <div class="page-header-actions" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <button wire:click="export('csv')" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    CSV
                </button>
                <button wire:click="export('excel')" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Excel
                </button>
                <button wire:click="export('pdf')" class="btn btn-secondary" style="display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                <div class="form-group" style="margin-bottom: 0; flex: 0 0 120px;">
                    <label class="form-label">Year</label>
                    <select wire:model.live="year" class="form-select">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                    <label class="form-label">Team Leader</label>
                    <select wire:model.live="teamLeaderId" class="form-select">
                        <option value="">All Team Leaders</option>
                        @foreach($teamLeaders as $leader)
                            <option value="{{ $leader->id }}">{{ $leader->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
                    <label class="form-label">Sales Executive</label>
                    <select wire:model.live="userId" class="form-select">
                        <option value="">All Sales Executives</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $userId ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filters-bar" style="display: flex; gap: 10px; align-items: center; margin-top: 1.25rem; border-top: 1px solid var(--border); padding-top: 1.25rem;">
                <div class="search-box" style="display: flex; gap: 10px; align-items: center; flex: 1; max-width: 400px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; color: var(--muted-foreground);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-input" placeholder="Search sales person...">
                </div>
                @if($search)
                    <button wire:click="$set('search', '')" class="btn btn-ghost" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Clear Search</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        @foreach($quarterlySummary as $q => $data)
            <div class="card" style="flex: 1; min-width: 200px; padding: 1.25rem 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary); background: rgba(var(--primary-rgb), 0.1); padding: 0.25rem 0.625rem; rounded: 9999px; text-transform: uppercase; letter-spacing: 0.05em;">
                        Q{{ $q }} Summary
                    </div>
                    @if($data['percent'] >= 100)
                        <span style="color: #10b981;">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </span>
                    @endif
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <div style="font-size: 0.8125rem; color: var(--muted-foreground); font-weight: 500;">Achieved Sales</div>
                    <div style="font-size: 1.5rem; font-weight: 700; margin-top: 0.125rem;">৳ {{ number_format($data['achieved'], 0) }}</div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: flex-end; padding-top: 0.75rem; border-top: 1px solid var(--border);">
                    <div>
                        <div style="font-size: 0.6875rem; color: var(--muted-foreground);">Target</div>
                        <div style="font-size: 0.875rem; font-weight: 600;">৳ {{ number_format($data['target'], 0) }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.6875rem; color: var(--muted-foreground);">Achievement</div>
                        <div style="font-size: 0.875rem; font-weight: 700; color: {{ $data['percent'] >= 100 ? '#10b981' : '#f59e0b' }}">
                            {{ $data['percent'] }}%
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Individual Sales Report -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="card-header">
            <h3 class="card-title">Individual Sales Performance</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container shadow-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="position: sticky; left: 0; background: var(--card); z-index: 10;">Sales Person</th>
                            @foreach(['Jan', 'Feb', 'Mar', 'Q1_Total', 'Apr', 'May', 'Jun', 'Q2_Total', 'Jul', 'Aug', 'Sep', 'Q3_Total', 'Oct', 'Nov', 'Dec', 'Q4_Total', 'Year_Total'] as $col)
                                <th style="text-align: center; {{ str_contains($col, 'Total') ? 'background: rgba(var(--primary-rgb), 0.03); font-weight: 700;' : '' }}">
                                    {{ str_replace('_', ' ', $col) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($individualPerformance as $perf)
                            <tr>
                                <td style="font-weight: 600; position: sticky; left: 0; background: var(--card); z-index: 5;">{{ $perf['name'] }}</td>
                                @foreach($perf['months'] as $month => $amount)
                                    <td style="text-align: center;">{{ number_format($amount, 0) }}</td>
                                    @if($month == 3) <td style="text-align: center; font-weight: 700; background: rgba(var(--primary-rgb), 0.05); color: var(--primary);">৳ {{ number_format($perf['q1'], 0) }}</td> @endif
                                    @if($month == 6) <td style="text-align: center; font-weight: 700; background: rgba(var(--primary-rgb), 0.05); color: var(--primary);">৳ {{ number_format($perf['q2'], 0) }}</td> @endif
                                    @if($month == 9) <td style="text-align: center; font-weight: 700; background: rgba(var(--primary-rgb), 0.05); color: var(--primary);">৳ {{ number_format($perf['q3'], 0) }}</td> @endif
                                    @if($month == 12) <td style="text-align: center; font-weight: 700; background: rgba(var(--primary-rgb), 0.05); color: var(--primary);">৳ {{ number_format($perf['q4'], 0) }}</td> @endif
                                @endforeach
                                <td style="text-align: center; font-weight: 800; background: rgba(var(--primary-rgb), 0.1); color: var(--primary);">৳ {{ number_format($perf['total'], 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Team Performance Report -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Team Performance Breakdown</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container shadow-none">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Team Leader</th>
                            <th style="text-align: center;">Q1 Sales</th>
                            <th style="text-align: center;">Q2 Sales</th>
                            <th style="text-align: center;">Q3 Sales</th>
                            <th style="text-align: center;">Q4 Sales</th>
                            <th style="text-align: right;">Total Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamPerformance as $team)
                            <tr>
                                <td style="font-weight: 600;">{{ $team['leader'] }}</td>
                                <td style="text-align: center;">৳ {{ number_format($team['q1'], 0) }}</td>
                                <td style="text-align: center;">৳ {{ number_format($team['q2'], 0) }}</td>
                                <td style="text-align: center;">৳ {{ number_format($team['q3'], 0) }}</td>
                                <td style="text-align: center;">৳ {{ number_format($team['q4'], 0) }}</td>
                                <td style="text-align: right; font-weight: 700; color: var(--primary);">৳ {{ number_format($team['total'], 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 3rem; color: var(--muted-foreground);">No team data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
