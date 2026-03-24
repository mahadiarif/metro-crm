<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Team Performance Report</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a2e; background: #fff; }
    .header { background: #0f172a; color: #fff; padding: 20px 24px; margin-bottom: 20px; }
    .header h1 { font-size: 16px; font-weight: 700; }
    .header p { font-size: 9px; color: #94a3b8; margin-top: 3px; }
    .meta { display: flex; gap: 15px; margin-bottom: 20px; padding: 0 24px; }
    .stat { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; flex: 1; }
    .stat-label { font-size: 8px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 14px; font-weight: 700; margin-top: 2px; }
    .stat-value.green { color: #059669; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    thead tr { background: #0f172a; color: #fff; }
    thead th { padding: 8px 10px; text-align: left; font-size: 8px; font-weight: 600; text-transform: uppercase; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; padding: 0 24px; }
    .section-title { font-size: 12px; font-weight: 700; margin: 0 24px 10px; padding-bottom: 5px; border-bottom: 2px solid #0f172a; color: #0f172a; }
</style>
</head>
<body>
<div class="header">
    <h1>Team Performance Report</h1>
    <p>Generated on: {{ $generated_at }}</p>
</div>

<div class="section-title">Summary Metrics</div>
<div class="meta">
    <div class="stat">
        <div class="stat-label">Team Members</div>
        <div class="stat-value">{{ number_format($aggregates->total_team_members) }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Total Leads</div>
        <div class="stat-value">{{ number_format($aggregates->total_leads) }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Total Sales</div>
        <div class="stat-value">{{ number_format($aggregates->total_sales) }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">MTD Team Revenue</div>
        <div class="stat-value green">৳ {{ number_format($aggregates->monthly_revenue, 2) }}</div>
    </div>
</div>

<div class="section-title">Detailed Performance Breakdown</div>
<div style="padding: 0 24px;">
    <table>
        <thead>
            <tr>
                <th>Executive</th>
                <th>Role</th>
                <th style="text-align: center;">Leads</th>
                <th style="text-align: center;">Visits</th>
                <th style="text-align: center;">Follow-ups</th>
                <th style="text-align: center;">Sales</th>
                <th style="text-align: right;">MTD Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teamMembers as $m)
            <tr>
                <td style="font-weight: 600;">{{ $m->name }}</td>
                <td>{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $m->role ?? 'N/A')) }}</td>
                <td style="text-align: center;">{{ $m->stats['total_leads'] }}</td>
                <td style="text-align: center;">{{ $m->stats['total_visits'] }}</td>
                <td style="text-align: center;">{{ $m->stats['total_followups'] }}</td>
                <td style="text-align: center; font-weight: 600;">{{ $m->stats['total_sales'] }}</td>
                <td style="text-align: right; font-weight: 700; color: #059669;">৳ {{ number_format($m->stats['monthly_revenue'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">MetroNet Sales CRM &bull; Team Performance Report &bull; {{ $generated_at }}</div>
</body>
</html>
