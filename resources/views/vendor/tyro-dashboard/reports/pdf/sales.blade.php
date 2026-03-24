<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Report</title>
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
    .stat-value.green { color: #059669; }
    table { width: 100%; border-collapse: collapse; margin: 0 24px; width: calc(100% - 48px); }
    thead tr { background: #0f172a; color: #fff; }
    thead th { padding: 8px 10px; text-align: left; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
    .amount { text-align: right; font-weight: 700; color: #059669; }
    tfoot tr { background: #0f172a; color: #fff; }
    tfoot td { padding: 8px 10px; font-weight: 700; font-size: 11px; }
    .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #94a3b8; padding: 0 24px; }
</style>
</head>
<body>
<div class="header">
    <h1>Sales Report — MetroNet</h1>
    <p>Generated on: {{ $generated_at }}</p>
</div>

<div class="meta">
    <div class="stat">
        <div class="stat-label">Total Deals</div>
        <div class="stat-value">{{ number_format($total_deals) }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value green">৳ {{ number_format($total_revenue, 2) }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Avg. Deal Value</div>
        <div class="stat-value">৳ {{ $total_deals > 0 ? number_format($total_revenue / $total_deals, 2) : '0.00' }}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Company</th>
            <th>Executive</th>
            <th>Product</th>
            <th style="text-align:right;">Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $sale)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ optional($sale->closed_at)->format('d M Y') }}</td>
            <td>
                <strong>{{ $sale->lead->company_name ?? 'N/A' }}</strong><br>
                <span style="color:#64748b;">{{ $sale->lead->client_name ?? '' }}</span>
            </td>
            <td>{{ $sale->user->name ?? 'N/A' }}</td>
            <td>{{ $sale->service->name ?? 'N/A' }}</td>
            <td class="amount">৳ {{ number_format($sale->amount, 2) }}</td>
            <td style="color:#64748b;">{{ \Str::limit($sale->remarks, 40) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center; padding: 20px; color:#64748b;">No records found.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align:right;">TOTAL</td>
            <td style="text-align:right;">৳ {{ number_format($total_revenue, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>

<div class="footer">MetroNet Sales CRM &bull; Confidential &bull; {{ $generated_at }}</div>
</body>
</html>
