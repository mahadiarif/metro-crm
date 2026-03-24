<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lead Report</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }
    .header { background: #0f172a; color: #fff; padding: 20px 24px; margin-bottom: 20px; }
    .header h1 { font-size: 18px; font-weight: 700; }
    .header p { font-size: 10px; color: #94a3b8; margin-top: 3px; }
    .meta { display: flex; gap: 24px; margin-bottom: 16px; padding: 0 24px; }
    .stat { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 16px; flex: 1; }
    .stat-label { font-size: 9px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 16px; font-weight: 700; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin: 0 24px; width: calc(100% - 48px); }
    thead tr { background: #0f172a; color: #fff; }
    thead th { padding: 8px 10px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
    .badge { padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 600; text-transform: uppercase; }
    .badge-secondary { background: #e2e8f0; color: #475569; }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-warning { background: #fef3c7; color: #92400e; }
    .badge-danger { background: #fee2e2; color: #991b1b; }
    .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #94a3b8; padding: 0 24px; }
</style>
</head>
<body>
<div class="header">
    <h1>Lead Report — MetroNet</h1>
    <p>Generated on: {{ $generated_at }}</p>
</div>

<div class="meta">
    <div class="stat">
        <div class="stat-label">Total Leads</div>
        <div class="stat-value">{{ number_format(count($data)) }}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Created</th>
            <th>Company</th>
            <th>Contact Person</th>
            <th>Phone</th>
            <th>Service</th>
            <th>Status</th>
            <th>Assigned To</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $lead)
        @php
            $badgeClass = match($lead->status) {
                'closed' => 'badge-success',
                'interested' => 'badge-warning',
                'lost' => 'badge-danger',
                default => 'badge-secondary',
            };
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $lead->created_at->format('d M Y') }}</td>
            <td><strong>{{ $lead->company_name }}</strong></td>
            <td>{{ $lead->client_name }}</td>
            <td>{{ $lead->phone }}</td>
            <td><span class="badge badge-secondary">{{ $lead->service->name ?? 'N/A' }}</span></td>
            <td><span class="badge {{ $badgeClass }}">{{ strtoupper($lead->status) }}</span></td>
            <td>{{ $lead->assignedUser->name ?? 'Unassigned' }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center; padding: 20px; color:#64748b;">No records found.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">MetroNet Sales CRM &bull; Confidential &bull; {{ $generated_at }}</div>
</body>
</html>
