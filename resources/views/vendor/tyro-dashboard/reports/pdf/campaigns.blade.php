<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Marketing Campaigns Report</title>
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
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    thead tr { background: #0f172a; color: #fff; }
    thead th { padding: 8px 10px; text-align: left; font-size: 8px; font-weight: 600; text-transform: uppercase; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
    .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; padding: 0 24px; }
    .badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: 600; text-transform: uppercase; }
    .badge-email { background: #e0f2fe; color: #0369a1; }
    .badge-sms { background: #f1f5f9; color: #475569; }
    .badge-sent { background: #dcfce7; color: #166534; }
    .badge-scheduled { background: #e0f2fe; color: #0369a1; }
    .badge-draft { background: #f1f5f9; color: #475569; }
</style>
</head>
<body>
<div class="header">
    <h1>Marketing Campaigns Report</h1>
    <p>Generated on: {{ $generated_at }}</p>
</div>

<div style="padding: 0 24px;">
    <table>
        <thead>
            <tr>
                <th>Campaign Name</th>
                <th>Type</th>
                <th>Status</th>
                <th style="text-align: center;">Recipients</th>
                <th>Created By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($campaigns as $c)
            <tr>
                <td style="font-weight: 600;">{{ $c->name }}</td>
                <td>
                    <span class="badge {{ $c->type === 'email' ? 'badge-email' : 'badge-sms' }}">
                        {{ strtoupper($c->type) }}
                    </span>
                </td>
                <td>
                    <span class="badge 
                        @if($c->status === 'sent') badge-sent
                        @elseif($c->status === 'scheduled') badge-scheduled
                        @else badge-draft @endif">
                        {{ ucfirst($c->status) }}
                    </span>
                </td>
                <td style="text-align: center;">{{ $c->recipients_count }}</td>
                <td>{{ $c->creator->name ?? 'N/A' }}</td>
                <td>{{ $c->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">MetroNet Sales CRM &bull; Marketing Campaigns Report &bull; {{ $generated_at }}</div>
</body>
</html>
